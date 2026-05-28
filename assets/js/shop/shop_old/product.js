// assets/js/shop/product.js

// =========================================================
// ELEMENTS
// =========================================================

const addToCartBtn = document.querySelector('.add-to-cart-button');

const qtyEl = document.querySelector('.qty');

const sizeSelect = document.querySelector('.size-select');

const increaseBtn = document.querySelector('.increase-qty-button');

const decreaseBtn = document.querySelector('.decrease-qty-button');

// =========================================================
// PRODUCT ID
// =========================================================

const productId = addToCartBtn
    ? addToCartBtn.dataset.id
    : null;

// =========================================================
// QUANTITY CONTROLS
// =========================================================

if (increaseBtn) {

    increaseBtn.addEventListener('click', () => {

        let qty = parseInt(qtyEl.innerText);

        qty++;

        qtyEl.innerText = qty;
    });
}

if (decreaseBtn) {

    decreaseBtn.addEventListener('click', () => {

        let qty = parseInt(qtyEl.innerText);

        if (qty > 1) {

            qty--;

            qtyEl.innerText = qty;
        }
    });
}

// =========================================================
// ADD TO CART
// =========================================================

if (addToCartBtn) {

    addToCartBtn.addEventListener('click', async function () {

        try {

            const id = this.dataset.id;

            const qty =
                parseInt(qtyEl.innerText);

            const size =
                sizeSelect
                    ? sizeSelect.value
                    : null;

            // =========================
            // VALIDATION
            // =========================

            if (!size) {

                alert('Odaberite veličinu.');

                return;
            }

            if (qty <= 0) {

                alert('Količina nije validna.');

                return;
            }

            // =========================
            // REQUEST
            // =========================

            const res = await fetch(
                'index.php?page=cart&action=add',
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json'
                    },

                    body: JSON.stringify({
                        id,
                        qty,
                        size
                    })
                }
            );

            const data = await res.json();

            // =========================
            // RESPONSE
            // =========================

            if (!data.success) {

                alert(data.message || 'Greška.');

                return;
            }

            // =========================
            // UPDATE MINI CART
            // =========================

            loadCart();

            const cartCount =
                document.querySelector('.cart-count');

            if (cartCount) {

                cartCount.innerText = data.count;
            }

        } catch (err) {

            console.error('Add to cart error:', err);
        }
    });
}

// =========================================================
// LOAD CART
// =========================================================

async function loadCart() {

    try {

        const res = await fetch(
            'index.php?page=cart&action=get'
        );

        const data = await res.json();

        const cartCount =
            document.querySelector('.cart-count');

        // =========================
        // UPDATE COUNT
        // =========================

        if (cartCount) {

            cartCount.innerText = data.count;
        }

        // =========================
        // MINI CART
        // =========================

        const container =
            document.querySelector('.cart-items');

        if (!container) return;

        container.innerHTML = '';

        let total = 0;

        // =========================
        // EMPTY CART
        // =========================

        if (data.items.length === 0) {

            container.innerHTML = `
                <div class="empty-cart">
                    Korpa je prazna.
                </div>
            `;

            return;
        }

        // =========================
        // RENDER ITEMS
        // =========================

        data.items.forEach(item => {

            const subtotal =
                item.price * item.qty;

            total += subtotal;

            container.innerHTML += `
                <div class="cart-item">

                    <div class="cart-item-info">

                        <strong>${escapeHtml(item.name)}</strong>

                        <p>
                            ${item.size} •
                            ${item.qty} x
                            ${item.price}€
                        </p>

                    </div>

                </div>
            `;
        });

        // =========================
        // TOTAL
        // =========================

        container.innerHTML += `
            <div class="cart-total">
                <strong>
                    Total: ${total.toFixed(2)}€
                </strong>
            </div>
        `;

    } catch (err) {

        console.error('Load cart error:', err);
    }
}

// =========================================================
// ESCAPE HTML
// =========================================================

function escapeHtml(text) {

    const div = document.createElement('div');

    div.innerText = text;

    return div.innerHTML;
}

// =========================================================
// INITIAL LOAD
// =========================================================

loadCart();