const slides = [
    'assets/images/images_shop/materials/slider.png',
   
];

const slider = document.getElementById('slider');

slides.forEach((src, i) => {
    const img = document.createElement('img');
    img.src = src;
    img.style.display = i === 0 ? 'block' : 'none'; // samo prvi slajd vidljiv
    slider.appendChild(img);
});

// jednostavan slideshow
let current = 0;
setInterval(() => {
    const images = slider.querySelectorAll('img');
    images[current].style.display = 'none';
    current = (current + 1) % images.length;
    images[current].style.display = 'block';
}, 3000);
