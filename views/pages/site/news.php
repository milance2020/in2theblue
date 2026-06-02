<div class="news-page">

    <header class="news-page-header">
        <span>In2TheBlue</span>
        <h1>Vijesti</h1>
        <p>Novosti iz bara, shopa i smještaja na jednom mjestu.</p>
    </header>

    <nav class="news-category-nav" aria-label="Kategorije vijesti">
        <?php foreach ($newsCategories as $key => $label): ?>
            <?php
            $url = $key === ''
                ? appUrl('news')
                : appUrl('news') . '?' . http_build_query(['category' => $key]);
            ?>

            <a
                href="<?= e($url) ?>"
                class="<?= $category === $key ? 'active' : '' ?>"
            >
                <?= e($label) ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <?php if (!$currentNews): ?>

        <section class="news-empty">
            <h2>Trenutno nema objavljenih vijesti.</h2>
            <p>Vratite se uskoro, nove objave će biti prikazane ovdje.</p>
        </section>

    <?php else: ?>

        <article class="news-featured">
            <?php if (!empty($currentNews['image'])): ?>
                <div class="news-featured-image">
                    <img
                        src="<?= e(storedFileUrl($currentNews['image'])) ?>"
                        alt="<?= e($currentNews['title']) ?>"
                    >
                </div>
            <?php endif; ?>

            <div class="news-featured-content">
                <div class="news-meta">
                    <span><?= e($newsCategories[$currentNews['category']] ?? ucfirst($currentNews['category'] ?? 'Vijesti')) ?></span>

                    <?php if (!empty($currentNews['created_at'])): ?>
                        <time datetime="<?= e($currentNews['created_at']) ?>">
                            <?= e(date('d.m.Y.', strtotime($currentNews['created_at']))) ?>
                        </time>
                    <?php endif; ?>
                </div>

                <h2><?= e($currentNews['title']) ?></h2>

                <div class="news-article-body">
                    <?= nl2br(e($currentNews['content'])) ?>
                </div>
            </div>
        </article>

        <?php if ($otherNews && $otherNews->num_rows > 0): ?>
            <section class="news-more-section">
                <div class="news-more-header">
                    <span>Arhiva</span>
                    <h2>Još vijesti</h2>
                </div>

                <div class="news-grid">
                    <?php while ($row = $otherNews->fetch_assoc()): ?>
                        <article class="news-card">
                            <?php if (!empty($row['image'])): ?>
                                <a href="<?= e(newsUrl($row)) ?>" class="news-card-image">
                                    <img
                                        src="<?= e(storedFileUrl($row['image'])) ?>"
                                        alt="<?= e($row['title']) ?>"
                                    >
                                </a>
                            <?php endif; ?>

                            <div class="news-card-content">
                                <div class="news-meta">
                                    <span><?= e($newsCategories[$row['category']] ?? ucfirst($row['category'] ?? 'Vijesti')) ?></span>

                                    <?php if (!empty($row['created_at'])): ?>
                                        <time datetime="<?= e($row['created_at']) ?>">
                                            <?= e(date('d.m.Y.', strtotime($row['created_at']))) ?>
                                        </time>
                                    <?php endif; ?>
                                </div>

                                <h3><?= e($row['title']) ?></h3>

                                <p>
                                    <?= e(substr(strip_tags($row['content']), 0, 140)) ?>...
                                </p>

                                <a href="<?= e(newsUrl($row)) ?>" class="news-read-more">
                                    Pročitaj više
                                </a>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
        <?php endif; ?>

    <?php endif; ?>

</div>
