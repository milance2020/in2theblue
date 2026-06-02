<div class="news-article">

    <div class="news-picture">
        <img src="<?= e(storedFileUrl($news['image'])) ?>" alt="<?= e($news['title']) ?>">
    </div>

    <div class="news-content">

        <span class="news-tag">
            NOVOSTI IZ BARA
        </span>

        <h2>
            <?= e($news['title']) ?>
        </h2>

        <p>
            <?= nl2br(e(substr(strip_tags($news['content']), 0, 180))) ?>...
        </p>

        <a href="<?= e(newsUrl($news)) ?>" class="news-btn">
            Pročitaj više
        </a>

    </div>

</div>
