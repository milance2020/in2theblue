class CheckoutPage {

    static init() {
        this.loadInitial();
    }

    static escapeHtml(text) {
        const el = document.createElement('div');
        el.textContent = text ?? '';
        return el.innerHTML;
    }

    static async loadInitial() {
        const data = await CartService.get();

        if (!data.success || !data.items.length) {
            document.getElementById('cart-app').style.display = 'none';
            document.getElementById('cart-empty').style.display = 'block';
            return;
        }

        this.render(data);
    }

    static bindEvents() {
        document.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                await CartService.remove(btn.dataset.id, btn.dataset.size);
                await this.refresh();
            });
        });

        document.querySelectorAll('.increase-qty-button').forEach(btn => {
            btn.addEventListener('click', async () => {
                await CartService.increase(btn.dataset.id, btn.dataset.size);
                await this.refresh();
            });
        });

        document.querySelectorAll('.decrease-qty-button').forEach(btn => {
            btn.addEventListener('click', async () => {
                await CartService.decrease(btn.dataset.id, btn.dataset.size);
                await this.refresh();
            });
        });
    }

    static async refresh() {
        const data = await CartService.get();

        CartUI.updateBadge(data.count);

        if (!data.items.length) {
            document.getElementById('cart-app').style.display = 'none';
            document.getElementById('cart-empty').style.display = 'block';
            return;
        }

        document.getElementById('cart-app').style.display = 'block';
        document.getElementById('cart-empty').style.display = 'none';

        this.render(data);
    }

    static render(data) {
        const container = document.querySelector('#cart-app');
        if (!container) return;

        let html = '<div class="cart-items-list">';

        data.items.forEach(item => {
            const price = parseFloat(item.price);
            const subtotal = parseFloat(item.subtotal ?? price * item.qty);

            html += `
                <div class="cart-row">

                    <button type="button" class="remove-item-btn"
                        data-id="${item.id}"
                        data-size="${this.escapeHtml(item.size)}"
                        aria-label="Remove item">
                        🗑
                    </button>

                    <img class="cart-row-image"
                        src="${this.escapeHtml(item.image_path)}"
                        alt="${this.escapeHtml(item.name)}"
                        width="80" height="80">

                    <div class="cart-info">
                        <h3 class="cart-item-name">${this.escapeHtml(item.name)}</h3>
                        <p class="cart-item-meta">Size: ${this.escapeHtml(item.size)}</p>
                        <span class="price">${price}</span>

                        <div class="qty-control">
                            <button type="button" class="decrease-qty-button"
                                data-id="${item.id}"
                                data-size="${this.escapeHtml(item.size)}">-</button>
                            <span class="qty">${item.qty}</span>
                            <button type="button" class="increase-qty-button"
                                data-id="${item.id}"
                                data-size="${this.escapeHtml(item.size)}">+</button>
                        </div>
                    </div>

                    <div class="cart-row-end">
                        <span class="subtotal-amount">${subtotal.toFixed(2)} €</span>
                    </div>

                </div>
            `;
        });

        html += `
            </div>
            <div class="cart-footer">
                <p class="cart-page-total">
                    Total: <span class="total-amount">${parseFloat(data.total).toFixed(2)}</span> €
                </p>
                <a href="order/" class="btn btn-checkout">Naruči</a>
            </div>
        `;

        container.innerHTML = html;
        this.bindEvents();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    CartUI.init();
    CheckoutPage.init();
});
