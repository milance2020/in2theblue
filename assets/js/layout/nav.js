const nav = document.querySelector('.site-nav');
const navToggle = document.querySelector('.nav-toggle');

if (nav && navToggle) {
    navToggle.addEventListener('click', function () {
        nav.classList.toggle('open');
    });
}

const cartWrapper = document.querySelector('.cart-wrapper');

if (cartWrapper) {
    cartWrapper.addEventListener('click', function (e) {
        if (window.innerWidth > 900) {
            return;
        }

        const cartIcon = e.target.closest('.cart-icon');

        if (!cartIcon) {
            return;
        }

        e.preventDefault();
        cartWrapper.classList.toggle('open');
    });
}
