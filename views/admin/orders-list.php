<h1>Narudzbe</h1>
<div class="order-filters">

    <a 
        class="<?= $status === '' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders"
    >
        All
    </a>

    <a 
        class="<?= $status === 'Pending' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Pending"
    >
        Pending
    </a>

    <a 
        class="<?= $status === 'Processing' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Processing"
    >
        Processing
    </a>

    <a 
        class="<?= $status === 'Shipped' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Shipped"
    >
        Shipped
    </a>

    <a 
        class="<?= $status === 'Completed' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Completed"
    >
        Completed
    </a>

    <a 
        class="<?= $status === 'Cancelled' ? 'active-filter' : '' ?>"
        href="index.php?page=adminPanel&view=orders&status=Cancelled"
    >
        Cancelled
    </a>

</div>

<table class="products-table">
    <tr>
        <th>ID</th>
        <th>Musterija</th>
        <th>Total</th>
        <th>Status</th>
        <th>Vrijeme Narudzbe</th>
    </tr>
    <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $row): ?>
            <tr>
                <td><?= $row->id?></td>
                <td><?= $row->full_name?></td>
                <td><?= $row->total_price?></td>
                <td><?= $row->status?></td>
                <td><?= $row->created_at?></td>
                <td><a href="index.php?page=adminPanel&view=order_info&id=<?=(int)$row->id?>">Pregledaj</a></td>
                

            </tr>
        <?php endforeach; ?>
    <?php else: ?>
            <tr>
        <td colspan="5">Nema narudzbi</td>
    </tr>
       <?php endif;?> 

</table>