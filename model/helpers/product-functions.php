<?php
function loadProduct(mysqli $conn, int $id, string $categorySlug, string $productSlug)
{
    // Podrzava i stari ID link i novi /shop/kategorija/proizvod URL.
    if ($id > 0) {
        $sql = "
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
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.id = ?
              AND p.deleted_at IS NULL
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
    } elseif ($categorySlug !== '' && $productSlug !== '') {
        $sql = "
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
            INNER JOIN categories c ON c.id = p.category_id
            WHERE c.slug = ?
              AND p.slug = ?
              AND p.deleted_at IS NULL
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $categorySlug, $productSlug);
    } else {
        return false;
    }

    if (!$stmt) {
        return false;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    return $product ?: false;
}

function loadProductSizes(mysqli $conn, int $productId): array
{
    $sql = "
        SELECT
            size,
            stock
        FROM product_sizes
        WHERE product_id = ?
        ORDER BY size ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        return [];
    }

    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $sizes = [];
    while ($row = $result->fetch_assoc()) {
        $sizes[] = $row;
    }

    return $sizes;
}

function loadRecommendedProducts(mysqli $conn, int $productId)
{
    // Preporuke su iz iste kategorije, uz jednostavnu gender logiku.
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

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param(
        "iiiii",
        $productId,
        $productId,
        $productId,
        $productId,
        $productId
    );

    $stmt->execute();

    return $stmt->get_result();
}
