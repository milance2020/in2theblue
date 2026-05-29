<?php
$page = $_GET['page'] ?? 'index';
?>

<?php if (
    !empty($_output['breadcrumbs_enabled'])
    && is_array($_output['breadcrumbs'])
    && count($_output['breadcrumbs'])
): ?>
    <nav class="breadcrumbs" aria-label="Breadcrumb">
        <?php foreach ($_output['breadcrumbs'] as $i => $b): ?>
            <?php if ($i > 0): ?>
                <span class="breadcrumb-sep" aria-hidden="true">/</span>
            <?php endif; ?>

            <?php if (!empty($b['url'])): ?>
                <a href="<?= htmlspecialchars($b['url']) ?>">
                    <?= htmlspecialchars($b['label']) ?>
                </a>
            <?php else: ?>
                <span class="breadcrumb-current" aria-current="page">
                    <?= htmlspecialchars($b['label']) ?>
                </span>
            <?php endif; ?>
        <?php endforeach; ?>
    </nav>
<?php endif; ?>

<model-<?= $_output['html_model'] ?>>

    <?php if (empty($_output['layout'])): ?>
        <?= flash_render() ?>
    <?php endif; ?>

    <?php
if (!empty($_output['layout'])) {
    $__viewFile = view_path('layouts/' . $_output['layout']);
} elseif (!empty($_output['view'])) {
    $__viewFile = view_path($_output['view']);
} else {
    $__viewFile = '';
}

if ($__viewFile !== '' && is_file($__viewFile)) {
    include $__viewFile;
} elseif ($__viewFile !== '') {
    http_response_code(500);
    echo 'View not found: ' . htmlspecialchars($_output['view'] ?? $_output['layout']);
}
?>

</model-<?= $_output['html_model'] ?>>
