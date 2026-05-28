<?php

$sku = $product->sku ?? '';
$name = $product->name ?? '';
$description = $product->description ?? '';
$price = $product->price ?? 0;


$sizes = $productSizes ?? []; 
// format: [
//   ['size' => 'M', 'stock' => 5],
//   ['size' => 'L', 'stock' => 2]
// ]

?>

<form action="index.php?page=adminPanel&action=update"
      method="post"
      enctype="multipart/form-data"
      class="form">

    <!-- =========================
         BASIC PRODUCT DATA
         ========================= -->
  <input type="hidden" name="id" value="<?= htmlspecialchars($product->id ?? 0) ?>">
    <label>Šifra proizvoda</label>
    <input type="text"
           name="code"
           value="<?= htmlspecialchars($sku) ?>"
           readonly>

    <label>Naziv proizvoda</label>
    <input type="text"
           name="name"
           value="<?= htmlspecialchars($name) ?>"
           required>

    <label>Opis proizvoda</label>
    <textarea name="description"
              rows="5"><?= htmlspecialchars($description) ?></textarea>

    <label>Cijena</label>
    <input type="number"
           step="0.01"
           name="price"
           value="<?= htmlspecialchars($price) ?>">

    <!-- =========================
         SIZE / STOCK MATRIX
         ========================= -->
    <h3>Stock po veličinama</h3>

    <div class="size-grid">

        <?php foreach ($sizes as $s): ?>

            <div class="size-row">

                <label><?= htmlspecialchars($s['size']) ?></label>

                <input type="number"
                       name="stock[<?= htmlspecialchars($s['size']) ?>]"
                       value="<?= (int)$s['stock'] ?>"
                       min="0">

            </div>

        <?php endforeach; ?>

    </div>

    <!-- =========================
         SUBMIT
         ========================= -->

    <button type="submit">
        Sačuvaj promjene
    </button>

</form>