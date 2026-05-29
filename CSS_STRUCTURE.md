# CSS struktura projekta

CSS je razbijen na manje fajlove, ali su stari glavni fajlovi ostali isti.

To znaci da PHP i dalje ucitava:

```php
assets/css/public/style.css
assets/css/public/forms.css
assets/css/public/tables.css
assets/css/public/news.css
assets/css/public/shop.css
assets/css/admin/adminPanel.css
assets/css/admin/order_info.css
```

Ti fajlovi sada uglavnom samo uvoze manje fajlove preko `@import`.

## Public CSS

### `assets/css/public/style.css`

Glavni public fajl.

Uvozi:

- `base/reset.css` - osnovni reset, boje, varijable, body, img
- `components/buttons.css` - osnovni public button
- `layout/header-slider.css` - header i slider
- `layout/nav.css` - navigacija i mini cart dropdown
- `pages/bar-home.css` - pocetna/bar sekcije
- `layout/footer.css` - footer
- `pages/login.css` - login dio
- `components/breadcrumbs.css` - breadcrumbs
- `pages/contact.css` - kontakt stranica

## Forms, tables i news

Ovi fajlovi su ostali kao ulazne tacke:

- `forms.css`
- `tables.css`
- `news.css`

Ali stvarni kod je prebacen u:

- `components/forms.css`
- `components/tables.css`
- `pages/news.css`

## Shop CSS

### `assets/css/public/shop.css`

Shop je razbijen po funkcionalnostima:

- `shop/buttons.css`
- `shop/explore.css`
- `shop/product.css`
- `shop/comments.css`
- `shop/checkout.css`
- `shop/order-success.css`
- `shop/cart.css`
- `shop/landing-experience.css`
- `shop/hero.css`
- `shop/featured-products.css`
- `shop/bar-promo.css`
- `shop/responsive.css`

Ako uredjujes product stranicu, idi u:

```text
assets/css/public/shop/product.css
```

Ako uredjujes komentare:

```text
assets/css/public/shop/comments.css
```

Ako uredjujes korpu:

```text
assets/css/public/shop/cart.css
```

## Admin CSS

### `assets/css/admin/adminPanel.css`

Uvozi:

- `layout/admin-layout.css` - osnovni admin layout i sidebar
- `components/filter-links.css` - zajednicki filter linkovi za orders/messages
- `components/comment-moderation.css` - komentari u admin panelu
- `pages/messages.css` - admin poruke

### `assets/css/admin/order_info.css`

Uvozi:

- `components/filter-links.css`
- `pages/order-detail.css`

## Sta je smanjeno od ponavljanja

Boje, radiusi, shadowi i osnovni reset su sada u:

```text
assets/css/public/base/reset.css
```

Filter linkovi za order i message stranice su spojeni u:

```text
assets/css/admin/components/filter-links.css
```

Tako se isti stil ne mora pisati dva puta.

## Kako objasniti na ispitu

Mozes reci:

> Nisam mijenjao nacin na koji aplikacija ucitava CSS. Ostavio sam glavne CSS fajlove da se i dalje ucitavaju iz PHP-a, ali sam njihov sadrzaj razbio na manje fajlove po namjeni. Tako je lakse odrzavati kod: navigacija je u jednom fajlu, footer u drugom, shop product u trecem, admin komentari u posebnom itd. Dio ponavljanja sam prebacio u zajednicke fajlove, kao sto su reset, varijable i admin filter linkovi.
