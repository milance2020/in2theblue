<?php

$_output['view'] = 'shop/product';
$_output['html_model'] = 'product';
$_output['breadcrumbs_enabled'] = true;
require_once FILE_CONNECT;
require_once FILE_SEO_HELPER;
require_once FILE_COMMENT_MODERATION;
require_once FILE_REPORT_MODERATION;
require_once FILE_PRODUCT_FUNCTIONS;

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

$product = loadProduct(
    $conn,
    $id,
    $categorySlug,
    $productSlug
);

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


// after slug lookup, use real numeric product id everywhere else.
$id = (int) $product['id'];

// =========================================================
// SIZES QUERY
// =========================================================

$sizes = loadProductSizes(
    $conn,
    $id
);



// =========================================================
// RECOMMENDED PRODUCTS
// =========================================================
$recommendedProducts = loadRecommendedProducts(
    $conn,
    $id
);

// =========================================================
// COMMENTS API
// =========================================================
$user_id = $_SESSION['user_id'] ?? 0;

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'get_comments'
) {
    // Product stranica koristi isti model i za mali JSON comments API.

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
    // Komentar prvo prodje kroz jednostavnu moderaciju, pa ide u bazu.

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
    // Reply je isti princip kao komentar, samo ima parent_id.
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
    // Report cuvamo posebno da korisnik ne moze vise puta prijaviti isto.

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
            'message' => 'Već ste prijavili ovaj komentar.'
        ]);

        exit;
    }

    // =====================================================
    // SELF REPORT BLOCK
    // =====================================================

    if (!isCommentReportableByUser(
        $conn,
        $commentId,
        $user_id
    )) {

        echo json_encode([
            'success' => false,
            'message' => 'Niste mogu prijaviti svoj komentar.'
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
            'message' => 'Neuspješno je prijavljivanje komentara.'
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
    'url' => productUrl($product),
]);
