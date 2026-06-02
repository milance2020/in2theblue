<div class="admin-order-page">

    <div class="order-header-card">

        <div>
            <h1>Narudžba #<?= (int) $order->id ?></h1>
            <p>
                Kreirano: <?= e($order->created_at) ?>
            </p>
        </div>

        <div class="order-status">
            <span class="status-badge">
                <?= e($order->status) ?>
            </span>
        </div>

    </div>

    <div class="order-info-grid">

        <div class="info-card">

            <h2>Informacije o narudžbi</h2>

            <div class="info-row">
                <span>Iznos</span>
                <strong><?= number_format((float) $order->total_price, 2) ?> €</strong>
            </div>

            <div class="info-row">
                <span>Način plaćanja</span>
                <strong>Plaćanje pri preuzimanju</strong>
            </div>

            <form method="POST" action="index.php?page=adminPanel&action=update_order_status">
                <?= csrf_input() ?>

                <input type="hidden" name="order_id" value="<?= (int) $order->id ?>">

                <div class="info-row">
                    <span>Stanje</span>

                    <select name="status" required>
                        <option value="Pending" <?= $order->status === 'Pending' ? 'selected' : '' ?>>Na čekanju</option>
                        <option value="Processing" <?= $order->status === 'Processing' ? 'selected' : '' ?>>U obradi</option>
                        <option value="Shipped" <?= $order->status === 'Shipped' ? 'selected' : '' ?>>Poslana</option>
                        <option value="Completed" <?= $order->status === 'Completed' ? 'selected' : '' ?>>Gotova</option>
                        <option value="Cancelled" <?= $order->status === 'Cancelled' ? 'selected' : '' ?>>Otkazana</option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    Spremi stanje
                </button>

            </form>

        </div>

        <div class="info-card">

            <h2>Informacije o kupcu</h2>

            <div class="info-row">
                <span>Ime</span>
                <strong><?= e($order->full_name) ?></strong>
            </div>

            <div class="info-row">
                <span>Email</span>
                <strong><?= e($order->email) ?></strong>
            </div>

            <div class="info-row">
                <span>Telefon</span>
                <strong><?= e($order->phone) ?></strong>
            </div>

            <div class="info-row">
                <span>Adresa</span>
                <strong><?= e($order->address) ?></strong>
            </div>

            <div class="info-row">
                <span>Grad</span>
                <strong><?= e($order->city) ?></strong>
            </div>

            <div class="info-row">
                <span>Poštanski broj</span>
                <strong><?= e($order->zip) ?></strong>
            </div>

            <div class="info-row">
                <span>Država</span>
                <strong><?= e($order->country) ?></strong>
            </div>

        </div>

    </div>

    <div class="order-items-card">

        <h2>Naručeni proizvodi</h2>

        <table class="order-table">

            <thead>
                <tr>
                    <th>Slika</th>
                    <th>Proizvod</th>
                    <th>Cijena</th>
                    <th>Količina</th>
                    <th>Veličina</th>
                    <th>Ukupno</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <img
                                src="<?= e(storedFileUrl($item['image_path'])) ?>"
                                alt="<?= e($item['name']) ?>"
                                class="order-product-image"
                            >
                        </td>

                        <td><?= e($item['name']) ?></td>

                        <td><?= number_format((float) $item['price'], 2) ?> €</td>

                        <td><?= (int) $item['quantity'] ?></td>

                        <td><?= e($item['size']) ?></td>

                        <td>
                            <?= number_format((float) $item['price'] * (int) $item['quantity'], 2) ?> €
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

</div>
