<table class="products-table">
    <tr>
        <th>id</th>
        <th>Naslov</th>
        <th>Sadržaj</th>
        <th>Slika</th>
        <th>Datum kreiranja</th>
        <th>Datum azuriranja</th>
    </tr>

    <?php if (!empty($news)): ?>

    <?php foreach ($news as $row): ?>
        <tr>
            <td><?= $row->id ?></td>
            <td><?= $row->title ?></td>
            <td><?= $row->content ?></td>
            <td>
                <img src="<?= $row->image ?>" width="80">
            </td>
            <td><?= $row->created_at ?></td>
            <td><?= $row->updated_at ?></td>
            <td>
                <a href="index.php?page=adminPanel&view=updateNews&id=<?= $row->id ?>">Uredi</a>
            </td>
            <td>
                <a href="index.php?page=adminPanel&action=deleteNews&id=<?= $row->id ?>" class="delete-btn">Obriši</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="8">Nema vijesti</td>
    </tr>
<?php endif; ?>
</table>    