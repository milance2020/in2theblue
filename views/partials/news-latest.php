<div class="news-article">

    <div class="news-picture">
        <img src="<?= $news['image'] ?>" alt="<?= htmlspecialchars($news['title']) ?>">
    </div>

    <div class="news-content">

        <span class="news-tag">
            NOVOSTI IZ BARA
        </span>

        <h2>
            <?= htmlspecialchars($news['title']) ?>
        </h2>

        <p>
            <?= nl2br(substr($news['content'], 0, 180)) ?>...
        </p>

        <a href="<?= appUrl('news') ?>" class="news-btn">
            Pročitaj više
        </a>

    </div>

</div>