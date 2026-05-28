<div class="admin-order-page">

    <!-- ORDER HEADER -->
    <div class="order-header-card">

        <div>
            <h1>Order #<?= $order->id ?></h1>
            <p>
                Created: <?= $order->created_at ?>
            </p>
        </div>

        <div class="order-status">
            <span class="status-badge">
                <?= $order->status ?>
            </span>
        </div>

    </div>

    <!-- ORDER + CUSTOMER INFO -->
    <div class="order-info-grid">

        <!-- ORDER INFO -->
        <div class="info-card">

            <h2>Order Information</h2>

            <div class="info-row">
                <span>Total</span>
                <strong><?= number_format($order->total_price, 2) ?> €</strong>
            </div>

            <div class="info-row">
                <span>Payment Method</span>
                <strong>Cash on Delivery</strong>
            </div>

            <form method="POST" action="index.php?page=adminPanel&action=update_order_status">

                <input type="hidden" name="order_id" value="<?= (int) $order->id ?>">

                <div class="info-row">
                    <span>Status</span>

                    <select name="status" required>
                        <option value="Pending" <?= $order->status === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="Processing" <?= $order->status === 'Processing' ? 'selected' : '' ?>>Processing
                        </option>
                        <option value="Shipped" <?= $order->status === 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                        <option value="Completed" <?= $order->status === 'Completed' ? 'selected' : '' ?>>Completed
                        </option>
                        <option value="Cancelled" <?= $order->status === 'Cancelled' ? 'selected' : '' ?>>Cancelled
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn">
                    Save status
                </button>

            </form>

        </div>

        <!-- CUSTOMER INFO -->
        <div class="info-card">

            <h2>Customer Information</h2>

            <div class="info-row">
                <span>Name</span>
                <strong><?= $order->full_name ?></strong>
            </div>

            <div class="info-row">
                <span>Email</span>
                <strong><?= $order->email ?></strong>
            </div>

            <div class="info-row">
                <span>Phone</span>
                <strong><?= $order->phone ?></strong>
            </div>

            <div class="info-row">
                <span>Address</span>
                <strong>
                    <?= $order->address ?>
                </strong>
            </div>

            <div class="info-row">
                <span>City</span>
                <strong>
                    <?= $order->city ?>
                </strong>
            </div>

        </div>

    </div>

    <!-- ORDER ITEMS -->
    <div class="order-items-card">

        <h2>Ordered Products</h2>

        <table class="order-table">

            <thead>

                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Size</th>
                    <th>Subtotal</th>
                </tr>

            </thead>

            <tbody>

                <?php foreach ($items as $item): ?>

                    <tr>

                        <td>
                            <img src="<?= $item['image_path'] ?>" class="order-product-image">
                        </td>

                        <td>
                            <?= $item['name'] ?>
                        </td>

                        <td>
                            <?= number_format($item['price'], 2) ?> €
                        </td>

                        <td>
                            <?= $item['quantity'] ?>
                        </td>
                        <td>
                            <?= $item['size'] ?>
                        </td>
                        <td>
                            <?= number_format(
                                $item['price'] * $item['quantity'],
                                2
                            ) ?> €
                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</div>