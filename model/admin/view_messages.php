<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}

include FILE_CONNECT;

$status = $_GET['status'] ?? '';

$allowedStatuses = [
    'Unread',
    'Read',
    'Archived'
];

$messages = [];

$baseQuery = "
    SELECT *
    FROM contact_messages
";

$orderBy = "
    ORDER BY
        CASE
            WHEN status = 'Unread' THEN 1
            WHEN status = 'Read' THEN 2
            WHEN status = 'Archived' THEN 3
            ELSE 4
        END,
        created_at DESC
";

if (in_array($status, $allowedStatuses, true)) {

    $stmt = $conn->prepare($baseQuery . "
        WHERE status = ?
        " . $orderBy
    );

    $stmt->bind_param("s", $status);

} else {
    $stmt = $conn->prepare($baseQuery . $orderBy);
}

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_object()) {
    $messages[] = $row;
}