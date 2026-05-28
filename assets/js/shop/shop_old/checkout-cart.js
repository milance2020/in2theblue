// =========================
// INCREASE QTY
// =========================

document.querySelectorAll('.increase-qty-button')
.forEach(btn => {

    btn.addEventListener('click', async function () {

        const id = this.dataset.id;
        const size = this.dataset.size;

        const res = await fetch('index.php?page=cart&action=increase', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify({
                id,
                size
            })
        });

        const data = await res.json();

        const row = this.closest('.cart-row');

        const qtyEl = row.querySelector('.qty');

        const price =
            parseFloat(row.querySelector('.price').innerText);

        const newQty =
            parseInt(qtyEl.innerText) + 1;

        updateCartUI(row, newQty, price);

        updateTotal();

        const cartCount =
            document.querySelector('.cart-count');

        if (cartCount) {
            cartCount.innerText = data.count;
        }
    });
});


// =========================
// DECREASE QTY
// =========================

document.querySelectorAll('.decrease-qty-button')
.forEach(btn => {

    btn.addEventListener('click', async function () {

        const id = this.dataset.id;
        const size = this.dataset.size;

        const res = await fetch('index.php?page=cart&action=decrease', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify({
                id,
                size
            })
        });

        const data = await res.json();

        const row = this.closest('.cart-row');

        const qtyEl = row.querySelector('.qty');

        const price =
            parseFloat(row.querySelector('.price').innerText);

        let newQty =
            parseInt(qtyEl.innerText) - 1;

        const cartCount =
            document.querySelector('.cart-count');

        if (cartCount) {
            cartCount.innerText = data.count;
        }

        if (newQty <= 0) {

            row.remove();

            updateTotal();

            return;
        }

        updateCartUI(row, newQty, price);

        updateTotal();
    });
});


// =========================
// REMOVE ITEM
// =========================

document.querySelectorAll('.remove-item-btn')
.forEach(btn => {

    btn.addEventListener('click', async function () {

        const id = this.dataset.id;
        const size = this.dataset.size;

        const row = this.closest('.cart-row');

        const res = await fetch(
            'index.php?page=cart-checkout&action=remove',
            {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json'
                },

                body: JSON.stringify({
                    id,
                    size
                })
            }
        );

        const data = await res.json();

        row.remove();

        const cartCount =
            document.querySelector('.cart-count');

        if (cartCount) {
            cartCount.innerText = data.count;
        }

        updateTotal();
    });
});


// =========================
// UPDATE SINGLE ROW
// =========================

function updateCartUI(row, qty, price) {

    const qtyEl =
        row.querySelector('.qty');

    const subtotalEl =
        row.querySelector('.subtotal-amount');

    const subtotal = qty * price;

    qtyEl.innerText = qty;

    subtotalEl.innerText =
        subtotal.toFixed(2) + '€';
}


// =========================
// UPDATE TOTAL
// =========================

function updateTotal() {

    let total = 0;

    document.querySelectorAll('.cart-row')
    .forEach(row => {

        const qty =
            parseInt(row.querySelector('.qty').innerText);

        const price =
            parseFloat(row.querySelector('.price').innerText);

        total += qty * price;
    });

    const totalEl =
        document.querySelector('.total-amount');

    if (totalEl) {
        totalEl.innerText =
            total.toFixed(2) + '€';
    }

    const checkoutBtn =
        document.querySelector('.checkout-button');

    if (checkoutBtn) {

        checkoutBtn.disabled = total <= 0;
    }
}