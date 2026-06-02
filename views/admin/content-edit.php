<div class="site-content-editor">

    <div class="site-content-header">
        <div>
            <h1>Uredi sadržaj stranice</h1>
            <p>Uredi tekstove koji se prikazuju na početnoj, kontakt stranici i footeru.</p>
        </div>
    </div>

    <form
        action="index.php?page=adminPanel&action=updateSiteContent"
        method="post"
        class="site-content-form"
    >
        <?= csrf_input() ?>

        <?php foreach ($contentSections as $sectionTitle => $sectionDefaults): ?>
            <section class="content-section-card">
                <div class="content-section-title">
                    <h2><?= e($sectionTitle) ?></h2>
                </div>

                <div class="content-fields-grid">
                    <?php foreach ($sectionDefaults as $key => $defaultValue): ?>
                        <?php
                        $label = $contentTitles[$key] ?? $key;
                        $value = $siteContent[$key] ?? $defaultValue;
                        $isTextarea = str_contains($key, '_text') || str_contains($key, '_brand_text');
                        ?>

                        <div class="content-field <?= $isTextarea ? 'content-field-wide' : '' ?>">
                            <label for="<?= e($key) ?>">
                                <?= e($label) ?>
                            </label>

                            <?php if ($isTextarea): ?>
                                <textarea
                                    name="content[<?= e($key) ?>]"
                                    id="<?= e($key) ?>"
                                    rows="4"
                                ><?= e($value) ?></textarea>
                            <?php else: ?>
                                <input
                                    type="text"
                                    name="content[<?= e($key) ?>]"
                                    id="<?= e($key) ?>"
                                    value="<?= e($value) ?>"
                                >
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>

        <div class="site-content-actions">
            <button type="submit" class="btn">
                Spremi sadržaj
            </button>
        </div>
    </form>

</div>
