<?php



//USER REPORTED COMMENT ALREADY?

function hasUserReportedComment(
    $conn,
    $commentId,
    $userId
){
    $stmt = $conn->prepare("
        SELECT id
        FROM comment_reports
        WHERE comment_id = ?
        AND user_id = ?
        LIMIT 1");

    $stmt->bind_param(
        "ii",
        $commentId,
        $userId
    );
    $stmt->execute();
    
    $result = $stmt->get_result();
    
    $report = $result->fetch_assoc();

    $stmt->close();

    return !!$report;
}

//ADD COMMENT REPORT

function addCommentReport(
    $conn,
    $commentId,
    $userId
){
    $stmt = $conn->prepare("
        INSERT INTO comment_reports (
            comment_id,
            user_id
        )
        VALUES (?, ?)
    ");

    $stmt->bind_param(
        "ii",
        $commentId,
        $userId
    );

    $success =
        $stmt->execute();

    $stmt->close();

    return $success;


}


//GET COMMENT REPORT COUNT


function getCommentReportCount(
    $conn,
    $commentId
) {

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS report_count
        FROM comment_reports
        WHERE comment_id = ?
    ");

    $stmt->bind_param(
        "i",
        $commentId
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    $row =
        $result->fetch_assoc();

    $stmt->close();

    return (int) $row['report_count'];
}

//UPDATE STATUS

function updateCommentStatus(
    $conn,
    $commentId,
    $status
) {

    $stmt = $conn->prepare("
        UPDATE comments
        SET status = ?
        WHERE id = ?
    ");

    $stmt->bind_param(
        "si",
        $status,
        $commentId
    );

    $success =
        $stmt->execute();

    $stmt->close();

    return $success;
}


//REPORT MODERATION RULES


function moderateReportedComment(
    $conn,
    $commentId
) {

    $reportCount =
        getCommentReportCount(
            $conn,
            $commentId
        );


    // =====================================================
    // AUTO HIDDEN
    // =====================================================

    if ($reportCount >= 10) {

        updateCommentStatus(
            $conn,
            $commentId,
            COMMENT_HIDDEN
        );

        return COMMENT_HIDDEN;
    }


    // =====================================================
    // AUTO PENDING
    // =====================================================

    if ($reportCount >= 5) {

        updateCommentStatus(
            $conn,
            $commentId,
            COMMENT_PENDING
        );

        return COMMENT_PENDING;
    }


    // =====================================================
    // NO CHANGE
    // =====================================================

    return false;
}

