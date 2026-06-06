<?php

function footerContentDefaults(): array
{
    return [
        'footer_brand_title' => 'IN2',
        'footer_brand_text' => 'IN2 spaja atmosferu bara, udoban smještaj i moderan shop na jednom mjestu. Mjesto za druženje, odmor i stil.',
        'footer_navigation_title' => 'Navigacija',
        'footer_contact_title' => 'Kontakt',
        'footer_address' => 'Ulica 123, Punta',
        'footer_phone' => '+385 91 123 4567',
        'footer_email' => 'info@in2.hr',
        'footer_social_title' => 'Pratite nas',
        'footer_instagram_label' => 'Instagram',
        'footer_instagram_url' => '#',
        'footer_facebook_label' => 'Facebook',
        'footer_facebook_url' => '#',
        'footer_tiktok_label' => 'TikTok',
        'footer_tiktok_url' => '#',
        'footer_hours_title' => 'Radno vrijeme',
        'footer_hours_text' => 'Pon - Ned: 08:00 - 02:00',
        'footer_bottom_text' => '© 2025 IN2. Sva prava pridržana.',
    ];
}

function homeHeroContentDefaults(): array
{
    return [
        'home_hero_tag' => 'SUNSET - KOKTELI - GLAZBA',
        'home_hero_title' => 'Mjesto gdje ljeto ostaje duže',
        'home_hero_text' => 'Kokteli, lokalna hrana, zalasci sunca i opuštena atmosfera inspirirana morem i mediteranskim načinom života.',
        'home_hero_primary_label' => 'Istražite Bar',
        'home_hero_secondary_label' => 'Posjeti Shop',
    ];
}

function shopHeroContentDefaults(): array
{
    return [
        'shop_hero_tag' => 'MORE - OPREMA - LIFESTYLE',
        'shop_hero_title' => 'Istražite obalni lifestyle',
        'shop_hero_text' => 'Premium nautička oprema, aktivan život uz more, bike i SUP rental te proizvodi inspirirani morem i avanturom.',
        'shop_hero_primary_label' => 'Istražite Shop',
        'shop_hero_secondary_label' => 'Posjeti Bar',
    ];
}

function contactContentDefaults(): array
{
    return [
        'contact_hero_title' => 'Kontakt',
        'contact_hero_text' => 'Imate pitanje za IN2THEBAR ili IN2THESHOP? Javite nam se putem forme ili direktno preko kontakt podataka.',
        'contact_info_title' => 'IN2 Kontakt',
        'contact_address' => 'Ulica 123, Punat, Hrvatska',
        'contact_phone' => '+385 91 123 4567',
        'contact_email' => 'info@in2.hr',
        'contact_working_hours' => 'Pon - Ned: 08:00 - 02:00',
    ];
}

function footerContentTitles(): array
{
    return [
        'footer_brand_title' => 'Brand naziv',
        'footer_brand_text' => 'Brand opis',
        'footer_navigation_title' => 'Naslov navigacije',
        'footer_contact_title' => 'Naslov kontakta',
        'footer_address' => 'Adresa',
        'footer_phone' => 'Telefon',
        'footer_email' => 'Email',
        'footer_social_title' => 'Naslov društvenih mreža',
        'footer_instagram_label' => 'Instagram tekst',
        'footer_instagram_url' => 'Instagram link',
        'footer_facebook_label' => 'Facebook tekst',
        'footer_facebook_url' => 'Facebook link',
        'footer_tiktok_label' => 'TikTok tekst',
        'footer_tiktok_url' => 'TikTok link',
        'footer_hours_title' => 'Naslov radnog vremena',
        'footer_hours_text' => 'Radno vrijeme',
        'footer_bottom_text' => 'Copyright tekst',
    ];
}

function homeHeroContentTitles(): array
{
    return [
        'home_hero_tag' => 'Bar hero oznaka',
        'home_hero_title' => 'Bar hero naslov',
        'home_hero_text' => 'Bar hero tekst',
        'home_hero_primary_label' => 'Tekst prvog dugmeta',
        'home_hero_secondary_label' => 'Tekst drugog dugmeta',
    ];
}

function shopHeroContentTitles(): array
{
    return [
        'shop_hero_tag' => 'Shop hero oznaka',
        'shop_hero_title' => 'Shop hero naslov',
        'shop_hero_text' => 'Shop hero tekst',
        'shop_hero_primary_label' => 'Tekst prvog dugmeta',
        'shop_hero_secondary_label' => 'Tekst drugog dugmeta',
    ];
}

function contactContentTitles(): array
{
    return [
        'contact_hero_title' => 'Kontakt naslov',
        'contact_hero_text' => 'Kontakt uvodni tekst',
        'contact_info_title' => 'Naslov kontakt kartice',
        'contact_address' => 'Adresa',
        'contact_phone' => 'Telefon',
        'contact_email' => 'Email',
        'contact_working_hours' => 'Radno vrijeme',
    ];
}

function editableContentDefaults(): array
{
    // Sve sekcije koje se mogu mijenjati iz admin panela.
    return footerContentDefaults()
        + homeHeroContentDefaults()
        + shopHeroContentDefaults()
        + contactContentDefaults();
}

function editableContentTitles(): array
{
    return footerContentTitles()
        + homeHeroContentTitles()
        + shopHeroContentTitles()
        + contactContentTitles();
}

function loadSiteContent(mysqli $conn, array $defaults): array
{
    // Ako tabela ili red ne postoje, koristimo default tekstove.
    $content = $defaults;

    $result = $conn->query("
        SELECT content_key, content
        FROM site_content
    ");

    if (!$result) {
        return $content;
    }

    while ($row = $result->fetch_assoc()) {
        // Uzimamo samo kljuceve koje aplikacija poznaje.
        if (array_key_exists($row['content_key'], $content)) {
            $content[$row['content_key']] = $row['content'];
        }
    }

    return $content;
}

function loadFooterContent(mysqli $conn): array
{
    return loadSiteContent($conn, footerContentDefaults());
}

function loadHomeHeroContent(mysqli $conn): array
{
    return loadSiteContent($conn, homeHeroContentDefaults());
}

function loadShopHeroContent(mysqli $conn): array
{
    return loadSiteContent($conn, shopHeroContentDefaults());
}

function loadContactContent(mysqli $conn): array
{
    return loadSiteContent($conn, contactContentDefaults());
}

function loadEditableContent(mysqli $conn): array
{
    return loadSiteContent($conn, editableContentDefaults());
}
