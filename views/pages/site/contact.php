<section class="contact-page">

    <div class="contact-hero">
        <h1>Kontakt</h1>
        <p>
            Imate pitanje za IN2THEBAR ili IN2THESHOP?
            Javite nam se putem forme ili direktno preko kontakt podataka.
        </p>
    </div>

    <div class="contact-container">

        <div class="contact-info-card">

            <h2>IN2 Kontakt</h2>

            <div class="contact-info-item">
                <span>Adresa</span>
                <strong>Ulica 123, Punat, Hrvatska</strong>
            </div>

            <div class="contact-info-item">
                <span>Telefon</span>
                <strong>+385 91 123 4567</strong>
            </div>

            <div class="contact-info-item">
                <span>Email</span>
                <strong>info@in2.hr</strong>
            </div>

            <div class="contact-info-item">
                <span>Radno vrijeme</span>
                <strong>Pon - Ned: 08:00 - 02:00</strong>
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