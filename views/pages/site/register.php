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



    <label for="username">Username</label>
    <input
        type="text"
        id="username"
        name="username"
        placeholder="Username"
        value="<?= htmlspecialchars($_SESSION['old']['username'] ?? '') ?>"
        maxlength="30"
        required
        autocomplete="username"
    >


    <label for="name">Name</label>
    <input
        type="text"
        id="name"
        name="name"
        placeholder="Name"
         value="<?= htmlspecialchars($_SESSION['old']['name'] ?? '') ?>"
        maxlength="50"
        required
        autocomplete="given-name"
    >


    <label for="last_name">Last Name</label>
    <input
        type="text"
        id="last_name"
        name="last_name"
        placeholder="Last Name"
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


    <label for="password">Password</label>
    <input
        type="password"
        id="password"
        name="password"
        placeholder="Password"
        minlength="8"
        required
        autocomplete="new-password"
    >


    <label for="confirm_password">Confirm Password</label>
    <input
        type="password"
        id="confirm_password"
        name="confirm_password"
        placeholder="Confirm Password"
        minlength="8"
        required
        autocomplete="new-password"
    >


    <button type="submit" class="btn">
        Register
    </button>

</form>
