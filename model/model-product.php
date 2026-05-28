<?php

$_output['view'] = 'shop/product';
$_output['html_model'] = 'product';
$_output['breadcrumbs_enabled'] = true;
require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;
require_once FILE_COMMENT_MODERATION;
require_once FILE_REPORT_MODERATION;

$product = null;
$sizes = [];

// =========================================================
// PRODUCT ROUTE DATA
// Supports:
// index.php?page=product&id=15
// /shop/category-slug/product-slug
// =========================================================

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

$categorySlug = trim($_GET['category_slug'] ?? '');
$productSlug  = trim($_GET['product_slug'] ?? '');

if (
    $id <= 0 &&
    ($categorySlug === '' || $productSlug === '')
) {
    if (isset($_GET['action'])) {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => 'Invalid product'
        ]);

        exit;
    }

    die('Invalid product');
}

// =========================================================
// PRODUCT QUERY
// =========================================================

if ($id > 0) {

    $stmt = $conn->prepare("
        SELECT
            p.id,
            p.name,
            p.slug,
            p.description,
            p.price,
            p.image_path,

            c.label AS category_label,
            c.slug AS category_slug

        FROM products2 p

        LEFT JOIN categories c
            ON c.id = p.category_id

        WHERE p.id = ?
          AND p.deleted_at IS NULL

        LIMIT 1
    ");

    $stmt->bind_param("i", $id);

} else {

    $stmt = $conn->prepare("
        SELECT
            p.id,
            p.name,
            p.slug,
            p.description,
            p.price,
            p.image_path,

            c.label AS category_label,
            c.slug AS category_slug

        FROM products2 p

        INNER JOIN categories c
            ON c.id = p.category_id

        WHERE c.slug = ?
          AND p.slug = ?
          AND p.deleted_at IS NULL

        LIMIT 1
    ");

    $stmt->bind_param(
        "ss",
        $categorySlug,
        $productSlug
    );
}

$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

// =========================================================
// PRODUCT NOT FOUND
// =========================================================

if (!$product) {

    if (isset($_GET['action'])) {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);

        exit;
    }

    die("Product not found");
}

// Very important:
// after slug lookup, use real numeric product id everywhere else.
$id = (int) $product['id'];

// =========================================================
// SIZES QUERY
// =========================================================

$stmt = $conn->prepare("
    SELECT
        size,
        stock
    FROM product_sizes
    WHERE product_id = ?
    ORDER BY size ASC
");

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $sizes[] = $row;
}


// =========================================================
// RECOMMENDED PRODUCTS
// =========================================================
// =========================================================
// current male   → male + unisex
//current female → female + unisex
//current unisex → male + female + unisex
// =========================================================

$stmt = $conn->prepare("
    SELECT
        p.id,
        p.name,
        p.slug,
        p.price,
        p.image_path,

        c.label AS category_label,
        c.slug AS category_slug

    FROM products2 p

    LEFT JOIN categories c
        ON c.id = p.category_id

    WHERE p.deleted_at IS NULL

      AND p.id != ?

      AND p.category_id = (
            SELECT category_id
            FROM products2
            WHERE id = ?
      )

      AND (
            (
                (
                    SELECT gender
                    FROM products2
                    WHERE id = ?
                ) = 'unisex'

                AND p.gender IN (
                    'male',
                    'female',
                    'unisex'
                )
            )

            OR

            (
                (
                    SELECT gender
                    FROM products2
                    WHERE id = ?
                ) != 'unisex'

                AND (
                    p.gender = (
                        SELECT gender
                        FROM products2
                        WHERE id = ?
                    )

                    OR p.gender = 'unisex'
                )
            )
      )

    ORDER BY p.created_at DESC

    LIMIT 4
");

$stmt->bind_param(
    "iiiii",
    $id,
    $id,
    $id,
    $id,
    $id
);

$stmt->execute();

$recommendedProducts =
    $stmt->get_result();

// =========================================================
// COMMENTS API
// =========================================================
$user_id = $_SESSION['user_id'] ?? 0;

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'get_comments'
) {

    header('Content-Type: application/json');

    $stmt = $conn->prepare("
            SELECT
            comments.id,
            comments.parent_id,
            comments.comment,
            comments.created_at,
            comments.status,
            comments.moderation_reasons,

            users.username

            FROM comments

            JOIN users
            ON comments.user_id = users.id

            WHERE comments.product_id = ?
            AND comments.status = 'visible'

            ORDER BY comments.created_at DESC
    ");

    // =========================
    // SQL ERROR
    // =========================

    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => $conn->error
        ]);

        exit;
    }

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $result = $stmt->get_result();

    $comments = [];
    $replies = [];

    while ($row = $result->fetch_assoc()) {

        // =====================
        // PARENT COMMENTS
        // =====================

        if ($row['parent_id'] == null) {

            $comments[] = [
                'id' => (int) $row['id'],
                'username' => $row['username'],
                'comment' => $row['comment'],
                'created_at' => $row['created_at'],
                'replies' => []
            ];
        }

        // =====================
        // REPLIES
        // =====================
        else {

            $replies[] = [
                'id' => (int) $row['id'],
                'parent_id' => (int) $row['parent_id'],
                'username' => $row['username'],
                'comment' => $row['comment'],
                'created_at' => $row['created_at'],
            ];
        }
    }
    foreach ($replies as $reply) {

        foreach ($comments as &$comment) {

            if (
                $comment['id'] ===
                $reply['parent_id']
            ) {

                $comment['replies'][] =
                    $reply;
            }
        }
    }


    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);

    exit;
}
// =========================================================
// ADD COMMENT API
// =========================================================

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'add_comment'
) {

    header('Content-Type: application/json');

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $username = $_SESSION['username'] ?? '';


    $comment =
        trim($input['comment'] ?? '');



    // =====================================================
    // VALIDATION
    // =====================================================

    if ($user_id <= 0 || $comment === '') {

        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);

        exit;
    }



    // =====================================================
    // MODERATION
    // =====================================================

    $moderation = moderateComment(
        $conn,
        $user_id,
        $comment
    );

    $status = $moderation['status'];

    $reasons = json_encode(
        $moderation['reasons']
    );



    // =====================================================
    // INSERT COMMENT
    // =====================================================

    $stmt = $conn->prepare("
        INSERT INTO comments (
            product_id,
            user_id,
            comment,
            status,
            moderation_reasons
        )
        VALUES (?, ?, ?, ?, ?)
    ");

    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => $conn->error
        ]);

        exit;
    }

    $stmt->bind_param(
        "iisss",
        $id,
        $user_id,
        $comment,
        $status,
        $reasons
    );

    $success = $stmt->execute();



    // =====================================================
    // ERROR
    // =====================================================

    if (!$success) {

        echo json_encode([
            'success' => false,
            'message' => $stmt->error
        ]);

        exit;
    }



    // =====================================================
    // SUCCESS
    // =====================================================

    echo json_encode([
        'success' => true,
        'status' => $status
    ]);

    exit;
}


