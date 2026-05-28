<form action="index.php?page=adminPanel&action=insertNews" method="post" enctype="multipart/form-data" class="form">
    
    <!-- NASLOV -->
    <label for="title">Naslov</label>
    <input type="text" name="title" id="title" required>

    <!-- SADRŽAJ -->
    <label for="content">Sadržaj</label>
    <textarea name="content" id="content" rows="6" required></textarea>

    <!-- SLIKA -->
    <label for="image">Slika</label>
    <input type="file" name="image" id="image" accept="image/*">

    <!-- KATEGORIJA (ENUM) -->
    <label for="category">Kategorija</label>
    <select name="category" id="category" required>
        <option value="">-- Odaberi --</option>
        <option value="bar">Bar</option>
        <option value="rooms">Rooms</option>
        <option value="shop">Shop</option>
    </select>

    <!-- SUBMIT -->
    <input type="submit" value="Spremi članak">

</form>