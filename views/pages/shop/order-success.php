<div class="order-success">

    <div class="success-icon">
        ✓
    </div>

    <h1>Narudžba je uspješna</h1>

    <p>
        Hvala na narudžbi. Vaša narudžba je uspješno zaprimljena.
    </p>

    <p class="order-id">
        ID narudžbe: #<?= (int) $orderId ?>
    </p>

    <p class="order-total">
        Ukupno: <?= number_format((float) $order['total_price'], 2) ?> €
    </p>

    <a href="<?= shopUrl() ?>" class="continue-shopping">
        Nastavi sa kupovinom
    </a>

</div>
