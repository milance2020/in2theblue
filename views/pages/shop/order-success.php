<div class="order-success">

    <div class="success-icon">
        ✓
    </div>

    <h1>Order Successful</h1>

    <p>
        Hvala na narudžbi. Vaša narudžba je uspješno zaprimljena.
    </p>

    <p class="order-id">
        Order ID: #<?= $orderId ?? '' ?>
    </p>

    <a href="<?= appUrl('shop') ?>" class="continue-shopping">
        Continue Shopping
    </a>

</div>