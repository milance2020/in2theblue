<section class="error-page">
    <div class="error-card">
        <span>403</span>
        <h1>Zabranjen pristup</h1>
        <p>
            Nemate dozvolu za pristup ovoj stranici.
        </p>

        <div class="error-actions">
            <a href="<?= appUrl('login') ?>" class="error-btn primary">
                Prijava
            </a>
            <a href="<?= shopUrl() ?>" class="error-btn">
                Prodavnica
            </a>
        </div>
    </div>
</section>
