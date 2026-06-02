<div class="checkout-layout">

    <div class="checkout-form">

        <form method="POST">
            <?= csrf_input() ?>

            <h2>Podaci o naplati</h2>

            <?php if (!empty($errors)): ?>
                <div class="checkout-errors">
                    <?php foreach ($errors as $error): ?>
                        <p><?= e($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <label for="full-name">Ime i prezime</label>
            <input
                type="text"
                name="full_name"
                id="full-name"
                placeholder="Ime i prezime"
                value="<?= e($formData['full_name'] ?? '') ?>"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Email"
                value="<?= e($formData['email'] ?? '') ?>"
                required
            >

            <label for="phone">Telefon</label>
            <input
                type="text"
                name="phone"
                id="phone"
                placeholder="Telefon"
                value="<?= e($formData['phone'] ?? '') ?>"
                required
            >

            <label for="address">Adresa</label>
            <input
                type="text"
                name="address"
                id="address"
                placeholder="Adresa"
                value="<?= e($formData['address'] ?? '') ?>"
                required
            >

            <label for="city">Grad</label>
            <input
                type="text"
                name="city"
                id="city"
                placeholder="Grad"
                value="<?= e($formData['city'] ?? '') ?>"
                required
            >

            <label for="zip-code">Poštanski broj</label>
            <input
                type="text"
                name="zip_code"
                id="zip-code"
                placeholder="Poštanski broj"
                value="<?= e($formData['zip_code'] ?? '') ?>"
            >

            <label for="country">Država</label>
            <input
                type="text"
                name="country"
                id="country"
                placeholder="Država"
                value="<?= e($formData['country'] ?? '') ?>"
                required
            >

            <button type="submit" <?= empty($items) ? 'disabled' : '' ?>>
                Naruči
            </button>

        </form>

    </div>

    <div class="order-summary">

        <h2>Narudžba</h2>

        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>

                <div class="summary-row">

                    <span>
                        <?= e($item['name']) ?>
                        x <?= (int) $item['qty'] ?>
                        <?= e($item['size']) ?>
                    </span>

                    <span>
                        <?= number_format((float) $item['subtotal'], 2) ?> €
                    </span>

                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-order">
                Korpa je prazna.
            </p>
        <?php endif; ?>

        <hr>

        <div class="summary-total">
            <strong>Total: <?= number_format((float) $total, 2) ?> €</strong>
        </div>

    </div>

</div>
