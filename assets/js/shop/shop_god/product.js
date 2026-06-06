class ProductPage {

    static init() {
        // Elementi postoje samo na product detail stranici.
        this.qtyEl = document.querySelector('.qty');
        this.sizeSelect = document.querySelector('.size-select');
        this.addBtn = document.querySelector('.add-to-cart-button');
        this.increaseBtn = document.querySelector('.increase-qty-button');
        this.decreaseBtn = document.querySelector('.decrease-qty-button');

        this.bindQty();
        this.bindAdd();
    }

    static bindQty() {
        // Kolicina se mijenja lokalno, server provjerava stock na add.
        if (this.increaseBtn && this.qtyEl) {
            this.increaseBtn.addEventListener('click', () => {
                let qty = parseInt(this.qtyEl.textContent, 10) || 1;
                this.qtyEl.textContent = qty + 1;
            });
        }

        if (this.decreaseBtn && this.qtyEl) {
            this.decreaseBtn.addEventListener('click', () => {
                let qty = parseInt(this.qtyEl.textContent, 10) || 1;
                if (qty > 1) {
                    this.qtyEl.textContent = qty - 1;
                }
            });
        }
    }

    static bindAdd() {
        // Slanje proizvoda u cart API.
        if (!this.addBtn) return;

        this.addBtn.addEventListener('click', async () => {
            const id = this.addBtn.dataset.id;
            const size = this.sizeSelect ? this.sizeSelect.value : null;
            const qty = this.qtyEl ? parseInt(this.qtyEl.textContent, 10) : 1;

            if (!size) {
                alert('Odaberite veličinu.');
                return;
            }

            if (!qty || qty <= 0) {
                alert('Količina nije validna.');
                return;
            }

            const res = await CartService.add(id, size, qty);

            if (res.success) {
                await CartUI.refresh();
            } else {
                alert(res.message || 'Greška.');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    ProductPage.init();
    CartUI.init();
    CartUI.refresh();
});
