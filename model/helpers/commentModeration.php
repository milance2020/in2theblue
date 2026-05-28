<?php

// =========================================================
// NEW USER CHECK
// =========================================================

function isUserNew($conn, $userId)
{
    $stmt = $conn->prepare("
        SELECT created_at
        FROM users
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    $user =
        $result->fetch_assoc();

    $stmt->close();

    if (!$user) {
        return false;
    }

    $createdAt = strtotime(
        $user['created_at']
    );

    $hoursSinceRegistration =
        (time() - $createdAt) / 3600;

    return
        $hoursSinceRegistration < 24;
}



// =========================================================
// URL DETECTION
// =========================================================

function containsUrl($text)
{
    return preg_match(
        '/https?:\/\/|www\./i',
        $text
    );
}



// =========================================================
// BAD WORD DETECTION
// =========================================================

function containsBadWords($text)
{
    $badWords = [
        'idiot',
        'debil',
        'retard',
        'jebe',
        'jebem',
        'fuck'
    ];

    $text = strtolower($text);

    foreach ($badWords as $word) {

        if (
            str_contains(
                $text,
                $word
            )
        ) {

            return true;
        }
    }

    return false;
}



// =========================================================
// COMMENT FLOOD CHECK
// =========================================================

function isCommentFlooding(
    $conn,
    $userId
) {

    $stmt = $conn->prepare("
        SELECT COUNT(*) AS comment_count
        FROM comments
        WHERE user_id = ?
        AND created_at >=
            NOW() - INTERVAL 1 MINUTE
    ");

    $stmt->bind_param(
        "i",
        $userId
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    $row =
        $result->fetch_assoc();

    $stmt->close();

    return
        $row['comment_count'] >= 5;
}



// =========================================================
// TEXT MODERATION
// =========================================================

function moderateTextContent($comment)
{
    $status = COMMENT_VISIBLE;

    $reasons = [];


    // =====================================================
    // BAD WORDS
    // =====================================================

    if (
        containsBadWords($comment)
    ) {

        $status = COMMENT_HIDDEN;

        $reasons[] = 'bad_words';
    }


    // =====================================================
    // URL SPAM
    // =====================================================

    if (
        containsUrl($comment)
        && $status !== COMMENT_HIDDEN
    ) {

        $status = COMMENT_HIDDEN;

        $reasons[] = 'url_spam';
    }


    return [
        'status' => $status,
        'reasons' => $reasons
    ];
}



// =========================================================
// USER BEHAVIOR MODERATION
// =========================================================

function moderateUserBehavior(
    $conn,
    $userId
) {

    $status = COMMENT_VISIBLE;

    $reasons = [];


    // =====================================================
    // NEW USER
    // =====================================================

    if (
        isUserNew(
            $conn,
            $userId
        )
    ) {

        $status = COMMENT_PENDING;

        $reasons[] = 'new_user';
    }


    // =====================================================
    // FLOODING
    // =====================================================

    if (
        isCommentFlooding(
            $conn,
            $userId
        )
    ) {

        $status = COMMENT_PENDING;

        $reasons[] = 'flooding';
    }


    return [
        'status' => $status,
        'reasons' => $reasons
    ];
}



// =========================================================
// MAIN MODERATION ENGINE
// =========================================================

function moderateComment(
    $conn,
    $userId,
    $comment
) {

    $status = COMMENT_VISIBLE;

    $reasons = [];


    // =====================================================
    // TEXT MODERATION
    // =====================================================

    $textModeration =
        moderateTextContent($comment);

    if (
        $textModeration['status']
        === COMMENT_HIDDEN
    ) {

        $status = COMMENT_HIDDEN;
    }

    $reasons = array_merge(
        $reasons,
        $textModeration['reasons']
    );


    // =====================================================
    // USER MODERATION
    // =====================================================

    if (
        $status !== COMMENT_HIDDEN
    ) {

        $behaviorModeration =
            moderateUserBehavior(
                $conn,
                $userId
            );

        if (
            $behaviorModeration['status']
            === COMMENT_PENDING
        ) {

            $status = COMMENT_PENDING;
        }

        $reasons = array_merge(
            $reasons,
            $behaviorModeration['reasons']
        );
    }


    // =====================================================
    // FINAL RESULT
    // =====================================================

    return [
        'status' => $status,
        'reasons' => $reasons
    ];
}