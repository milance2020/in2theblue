<section class="shop-experience-section">

    <div class="experience-header">

        <span class="experience-tag">
            SEA • FOOD • GEAR • EXPERIENCE
        </span>

        <h2>
            Više od običnog shopa
        </h2>

        <p>
            In2TheShop nije zamišljen samo kao mjesto za kupovinu proizvoda,
            nego kao centralna tačka koja povezuje more, sport, opremu,
            lokalni bar i aktivan lifestyle.
        </p>

    </div>


    <div class="experience-grid">


        <!-- RENT A BIKE -->
        <article class="experience-card">

            <img
                src="<?= appUrl('assets/images/images_shop/bike.jpg') ?>"
                alt="Rent a Bike"
            >

            <div class="experience-content">

                <h3>Rent a Bike</h3>

                <p>
                    Istražite obalu, gradske ulice i skrivene lokacije uz naš
                    Rent a Bike sistem. Bicikli su dostupni za kratke gradske
                    vožnje, dnevne ture i istraživanje okoline.
                </p>

                <p>
                    Cilj sistema je povezati aktivan odmor sa lokalnim iskustvom,
                    uz jednostavno rezervisanje i mogućnost povezivanja sa
                    ponudama bara i shopa.
                </p>

                

            </div>

        </article>


        <!-- RENT A SUP -->
        <article class="experience-card">

            <img
                src="<?= appUrl('assets/images/images_shop/sup.jpg') ?>"
                alt="Rent a SUP"
            >

            <div class="experience-content">

                <h3>Rent a SUP</h3>

                <p>
                    More je centralni dio identiteta projekta, zbog čega je
                    planiran i SUP rental sistem za korisnike koji žele aktivno
                    iskustvo na vodi.
                </p>

                <p>
                    SUP ponuda bi bila povezana sa shop sekcijom kroz sportsku
                    opremu i premium brendove namijenjene aktivnostima na moru.
                </p>

                

            </div>

        </article>


        <!-- EAT YOUR BIKE -->
        <article class="experience-card featured">

            <img
                src="<?= appUrl('assets/images/images_shop/eat.jpg') ?>"
                alt="Eat Your Bike"
            >

            <div class="experience-content">

                <h3>Eat Your Bike</h3>

                <p>
                    “Eat Your Bike” koncept povezuje bar i bike rental sistem.
                    Korisnici koji u baru ostvare određenu potrošnju mogu dobiti
                    besplatno korištenje bicikla ili posebne pogodnosti.
                </p>

                <p>
                    Na taj način shop, bar i dodatne usluge postaju dio jednog
                    povezanog sistema iskustava umjesto odvojenih funkcionalnosti.
                </p>

                <a href="<?= appUrl('in2thebar') ?>">
                    Posjeti bar
                </a>

            </div>

        </article>


        <!-- BRANDS -->
        <article class="experience-card">

            <img
                src="<?= appUrl('assets/images/images_shop/shop.jpg') ?>"
                alt="Premium brands"
            >

            <div class="experience-content">

                <h3>Premium brendovi</h3>

                <p>
                    Shop je fokusiran na kvalitetne lifestyle i nautical brendove
                    poput Helly Hansen i Zhik, sa proizvodima namijenjenim moru,
                    sportu i svakodnevnom aktivnom životu.
                </p>

                <p>
                    Dugoročna ideja projekta je razvijati curated ponudu opreme
                    i odjeće koja odgovara identitetu lokacije i zajednice oko nje.
                </p>

                <a href="<?= shopUrl() ?>">
                    Pogledaj proizvode
                </a>

            </div>

        </article>

    </div>

</section>
<section class="featured-products-section">

    <div class="featured-products-header">
        <span class="section-tag">
            PREPORUČENO
        </span>

        <h2>
            Oprema za more i aktivan lifestyle
        </h2>

        <p>
            Odabrani proizvodi za svakodnevno nošenje, boravak uz more
            i aktivnosti na otvorenom.
        </p>
    </div>

    <div class="featured-products-grid">

        <?php while ($product = $featuredProducts->fetch_assoc()): ?>

            <article class="featured-product-card">

                <img
                    src="<?= appUrl($product['image_path']) ?>"
                    alt="<?= htmlspecialchars($product['name']) ?>"
                >

                <div class="featured-product-content">

                    <span>
                        <?= htmlspecialchars($product['category_label']) ?>
                    </span>

                    <h3>
                        <?= htmlspecialchars($product['name']) ?>
                    </h3>

                    <p>
                        <?= number_format($product['price'], 2) ?> €
                    </p>

                    <a href="<?= productUrl($product) ?>">
                        Pogledaj proizvod
                    </a>

                </div>

            </article>

        <?php endwhile; ?>

    </div>

</section>

<section class="bar-promo-section">

    <div class="bar-promo-overlay"></div>

    <div class="bar-promo-content">

        <span class="bar-promo-tag">
            IN2THEBAR
        </span>

        <h2>
            Kokteli, More i Večeri Uz Obalu
        </h2>

        <p>
            Opuštena atmosfera, lokalna hrana, glazba i zalasci sunca
            stvaraju mjesto gdje se shop i obalni lifestyle spajaju
            u jedno iskustvo.
        </p>

        <a href="<?= appUrl('in2thebar') ?>" class="bar-promo-btn">
            Posjeti Bar
        </a>

    </div>

</section>
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