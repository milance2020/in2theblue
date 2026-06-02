<?php

if (!isset($_SESSION['ulogovan'])) {
    $_SESSION['ulogovan'] = USER_LEVEL_ANONYMOUS;
}
?>
<?php if ($_SESSION['ulogovan'] == USER_LEVEL_ANONYMOUS) : ?>
    
    <!-- Log in forma -->
    <div >
        
        <form method="post" class="form">
            <H2>Prijava</H2>
            <label for="username">Korisničko ime</label>
            <input type="text" name="username" id="username" value="<?php echo $username ?? ''; ?>">
            <label for="password">Lozinka</label>
            <input type="password" name="password" id="password" value="<?php echo $password ?? ''; ?>">
            <input type="submit">
            <p>Nemate račun? <a href="<?= appUrl('register') ?>">Registrirajte se ovdje</a></p>
            <?php
            if (!empty($_SESSION['login_error'])) {
                echo "<div class='error'>" . $_SESSION['login_error'] . "</div>";
                unset($_SESSION['login_error']);
            }
            ?>

        </form>

    </div>
<?php else : ?>
    <h1>Već ste prijavljeni</h1>
    <a href="<?= logoutUrl() ?>">Odjavi se</a>

<?php endif; ?>
