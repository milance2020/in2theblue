<table class="products-table">
    <tr>
        <th>id</th>
        <th>Naslov</th>
        <th>Sadrzaj</th>
        <th>Slika</th>
        <th>Datum kreiranja</th>
        <th>Datum azuriranja</th>
        <th>Uredi</th>
        <th>Obrisi</th>
    </tr>

    <?php if (!empty($news)): ?>
        <?php foreach ($news as $row): ?>
            <tr>
                <td><?= (int) $row->id ?></td>
                <td><?= e($row->title) ?></td>
                <td><?= e($row->content) ?></td>
                <td>
                    <img src="<?= e($row->image) ?>" width="80" alt="">
                </td>
                <td><?= e($row->created_at) ?></td>
                <td><?= e($row->updated_at) ?></td>
                <td>
                    <a href="index.php?page=adminPanel&view=updateNews&id=<?= (int) $row->id ?>">Uredi</a>
                </td>
                <td>
                    <a href="index.php?page=adminPanel&action=deleteNews&id=<?= (int) $row->id ?>&<?= csrf_url() ?>" class="delete-btn">Obrisi</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="8">Nema vijesti</td>
        </tr>
    <?php endif; ?>
</table>
