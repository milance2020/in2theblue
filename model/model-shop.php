<?php
$_output['view'] = 'site/shop';
$_output['html_model'] = 'shop';
$_output['breadcrumbs_enabled'] = true;

require_once FILE_SEO_HELPER;
setSEO('shop');
require_once FILE_CONNECT;
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

    ORDER BY p.created_at DESC

    LIMIT 4
");

$stmt->execute();

$featuredProducts = $stmt->get_result();
?>