<form action="index.php?page=adminPanel&action=insertUsers" method="post" enctype="multipart/form-data" class="form">
    <?= csrf_input() ?>

    <label for="username">Username</label>
    <input type="text" name="username" required><br>

    <label for="email">E-mail</label>
    <input type="email" name="email" required><br>

    <label for="password">Password</label>
    <input type="password" name="password"><br>

    <label for="role">Odaberi rolu:</label>
    <select name="role" id="role" required>
        <option value="admin">Admin</option>
        <option value="user">User</option>
        <option value="manager">Manager</option>
    </select>
    <br>

    <input type="submit" value="posalji">
</form>
