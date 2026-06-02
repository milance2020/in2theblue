<form action="<?= appUrl('register') ?>" method="post" class="form" autocomplete="off">

    <h2>Registracija</h2>

    <!-- ERROR MESSAGE -->
    <?php if (!empty($_SESSION['register_error'])): ?>
        <div class="form-error">
            <?= htmlspecialchars($_SESSION['register_error']) ?>
        </div>
        <?php unset($_SESSION['register_error']); ?>
    <?php endif; ?>

    <!-- SUCCESS MESSAGE -->
    <?php if (!empty($_SESSION['register_success'])): ?>
        <div class="form-success">
            <?= htmlspecialchars($_SESSION['register_success']) ?>
        </div>
        <?php unset($_SESSION['register_success']); ?>
    <?php endif; ?>



    <label for="username">Korisničko ime</label>
    <input
        type="text"
        id="username"
        name="username"
        placeholder="Korisničko ime"
        value="<?= htmlspecialchars($_SESSION['old']['username'] ?? '') ?>"
        maxlength="30"
        required
        autocomplete="username"
    >


    <label for="name">Ime</label>
    <input
        type="text"
        id="name"
        name="name"
        placeholder="Ime"
         value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>"
        maxlength="50"
        required
        autocomplete="given-name"
    >


    <label for="last_name">Prezime</label>
    <input
        type="text"
        id="last_name"
        name="last_name"
        placeholder="Prezime"
         value="<?= htmlspecialchars($_SESSION['old']['last_name'] ?? '') ?>"
        maxlength="50"
        required
        autocomplete="family-name"
    >


    <label for="email">Email</label>
    <input
        type="email"
        id="email"
        name="email"
        placeholder="Email"
         value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>"
        maxlength="100"
        required
        autocomplete="email"
    >


    <label for="password">Lozinka</label>
    <input
        type="password"
        id="password"
        name="password"
        placeholder="Lozinka"
        minlength="8"
        required
        autocomplete="new-password"
    >


    <label for="confirm_password">Potvrdite lozinku</label>
    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        placeholder="Potvrdite lozinku"
        minlength="8"
        required
        autocomplete="new-password"
    >


    <button type="submit" class="btn">
        Registracija
    </button>

</form>
