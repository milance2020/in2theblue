class ExplorePage {

    static init() {
        this.bindSizes();
        this.bindAddButtons();
    }

static bindSizes() {

    document.querySelectorAll('.size-btn')
        .forEach(btn => {

            btn.addEventListener('click', () => {

                const selectedSize =
                    btn.dataset.size;

                const productCard =
                    btn.closest('.product-card');

                // remove active only
                // inside this card

                productCard
                    .querySelectorAll('.size-btn')
                    .forEach(b =>
                        b.classList.remove('active')
                    );

                btn.classList.add('active');

                // set size only
                // for this product

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

        document.querySelectorAll('.add-to-cart-button')
            .forEach(btn => {

                btn.addEventListener('click', async () => {

                    const id = btn.dataset.id;
                    const size = btn.dataset.size;
                   // const productCard = btn.closest('.product-card');
                    //const sizeSelect = productCard.querySelector('.size-btn');
                    //const size2 = sizeSelect.value;

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
