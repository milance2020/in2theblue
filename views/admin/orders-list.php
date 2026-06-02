<h1>Narudžbe</h1>
<div class="order-filters">

    <a 
        class="<?= $status === '' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders"
    >
        Sve
    </a>

    <a 
        class="<?= $status === 'Pending' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Pending"
    >
        Na čekanju
    </a>

    <a 
        class="<?= $status === 'Processing' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Processing"
    >
        U obradi
    </a>

    <a 
        class="<?= $status === 'Shipped' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Shipped"
    >
        Poslana
    </a>

    <a 
        class="<?= $status === 'Completed' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Completed"
    >
        Gotova
    </a>

    <a 
        class="<?= $status === 'Cancelled' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Cancelled"
    >
        Otkazana
    </a>

</div>

<table class="products-table">
    <tr>
        <th>ID</th>
        <th>Kupac</th>
        <th>Iznos</th>
        <th>Stanje</th>
        <th>Vrijeme narudžbe</th>
    </tr>
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td><?= $row->id?></td>
                <td><?= $row->full_name?></td>
                <td><?= $row->total_price?></td>
                <td><?= $row->status?></td>
                <td><?= $row->created_at?></td>
                <td><a href="index.php?page=adminPanel&view=order_info&id=<?=(int)$row->id?>">Pregled</a></td>
                

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
            <tr>
        <td colspan="5">Nema narudzbi</td>
    </tr>
       <?php endif;?> 

</table>
