class ExplorePage {

    static init() {
        this.bindSizes();
        this.bindAddButtons();
    }

static bindSizes() {
    // Velicina se cuva na add dugmetu unutar iste kartice.

    document.querySelectorAll('.size-btn')
        .forEach(btn => {

            btn.addEventListener('click', () => {

                const selectedSize =
                    btn.dataset.size;

                const productCard =
                    btn.closest('.product-card');

                productCard
                    .querySelectorAll('.size-btn')
                    .forEach(b =>
                        b.classList.remove('active')
                    );

                btn.classList.add('active');

                const addButton =
                    productCard.querySelector(
                        '.add-to-cart-button'
                    );

                addButton.dataset.size =
                    selectedSize;
            });
        });
}
    static bindAddButtons() {
        // Explore dodaje po 1 komad odabrane velicine.

        document.querySelectorAll('.add-to-cart-button')
            .forEach(btn => {

                btn.addEventListener('click', async () => {

                    const id = btn.dataset.id;
                    const size = btn.dataset.size;

                    if (!size) {
                        alert("Molimo odaberite veličinu!");
                        return;
                    }

                    const res = await CartService.add(id, size, 1);

                    if (res.success) {
                        await CartUI.refresh();
                    } else {
                        alert(res.message);
                    }
                });
            });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    ExplorePage.init();
    CartUI.init();
    CartUI.refresh();
});
