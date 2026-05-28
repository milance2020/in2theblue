<div class="shop-hero">

    <span class="shop-tag">
        NOVA KOLEKCIJA
    </span>

    <h1>
        Istražite Naše Najnovije Proizvode
    </h1>

</div>

<div class="shop-layout">

    <aside class="sidebar">

        <form method="GET" action="<?= shopUrl() ?>" class="shop-search">

            <?php if (!empty($category)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($category) ?>">
            <?php endif; ?>

            <?php if (!empty($gender)): ?>
                <input type="hidden" name="gender" value="<?= htmlspecialchars($gender) ?>">
            <?php endif; ?>

            <input type="text" name="search" placeholder="Pretraži proizvode..."
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

        </form>

        <div class="sidebar-section">

            <h3>Kategorije</h3>

            <ul class="sidebar-links">

                <li>
                    <a href="<?= shopUrl() ?>" class="<?= empty($category) ? 'active' : '' ?>">
                        Svi proizvodi
                    </a>
                </li>

                <?php foreach ($categories as $cat): ?>

                    <li>
                        <a href="<?= categoryUrl($cat) ?>" class="<?= $category === $cat['slug'] ? 'active' : '' ?>">
                            <?= htmlspecialchars($cat['label']) ?>
                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

        <div class="sidebar-section">

            <h3>Pol</h3>

            <ul class="sidebar-links">

                <li>
                    <a href="<?= shopUrl(array_filter([
                        'category' => $category ?: null
                    ])) ?>" class="<?= empty($gender) ? 'active' : '' ?>">
                        Svi
                    </a>
                </li>

                <li>
                    <a href="<?= shopUrl(array_filter([
                        'category' => $category ?: null,
                        'gender' => 'male'
                    ])) ?>" class="<?= $gender === 'male' ? 'active' : '' ?>">
                        Muško
                    </a>
                </li>

                <li>
                    <a href="<?= shopUrl(array_filter([
                        'category' => $category ?: null,
                        'gender' => 'female'
                    ])) ?>" class="<?= $gender === 'female' ? 'active' : '' ?>">
                        Žensko
                    </a>
                </li>

                <li>
                    <a href="<?= shopUrl(array_filter([
                        'category' => $category ?: null,
                        'gender' => 'unisex'
                    ])) ?>" class="<?= $gender === 'unisex' ? 'active' : '' ?>">
                        Unisex
                    </a>
                </li>

            </ul>

        </div>

    </aside>

    <div class="products-container">

        <?php while ($row = $result->fetch_assoc()): ?>

            <div class="product-card">

                <img src="<?= appUrl($row['image_path']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">

                <h3>
                    <?= htmlspecialchars($row['name']) ?>
                </h3>

                <p class="meta">
                    <?= htmlspecialchars($row['category_label']) ?> •
                    <?= htmlspecialchars(ucfirst($row['gender'])) ?>
                </p>

                <p class="price">
                    <?= number_format($row['price'], 2) ?> €
                </p>

                <div class="size-mini">
                    <button class="size-btn" data-size="S">S</button>
                    <button class="size-btn" data-size="M">M</button>
                    <button class="size-btn" data-size="L">L</button>
                    <button class="size-btn" data-size="XL">XL</button>
                </div>

                <div class="product-actions">

                    <a href="<?= productUrl($row) ?>" class="btn">
                        Detalji
                    </a>

                    <button class="btn add-to-cart-button" data-id="<?= (int) $row['id'] ?>" data-size="">
                        Dodaj u korpu
                    </button>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>

<script src="<?= URL_ASSETS_JS_SHOP ?>explore.js"></script>