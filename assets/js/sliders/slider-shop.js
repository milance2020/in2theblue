const slider = document.getElementById('slider');

const isMobile = window.innerWidth <= 768;

const slides = isMobile
    ? [
        'assets/images/images_shop/materials/slidermob.jpg',
    ]
    : [
        'assets/images/images_shop/materials/slider.png',
    ];

slides.forEach((src, i) => {

    const img = document.createElement('img');

    img.src = src + '?v=2';

    img.style.display = i === 0 ? 'block' : 'none';

    slider.appendChild(img);

});


// SLIDESHOW
let current = 0;

setInterval(() => {

    const images = slider.querySelectorAll('img');

    images[current].style.display = 'none';

    current = (current + 1) % images.length;

    images[current].style.display = 'block';

}, 3000);