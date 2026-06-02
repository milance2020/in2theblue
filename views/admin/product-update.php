<?php

$sku = $product->sku ?? '';
$name = $product->name ?? '';
$description = $product->description ?? '';
$price = $product->price ?? 0;
$sizes = $productSizes ?? [];

?>

<form action="index.php?page=adminPanel&action=update"
      method="post"
      enctype="multipart/form-data"
      class="form">
    <?= csrf_input() ?>

    <input type="hidden" name="id" value="<?= e($product->id ?? 0) ?>">

    <label>Šifra proizvoda</label>
    <input type="text"
           name="code"
           value="<?= e($sku) ?>"
           readonly>

    <label>Naziv proizvoda</label>
    <input type="text"
           name="name"
           value="<?= e($name) ?>"
           required>

    <label>Opis proizvoda</label>
    <textarea name="description"
              rows="5"><?= e($description) ?></textarea>

    <label>Cijena</label>
    <input type="number"
           step="0.01"
           name="price"
           value="<?= e($price) ?>">

    <h3>Stock po veličinama</h3>

    <div class="size-grid">
        <?php foreach ($sizes as $s): ?>
            <div class="size-row">
                <label><?= e($s['size']) ?></label>

                <input type="number"
                       name="stock[<?= e($s['size']) ?>]"
                       value="<?= (int) $s['stock'] ?>"
                       min="0">
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit">
        Sačuvaj promjene
    </button>
</form>
