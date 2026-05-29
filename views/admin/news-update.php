<?php
$id = (int) ($_GET['id'] ?? 0);
$title = $product->title ?? 'N/A';
$content = $product->content ?? 'N/A';
$category = $product->category ?? '';
?>

<form action="index.php?page=adminPanel&action=updateNews&id=<?= $id ?>" method="post" enctype="multipart/form-data" class="form">
    <?= csrf_input() ?>

    <label for="title">Naslov</label>
    <input type="text" name="title" id="title" value="<?= e($title) ?>" required>

    <label for="content">Sadrzaj</label>
    <textarea name="content" id="content" rows="6" required><?= e($content) ?></textarea>

    <label for="image">Slika</label>
    <input type="file" name="image" id="image" accept="image/*">

    <label for="category">Kategorija</label>
    <select name="category" id="category" required>
        <option value="">-- Odaberi --</option>
        <option value="bar" <?= $category === 'bar' ? 'selected' : '' ?>>Bar</option>
        <option value="rooms" <?= $category === 'rooms' ? 'selected' : '' ?>>Rooms</option>
        <option value="shop" <?= $category === 'shop' ? 'selected' : '' ?>>Shop</option>
    </select>

    <input type="submit" value="Spremi clanak">
</form>
