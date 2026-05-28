document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', function (e) {
        if (!confirm('Da li si siguran da želiš obrisati?')) {
            e.preventDefault(); // stop link
        }
    });
});