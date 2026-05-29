<form action="index.php?page=adminPanel&action=insert" method="post" enctype="multipart/form-data" class="form">
    <?= csrf_input() ?>

    <label>Sifra proizvoda</label>
    <input type="text" name="sku" required><br>

    <label>Naziv proizvoda</label>
    <input type="text" name="name" required><br>

    <label>Kategorija</label>
    <select name="category_id" required>
        <option value="1">Majica</option>
        <option value="2">Duks</option>
        <option value="3">Dukserica</option>
        <option value="4">Sorc</option>
        <option value="5">Hlace</option>
        <option value="6">Ostalo</option>
    </select><br>

    <label>Pol</label>
    <select name="gender" required>
        <option value="male">Musko</option>
        <option value="female">Zensko</option>
        <option value="unisex">Unisex</option>
    </select><br>

    <label>Opis</label>
    <input type="text" name="description"><br>

    <label>Cijena</label>
    <input type="number" step="0.01" name="price"><br>

    <label>Slika</label>
    <input type="file" name="productPicture" accept="image/*"><br><br>

    <hr>

    <h3>Stock po velicinama</h3>

    <label>S</label>
    <input type="number" name="size_s" value="0"><br>

    <label>M</label>
    <input type="number" name="size_m" value="0"><br>

    <label>L</label>
    <input type="number" name="size_l" value="0"><br>

    <label>XL</label>
    <input type="number" name="size_xl" value="0"><br>

    <br>

    <input type="submit" value="Dodaj proizvod">
</form>
