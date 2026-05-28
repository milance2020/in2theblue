<div class="checkout-layout">

    <!-- LEFT -->
    <div class="checkout-form">

        

        <form method="POST" >
            <h2>Podaci o naplati</h2>
            <label for="full-name">Puno Ime</label>
            <input type="text" name="full_name" id="full-name" placeholder="Ime i prezime">

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Email">

            <label for="phone">Telefon</label>
            <input type="text" name="phone" id="phone" placeholder="Telefon">

            <label for="address">Adresa</label>
            <input type="text" name="address" id="address" placeholder="Adresa">
            <label for="city">Grad</label>
            <input type="text" name="city" id="city" placeholder="Grad">
            <label for="zip-code">Zip Code</label>
            <input type="text" name="zip_code" id="zip-code" placeholder="Zip Code">
           
            <label for="country">Država</label>
            <input type="text" name="country" id="country" placeholder="Država">
           

            <button type="submit">
                Naruči
            </button>

        </form>

    </div>

    <!-- RIGHT -->
    <div class="order-summary">

        <h2>Narudžba</h2>

        <?php foreach ($items as $item): ?>

            <div class="summary-row">

                <span>
                    <?= $item['name'] ?>
                    x <?= $item['qty'] ?> 
                     <?= $item['size'] ?>
                </span>

                <span>
                    <?= $item['subtotal'] ?>€
                </span>

            </div>

        <?php endforeach; ?>

        <hr>

        <div class="summary-total">
            <strong>Total: <?= $total ?>€</strong>
        </div>

    </div>

</div>