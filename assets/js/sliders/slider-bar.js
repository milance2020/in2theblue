const slides = [
    'assets/images/images_bar/sliders/slider.jpg',
   'assets/images/images_bar/sliders/slider2.jpg',
    'assets/images/images_bar/sliders/slider3.jpg'
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
