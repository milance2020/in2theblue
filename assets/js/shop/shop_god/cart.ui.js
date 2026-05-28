class CartUI {

    // =====================================================
    // INITIALIZE UI REFERENCES
    // =====================================================

    static init() {

        // cart badge in navbar
        this.badge =
            document.querySelector('.cart-count');

        // total element
        this.totalEl =
            document.querySelector('.cart-total');
    }

    // =====================================================
    // UPDATE CART BADGE
    // =====================================================

    static updateBadge(count) {

        // safety check
        if (!this.badge) return;

        this.badge.textContent = count;
    }

    // =====================================================
    // UPDATE TOTAL PRICE
    // =====================================================

    static updateTotal(total) {

        if (!this.totalEl) return;

        this.totalEl.textContent =
            parseFloat(total).toFixed(2);
    }

    // =====================================================
    // REFRESH COMPLETE CART UI
    // =====================================================

    static async refresh() {

        // fetch latest cart data
        const data = await CartService.get();

        // API failed
        if (!data.success) return;

        // update navbar badge
        this.updateBadge(data.count);

        // update total if available
        if (data.total !== undefined) {

            this.updateTotal(data.total);
        }

        // render minicart items
        this.renderMiniCart(data.items || []);
    }

    // =====================================================
    // RENDER MINI CART
    // =====================================================

    static renderMiniCart(items) {

        const container =
            document.querySelector('.cart-items');

        if (!container) return;

        // =============================
        // EMPTY CART
        // =============================

        if (!items.length) {

            container.innerHTML = `
                <div class="empty-cart">
                    Korpa je prazna.
                </div>
            `;

            return;
        }

        let html = '';

        let total = 0;

        // =============================
        // LOOP ITEMS
        // =============================

        items.forEach(item => {

            // subtotal for one item
            const subtotal =
                item.price * item.qty;

            total += subtotal;

            html += `
                <div class="cart-item">

                    <strong>
                        ${this.escapeHtml(item.name)}
                    </strong>

                    <p>
                        ${item.size}
                        •
                        ${item.qty} x ${item.price}€
                    </p>

                </div><hr>
            `;
        });

        // =============================
        // TOTAL
        // =============================

        html += `
            <div class="cart-total">

                <strong>
                    Total: ${total.toFixed(2)}€
                </strong>

            </div><hr>
        `;

        // inject final HTML
        container.innerHTML = html;
    }

    // =====================================================
    // ESCAPE HTML
    // Prevent XSS injection
    // =====================================================

    static escapeHtml(text) {

        const el =
            document.createElement('div');

        el.textContent = text;

        return el.innerHTML;
    }
}