// =========================
// ADD REPLY API
// =========================
if (
    isset($_GET['action']) &&
    $_GET['action'] === 'add_reply'
) {
    header('Content-Type: application/json');

    $input = json_decode(
        file_get_contents('php://input'),
        true

    );
    $parentID = (int) trim($input['parent_id'] ?? '');
    $username = $_SESSION['username'];
    $reply = trim($input['reply'] ?? '');




    if ($parentID <= 0 || $reply === '') {

        echo json_encode([
            'success' => false,
            'message' => 'All fields are required.'
        ]);

        exit;
    }

    // =====================================================
    // MODERATION
    // =====================================================

    $moderation = moderateComment(
        $conn,
        $user_id,
        $reply
    );

    $status = $moderation['status'];

    $reasons = json_encode(
        $moderation['reasons']
    );

    $stmt = $conn->prepare('
        INSERT INTO comments(product_id,parent_id,user_id,comment,status,moderation_reasons)
        VALUES(?,?,?,?,?,?)
    ');
    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => $conn->error
        ]);

        exit;
    }
    $stmt->bind_param(
        "iiisss",
        $id,
        $parentID,
        $user_id,
        $reply,
        $status,
        $reasons
    );
    $success = $stmt->execute();

    if (!$success) {

        echo json_encode([
            'success' => false,
            'message' => $stmt->error
        ]);

        exit;
    }

    echo json_encode([
        'success' => true,
        'status' => $status
    ]);

    exit;




}

// =========================================================
// REPORT COMMENT API
// =========================================================

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'add_report'
) {

    header('Content-Type: application/json');

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $commentId = (int) (
        $input['comment_id'] ?? 0
    );


    // =====================================================
    // VALIDATION
    // =====================================================

    if (
        $user_id <= 0 ||
        $commentId <= 0
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid request.'
        ]);

        exit;
    }


    // =====================================================
    // DUPLICATE REPORT
    // =====================================================

    if (
        hasUserReportedComment(
            $conn,
            $commentId,
            $user_id
        )
    ) {

        echo json_encode([
            'success' => false,
            'message' => 'You already reported this comment.'
        ]);

        exit;
    }


    // =====================================================
    // INSERT REPORT
    // =====================================================

    $success = addCommentReport(
        $conn,
        $commentId,
        $user_id
    );

    if (!$success) {

        echo json_encode([
            'success' => false,
            'message' => 'Failed to report comment.'
        ]);

        exit;
    }


    // =====================================================
    // APPLY REPORT RULES
    // =====================================================

    $newStatus = moderateReportedComment(
        $conn,
        $commentId
    );


    // =====================================================
    // SUCCESS
    // =====================================================

    echo json_encode([
        'success' => true,
        'status' => $newStatus
    ]);

    exit;
}
// =========================================================
// SEO
// =========================================================

setSEO('product', [
    'id' => $product['id'],
    'name' => $product['name'],
    'description' => $product['description'],
    'category' => $product['category_label'],
    'category_slug' => $product['category_slug'],
    'url' => pageUrl('product', [
        'id' => $product['id']
    ]),
]);