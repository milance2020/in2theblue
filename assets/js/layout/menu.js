const menuButton = document.querySelector('.meni-btn');
const pdfContainer = document.getElementById('pdfContainer');

// PDF meni se ucitava tek kad korisnik odluci da ga otvori.
if (menuButton && pdfContainer) {
    menuButton.addEventListener('click', function () {
        const isVisible = pdfContainer.style.display === 'block';

        pdfContainer.style.display = isVisible ? 'none' : 'block';
        menuButton.textContent = isVisible ? 'Prikaži meni' : 'Sakrij meni';
    });
}
