<h1>Pregled proizvoda</h1>

<table class="products-table">
    <tr>
        <th>id</th>
        <th>Sifra</th>
        <th>Naziv</th>
        <th>Kategorija</th>
        <th>Veličina</th>
        <th>Pol</th>
        <th>Opis</th>
        <th>Cijena</th>
        <th>Kolicina</th>
        <th>Slika</th>
        <th>Datum kreiranja</th>
        <th>Datum azuriranja</th>
    </tr>

    <?php if (!empty($products)): ?>

        <?php foreach ($products as $row): ?>
            <tr>
                <td><?= $row->id ?></td>
                <td><?= $row->sku ?></td>
                <td><?= $row->name ?></td>
                <td><?= $row->category ?></td>
                <td><?= $row->sizes ?></td>
                <td><?= $row->gender ?></td>
                <td><?= $row->description ?></td>
                <td><?= $row->price ?></td>
                <td>
                    <img src="<?= $row->image_path ?>" width="80">
                </td>
                <td><?= $row->created_at ?></td>
                <td><?= $row->updated_at ?></td>
                <td>
                    <a href="index.php?page=adminPanel&view=update&id=<?= $row->id ?>">Uredi</a>
                </td>
                <td>
                    <a href="index.php?page=adminPanel&action=delete&id=<?= $row->id ?>" class="delete-btn">Obriši</a>
                </td>
            </tr>
        <?php endforeach; ?>

    <?php else: ?>
        <tr>
            <td colspan="13">Nema proizvoda</td>
        </tr>
    <?php endif; ?>
</table>


<script src="<?= URL_ASSETS_JS ?>delete_btn.js">

</script>