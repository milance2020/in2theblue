<?php
if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'get_admin_comments'
) {

    header('Content-Type: application/json');
    $status = $_GET['status'] ?? 'pending';

    //reported comments
    if ($status === 'reported') {

        $stmt = $conn->prepare("
        SELECT 
            comments.id,
            comments.parent_id,
            comments.comment,
            comments.status,
            comments.moderation_reasons,
            comments.created_at,

            users.username,

            COUNT(comment_reports.id)
            AS report_count

        FROM comments

        JOIN users
        ON comments.user_id = users.id

        LEFT JOIN comment_reports
        ON comment_reports.comment_id =
            comments.id

        GROUP BY comments.id

        HAVING report_count >= 3

        ORDER BY report_count DESC,
                 comments.created_at DESC
    ");

    } else if ($status === 'all') {

        $stmt = $conn->prepare("
        SELECT 
            comments.id,
            comments.parent_id,
            comments.comment,
            comments.status,
            comments.moderation_reasons,
            comments.created_at,

            users.username,

            COUNT(comment_reports.id)
            AS report_count

        FROM comments

        JOIN users
        ON comments.user_id = users.id

        LEFT JOIN comment_reports
        ON comment_reports.comment_id =
            comments.id

        GROUP BY comments.id

        

        ORDER BY report_count DESC,
                 comments.created_at DESC");
    } else {
        //FILTERED
        $stmt = $conn->prepare("
        SELECT 
            comments.id,
            comments.parent_id,
            comments.comment,
            comments.status,
            comments.moderation_reasons,
            comments.created_at,

            users.username,

            COUNT(comment_reports.id)
            AS report_count

        FROM comments

        JOIN users
        ON comments.user_id = users.id

        LEFT JOIN comment_reports
        ON comment_reports.comment_id =
            comments.id
        WHERE comments.status = ?
        GROUP BY comments.id

        
        
        ORDER BY report_count DESC,
                 comments.created_at DESC");

        $stmt->bind_param("s", $status);

    }



    //SQL ERROR

    if (!$stmt) {
        echo json_encode([
            'sucess' => false,
            'message' => $conn->error
        ]);
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $comments = [];

    while ($row = $result->fetch_assoc()) {

        $comments[] = [
            'id' => (int) $row['id'],

            'parent_id' =>
                $row['parent_id']
                ? (int) $row['parent_id']
                : null,

            'username' => $row['username'],
            'comment' => $row['comment'],
            'status' => $row['status'],
            'report_count' => (int) $row['report_count'],
            'moderation_reasons' =>
                json_decode(
                    $row['moderation_reasons'],
                    true
                ),

            'created_at' => $row['created_at'],

        ];

    }

    echo json_encode([
        'success' => true,
        'comments' => $comments
    ]);
    exit;


}

// =========================================================
// APROVE COMMENT
// =========================================================

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'approve_comment'
) {

    header('Content-Type: application/json');


    // =====================================================
    // INPUT
    // =====================================================

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $commentId =
        (int) ($input['comment_id'] ?? 0);


    // =====================================================
    // VALIDATION
    // =====================================================

    if ($commentId <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid comment ID.'
        ]);

        exit;
    }


    // =====================================================
    // UPDATE STATUS
    // =====================================================

    $stmt = $conn->prepare("
        UPDATE comments
        SET status = 'visible'
        WHERE id = ?
    ");


    // =====================================================
    // SQL ERROR
    // =====================================================

    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => $conn->error
        ]);

        exit;
    }


    $stmt->bind_param(
        "i",
        $commentId
    );

    $success = $stmt->execute();


    // =====================================================
    // EXECUTE ERROR
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
        'success' => true
    ]);

    exit;
}


// =========================================================
// DELETE COMMENT
// =========================================================

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'delete_comment'
) {

    header('Content-Type: application/json');


    // =====================================================
    // INPUT
    // =====================================================

    $input = json_decode(
        file_get_contents('php://input'),
        true
    );

    $commentId =
        (int) ($input['comment_id'] ?? 0);


    // =====================================================
    // VALIDATION
    // =====================================================

    if ($commentId <= 0) {

        echo json_encode([
            'success' => false,
            'message' => 'Invalid comment ID.'
        ]);

        exit;
    }


    // =====================================================
    // UPDATE STATUS
    // =====================================================

    $stmt = $conn->prepare("
        UPDATE comments
        SET status = 'deleted'
        WHERE id = ?
    ");


    // =====================================================
    // SQL ERROR
    // =====================================================

    if (!$stmt) {

        echo json_encode([
            'success' => false,
            'message' => $conn->error
        ]);

        exit;
    }


    $stmt->bind_param(
        "i",
        $commentId
    );

    $success = $stmt->execute();


    // =====================================================
    // EXECUTE ERROR
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
        'success' => true
    ]);

    exit;
}