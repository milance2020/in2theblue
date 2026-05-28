<!-- FEATURED NEWS -->
<div class="news-wrapper">
    <div class="featured-news">

        <?php if (!empty($_SESSION['ulogovan'])): ?>

            <input type="text" id="news-title" value="<?= htmlspecialchars($currentNews['title']) ?>">

            <textarea id="news-content"><?= htmlspecialchars($currentNews['content']) ?></textarea>

            <img src="<?= appUrl($currentNews['image']) ?>" style="max-width:100%; margin:10px 0;">

            <button id="save-news" data-id="<?= $currentNews['id'] ?>">
                Sačuvaj
            </button>

        <?php else: ?>

            <h1><?= $currentNews['title'] ?></h1>

            <img src="<?=appUrl($currentNews['image']) ?>">

            <p><?= nl2br($currentNews['content']) ?></p>

        <?php endif; ?>

    </div>

    <hr>

    <!-- OLDER NEWS -->
    <div class="news-list">

        <?php while ($row = $otherNews->fetch_assoc()): ?>

            <div class="news-item">
                <h3><?= $row['title'] ?></h3>

                <p><?= substr($row['content'], 0, 120) ?>...</p>

                <a href="<?= newsUrl($row) ?>">
                    Procitaj više
                </a>

            </div>

        <?php endwhile; ?>

    </div>
</div>


<script>window.APP_URLS = <?= json_encode(['adminUpdateNewsInline' => URL_ADMIN_UPDATE_NEWS_INLINE]) ?>;</script>
<script src="<?= URL_ASSETS_JS ?>news/news.js">

</script>