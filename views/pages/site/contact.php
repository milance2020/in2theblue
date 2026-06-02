<section class="contact-page">

    <div class="contact-hero">
        <h1><?= e($contactContent['contact_hero_title']) ?></h1>
        <p>
            <?= e($contactContent['contact_hero_text']) ?>
        </p>
    </div>

    <div class="contact-container">

        <div class="contact-info-card">

            <h2><?= e($contactContent['contact_info_title']) ?></h2>

            <div class="contact-info-item">
                <span>Adresa</span>
                <strong><?= e($contactContent['contact_address']) ?></strong>
            </div>

            <div class="contact-info-item">
                <span>Telefon</span>
                <strong><?= e($contactContent['contact_phone']) ?></strong>
            </div>

            <div class="contact-info-item">
                <span>Email</span>
                <strong><?= e($contactContent['contact_email']) ?></strong>
            </div>

            <div class="contact-info-item">
                <span>Radno vrijeme</span>
                <strong><?= e($contactContent['contact_working_hours']) ?></strong>
            </div>

            <div class="contact-socials">
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">TikTok</a>
            </div>

        </div>

        <div class="contact-form-card">

            <h2>Pošaljite poruku</h2>

            <?php if (!empty($messageSent)): ?>
                <div class="success-message">
                    Poruka je uspješno poslana.
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?= htmlspecialchars($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">

                <div class="form-group">
                    <label>Ime i prezime</label>

                    <input 
                        type="text" 
                        name="full_name"
                        value="<?= htmlspecialchars($fullName ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Email adresa</label>

                    <input 
                        type="email" 
                        name="email"
                        value="<?= htmlspecialchars($email ?? '') ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Tema</label>

                    <select name="subject" required>
                        <option value="">Odaberite temu</option>

                        <option 
                            value="bar"
                            <?= ($subject ?? '') === 'bar' ? 'selected' : '' ?>
                        >
                            IN2THEBAR
                        </option>

                        <option 
                            value="shop"
                            <?= ($subject ?? '') === 'shop' ? 'selected' : '' ?>
                        >
                            IN2THESHOP
                        </option>

                        <option 
                            value="other"
                            <?= ($subject ?? '') === 'other' ? 'selected' : '' ?>
                        >
                            Ostalo
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Poruka</label>

                    <textarea 
                        name="message" 
                        rows="6" 
                        required
                    ><?= htmlspecialchars($message ?? '') ?></textarea>
                </div>

                <button type="submit" class="contact-btn">
                    Pošalji poruku
                </button>

            </form>

        </div>

    </div>

</section>
