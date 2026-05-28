<section class="bar-intro-section">

    <div class="bar-intro-content">

        <span class="bar-section-tag">
            IN2THEBAR
        </span>

        <h2>
            Naš Bar – Vaše Mjesto Susreta
        </h2>

        <p class="bar-lead">
            Hrvatski stil susreće austrijsku kulturu u samom centru Punta.
        </p>

        <p>
            IN2THEBAR je mjesto za nautičare, lokalno stanovništvo,
            turiste i ljubitelje dobre atmosfere uz more.
        </p>

    </div>

</section>



<section class="bar-offer-section">

    <div class="bar-offer-grid">

        <?php
        $cards = [
            ['img' => 'dorucak.jpg', 'title' => 'Doručci', 'text' => 'Svježi doručci, kroasani, omleti i tost za početak dana.'],
            ['img' => 'smoothie.jpg', 'title' => 'Smoothieji', 'text' => 'Lagani i osvježavajući smoothieji za dan uz more.'],
            ['img' => 'kava.jpg', 'title' => 'Kava', 'text' => 'Kvalitetna kava za jutro, pauzu ili opušteno druženje.'],
            ['img' => 'koktel.jpg', 'title' => 'Kokteli', 'text' => 'Kokteli za zalaske sunca i večernju atmosferu.'],
            ['img' => 'ginT.jpg', 'title' => 'Gin Tonic', 'text' => 'Elegantni gin tonic izbor za posebne trenutke.'],
            ['img' => 'pivo.jpg', 'title' => 'Pivo', 'text' => 'Hladno Hirter pivo i klasici za opuštene večeri.'],
        ];
        ?>

        <?php foreach ($cards as $card): ?>

            <article class="bar-offer-card">

                <div
                    class="bar-offer-img"
                    style="background-image:url('<?= URL_ASSETS_IMAGES_BAR_CARDS . $card['img'] ?>')"
                ></div>

                <div class="bar-offer-content">

                    <h3>
                        <?= htmlspecialchars($card['title']) ?>
                    </h3>

                    <p>
                        <?= htmlspecialchars($card['text']) ?>
                    </p>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

</section>



<section class="bar-story-section">

    <div class="bar-story-content">

        <span class="bar-section-tag">
            O NAMA
        </span>

        <h2>
            Mjesto gdje dan počinje kavom, a završava koktelima
        </h2>

        <p>
            S našom školom jedrenja stvorili smo sportsku obitelj,
            a s IN2THEBAR-om mjesto gdje se društvena okupljanja
            susreću s užitkom, glazbom i opuštenom atmosferom.
        </p>

        <p>
            Ujutro vas čekaju doručci, smoothie zdjelice i kvalitetna
            kava, dok večeri donose koktele, glazbu i pogled na more.
        </p>

        <p>
            Ugodan interijer, terasa uz more i sjenoviti vrt stvaraju
            prostor za opuštanje tijekom cijelog dana.
        </p>

        <a href="<?= shopUrl() ?>" class="bar-story-btn">
            Posjeti In2TheShop
        </a>

    </div>

</section>



<section class="bar-menu-section">

 

    <div class="bar-menu-wrapper">

        <?php include_once FILE_MENI; ?>

    </div>

</section>



<?php
if ($_show_news ?? false) {
    include FILE_MODEL_NEWS_LATEST;
    include FILE_VIEW_MODEL_NEWS_LATEST;
}
?>



<section class="bar-map-section">

    <div class="bar-map-header">

        <span class="bar-section-tag">
            LOKACIJA
        </span>

        <h2>
            Posjeti Nas Uz More
        </h2>

        <p>
            Nalazimo se u centru Punta, uz more i malu luku.
        </p>

    </div>

    <div class="bar-map-container">

        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2820.1398229386887!2d14.628828799999999!3d45.022087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x476375f9ae137b55%3A0xeb334bcce25ddd06!2sIN%202%20THE%20BAR!5e0!3m2!1shr!2shr!4v1760427591444!5m2!1shr!2shr"
            width="100%"
            height="520"
            style="border:0;"
            allowfullscreen=""
            loading="lazy"
        ></iframe>

    </div>

</section>