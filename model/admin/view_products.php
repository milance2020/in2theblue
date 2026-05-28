<?php

if (!defined('DIR_ROOT')) {
    require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'constants.php';
}
include FILE_CONNECT;

$upit = "
SELECT 
    p.id,
    p.sku,
    p.name,
    p.gender,
    p.description,
    p.price,
    p.image_path,
    p.created_at,
    p.updated_at,

    c.label AS category,

    GROUP_CONCAT(
        CONCAT(ps.size, ':', ps.stock)
        SEPARATOR ' | '
    ) AS sizes

FROM products2 p

JOIN categories c 
    ON c.id = p.category_id

LEFT JOIN product_sizes ps 
    ON ps.product_id = p.id

WHERE p.deleted_at IS NULL

GROUP BY p.id

ORDER BY p.created_at DESC
";

$q = mysqli_query($conn, $upit);

$products = [];

if ($q && $q->num_rows > 0) {

    while ($row = $q->fetch_object()) {

        // =========================
        // GENDER NORMALIZATION
        // =========================
        if ($row->gender === 'male') {
            $row->gender = 'Muško';
        } elseif ($row->gender === 'female') {
            $row->gender = 'Žensko';
        } else {
            $row->gender = ucfirst($row->gender);
        }

        $products[] = $row;
    }
}
?>