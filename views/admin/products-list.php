<h1>Pregled proizvoda</h1>

<?php $isAdmin = ($_SESSION['role'] ?? '') === 'admin'; ?>

<table class="products-table">
    <tr>
        <th>id</th>
        <th>Šifra</th>
        <th>Naziv</th>
        <th>Kategorija</th>
        <th>Veličina</th>
        <th>Pol</th>
        <th>Opis</th>
        <th>Cijena</th>
        <th>Slika</th>
        <th>Datum kreiranja</th>
        <th>Datum ažuriranja</th>
        <?php if ($isAdmin): ?>
            <th>Uredi</th>
            <th>Obriši</th>
        <?php endif; ?>
    </tr>

    <?php if (!empty($products)): ?>
        <?php foreach ($products as $row): ?>
            <tr>
                <td><?= (int) $row->id ?></td>
                <td><?= e($row->sku) ?></td>
                <td><?= e($row->name) ?></td>
                <td><?= e($row->category) ?></td>
                <td><?= e($row->sizes) ?></td>
                <td><?= e($row->gender) ?></td>
                <td><?= e($row->description) ?></td>
                <td><?= e($row->price) ?></td>
                <td>
                    <img src="<?= e($row->image_path) ?>" width="80" alt="">
                </td>
                <td><?= e($row->created_at) ?></td>
                <td><?= e($row->updated_at) ?></td>
                <?php if ($isAdmin): ?>
                    <td>
                        <a href="index.php?page=adminPanel&view=update&id=<?= (int) $row->id ?>">Uredi</a>
                    </td>
                    <td>
                        <a href="index.php?page=adminPanel&action=delete&id=<?= (int) $row->id ?>&<?= csrf_url() ?>" class="delete-btn">Obriši</a>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="<?= $isAdmin ? 13 : 11 ?>">Nema proizvoda</td>
        </tr>
    <?php endif; ?>

</table>

<script src="<?= URL_ASSETS_JS ?>delete_btn.js"></script>
