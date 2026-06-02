<div class="product-layout">

    <main class="product-main">

        <div class="product-page">

            <div class="product-left">

                <img
                    src="<?= appUrl($product['image_path']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                >

            </div>

            <div class="product-right">

                <span class="product-category">
                    <?= htmlspecialchars($product['category_label']) ?>
                </span>

                <h1>
                    <?= htmlspecialchars($product['name']) ?>
                </h1>

                <p>
                    <?= htmlspecialchars($product['description']) ?>
                </p>

                <h3>
                    <?= number_format($product['price'], 2) ?> €
                </h3>

                <div class="size-select-wrapper">

                    <h4>Veličina</h4>

                    <select class="size-select">

                        <?php foreach ($sizes as $s): ?>

                            <?php if ($s['stock'] > 0): ?>

                                <option
                                    value="<?= htmlspecialchars($s['size']) ?>"
                                    data-stock="<?= (int) $s['stock'] ?>"
                                >
                                    <?= htmlspecialchars($s['size']) ?>
                                    (<?= (int) $s['stock'] ?> dostupno)
                                </option>

                            <?php else: ?>

                                <option disabled>
                                    <?= htmlspecialchars($s['size']) ?>
                                    (nije dostupno)
                                </option>

                            <?php endif; ?>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="qty-control">

                    <button class="decrease-qty-button" type="button">
                        -
                    </button>

                    <span class="qty">1</span>

                    <button class="increase-qty-button" type="button">
                        +
                    </button>

                </div>

                <button
                    class="add-to-cart-button"
                    data-id="<?= (int) $product['id'] ?>"
                    type="button"
                >
                    Dodaj u korpu
                </button>

            </div>

        </div>

        <section class="comments-section">

            <h2>Komentari</h2>

            <div id="toast"></div>
            <div id="toast-green"></div>

            <?php if (!empty($_SESSION['ulogovan'])): ?>

                <form
                    class="comment-form"
                    data-id="<?= (int) $product['id'] ?>"
                >

                    <input
                        type="text"
                        name="username"
                        value="<?= htmlspecialchars($_SESSION['username']) ?>"
                        readonly
                    >

                    <textarea
                        name="comment"
                        placeholder="Napiši komentar..."
                        required
                    ></textarea>

                    <button type="submit">
                        Komentariši
                    </button>

                </form>

            <?php else: ?>

                <div class="comments-login-note">
                    Morate biti ulogovani da biste komentarisali.
                </div>

            <?php endif; ?>

            <div
                class="comments"
                data-product-id="<?= (int) $product['id'] ?>"
            ></div>

        </section>

    </main>

    <aside class="product-recommendations">

        <span class="recommendation-tag">
            PREPORUČENO
        </span>

        <h2>
            Slični proizvodi
        </h2>

        <div class="recommendation-list">

            <?php if ($recommendedProducts && $recommendedProducts->num_rows > 0): ?>

                <?php while ($rec = $recommendedProducts->fetch_assoc()): ?>

                    <a
                        href="<?= productUrl($rec) ?>"
                        class="recommendation-card"
                    >

                        <img
                            src="<?= appUrl($rec['image_path']) ?>"
                            alt="<?= htmlspecialchars($rec['name']) ?>"
                        >

                        <div>
                            <span>
                                <?= htmlspecialchars($rec['category_label']) ?>
                            </span>

                            <h3>
                                <?= htmlspecialchars($rec['name']) ?>
                            </h3>

                            <p>
                                <?= number_format($rec['price'], 2) ?> €
                            </p>
                        </div>

                    </a>

                <?php endwhile; ?>

            <?php else: ?>

                <p class="no-recommendations">
                    Trenutno nema preporučenih proizvoda.
                </p>

            <?php endif; ?>

        </div>

    </aside>

</div>

<script src="<?= URL_ASSETS_JS_SHOP ?>product.js"></script>
<script src="<?= URL_ASSETS_JS . 'shop/comments.js' ?>"></script>