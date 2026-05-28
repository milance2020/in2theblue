// =========================
// CART API
// =========================

async function cartRequest(action, payload = {}) {

    const res = await fetch(`index.php?page=cart&action=${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return await res.json();
}

// =========================
// UPDATE CART COUNT
// =========================

function updateCartCount(count) {

    const cartCount = document.querySelector('.cart-count');

    if (cartCount) {
        cartCount.innerText = count;
    }
}

// =========================
// LOAD CART
// =========================

async function loadCart() {

    const res = await fetch('index.php?page=cart&action=get');

    const data = await res.json();

    updateCartCount(data.count);

    const container = document.querySelector('.cart-items');

    if (!container) return;

    container.innerHTML = '';

    let total = 0;
    console.log(data);
    data.items.forEach(item => {

        const subtotal = item.price * item.qty;

        total += subtotal;

        container.innerHTML += `
            <div class="cart-item">

                <div>
                    <strong>${item.name}</strong>
                    <p>
                        ${item.size} |
                        ${item.qty} x ${item.price}€
                    </p>
                </div>

            </div>
        `;
    });

    container.innerHTML += `
        <div class="cart-total">
            <strong>Total: ${total.toFixed(2)}€</strong>
        </div>
    `;
}

// =========================
// RELOAD CHECKOUT PAGE
// =========================

function refreshCheckoutPage() {

    if (window.location.href.includes('page=cart-checkout')) {
        window.location.reload();
    }
}

// =========================
// ADD TO CART
// =========================

document.querySelectorAll('.add-to-cart-button')
    .forEach(btn => {

        btn.addEventListener('click', async function () {

            const id = this.dataset.id;
            const size = this.dataset.size;

            if (!size) {
                alert('Izaberi veličinu');
                return;
            }

            let qty = 1;
            

            const qtyEl = document.querySelector('.qty');
            if (qtyEl) {
                qty = parseInt(qtyEl.innerText);
            }

            const sizeEl = document.querySelector('.size-select');
            if (sizeEl) {
                size = sizeEl.value;
            }

            const data = await cartRequest('add', {
                id,
                qty,
                size
            });

            updateCartCount(data.count);

            loadCart();

            refreshCheckoutPage();
        });
    });

// =========================
// INCREASE
// =========================

document.querySelectorAll('.increase-qty-button')
    .forEach(btn => {

        btn.addEventListener('click', async function () {

            const id = this.dataset.id;
            const size = this.dataset.size;

            const data = await cartRequest('increase', {
                id,
                size
            });

            updateCartCount(data.count);

            loadCart();

            refreshCheckoutPage();
        });
    });

// =========================
// DECREASE
// =========================

document.querySelectorAll('.decrease-qty-button')
    .forEach(btn => {

        btn.addEventListener('click', async function () {

            const id = this.dataset.id;
            const size = this.dataset.size;

            const data = await cartRequest('decrease', {
                id,
                size
            });

            updateCartCount(data.count);

            loadCart();

            refreshCheckoutPage();
        });
    });
// =========================
// SIZE SELECT
// =========================
document.querySelectorAll('.product-card').forEach(card => {

    let selectedSize = null;

    card.querySelectorAll('.size-btn').forEach(btn => {

        btn.addEventListener('click', () => {

            selectedSize = btn.dataset.size;

            card.querySelector('.add-to-cart-button')
                .dataset.size = selectedSize;

            card.querySelectorAll('.size-btn')
                .forEach(b => b.classList.remove('active'));

            btn.classList.add('active');
        });

    });

});

// =========================
// INIT
// =========================

loadCart();