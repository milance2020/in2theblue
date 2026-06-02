const menuButton = document.querySelector('.meni-btn');
const pdfContainer = document.getElementById('pdfContainer');

if (menuButton && pdfContainer) {
    menuButton.addEventListener('click', function () {
        const isVisible = pdfContainer.style.display === 'block';

        pdfContainer.style.display = isVisible ? 'none' : 'block';
        menuButton.textContent = isVisible ? 'Prikaži meni' : 'Sakrij meni';
    });
}
