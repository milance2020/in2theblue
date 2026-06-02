<?php

$_output['view'] = 'shop/explore';
$_output['html_model'] = 'explore';
$_output['breadcrumbs_enabled'] = true;

include FILE_CONNECT;

/*********SHOP SIDEBAR**********/ 


// =========================
// GET FILTERS
// =========================

$category = $_GET['category'] ?? '';
$gender = $_GET['gender'] ?? '';
$search = trim($_GET['search'] ?? '');

// =========================
// LOAD CATEGORIES
// =========================

$stmtCategories = $conn->prepare("
    SELECT
        id,
        name,
        label,
        slug
    FROM categories
    ORDER BY label ASC
");

$stmtCategories->execute();

$categories = $stmtCategories
    ->get_result()
    ->fetch_all(MYSQLI_ASSOC);

// =========================
// BASE QUERY
// =========================
$sql = "
    SELECT
        p.id,
        p.name,
        p.slug,
        p.price,
        p.image_path,
        p.gender,
        p.description,

        c.label AS category_label,
        c.slug AS category_slug

    FROM products2 p
    JOIN categories c ON c.id = p.category_id
";

$where = [];
$params = [];
$types = '';

// =========================
// ALWAYS: soft delete filter
// =========================
$where[] = "p.deleted_at IS NULL";

// =========================
// CATEGORY FILTER
// =========================
if ($category !== '') {
    $where[] = "c.slug = ?";
    $params[] = $category;
    $types .= 's';
}

// =========================
// GENDER FILTER
// =========================
if ($gender !== '') {
    $where[] = "p.gender = ?";
    $params[] = $gender;
    $types .= 's';
}

// =========================
// SEARCH FILTER
// =========================
if ($search !== '') {

    $where[] = "(p.name LIKE ? OR p.description LIKE ?)";

    $searchTerm = "%{$search}%";

    $params[] = $searchTerm;
    $params[] = $searchTerm;

    $types .= 'ss';
}

// =========================
// BUILD WHERE (ONLY ONCE)
// =========================
$sql .= " WHERE " . implode(" AND ", $where);

// =========================
// ORDER
// =========================
$sql .= " ORDER BY p.id DESC";

// =========================
// PREPARE
// =========================
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL ERROR: " . $conn->error . "<br><br>" . $sql);
}

// =========================
// BIND
// =========================
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// =========================
// EXECUTE
// =========================
$stmt->execute();

$result = $stmt->get_result();

$categoryLabel = '';
if ($category !== '') {
    foreach ($categories as $cat) {
        if ($cat['slug'] === $category) {
            $categoryLabel = $cat['label'];
            break;
        }
    }
}

require_once FILE_SEO_HELPER;

$canonicalParams = array_filter([
    'category' => $category ?: null,
    'gender'   => $gender ?: null,
    'search'   => $search ?: null,
]);

setSEO('explore', [
    'search'          => $search,
    'category'        => $category,
    'category_label'  => $categoryLabel,
    'gender'          => $gender,
    'url'             => shopUrl($canonicalParams),
]);

?>
