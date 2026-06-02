# `seo.php` i `view.php` objasnjenje

Ovaj dokument objasnjava dva helper fajla:

- `model/helpers/seo.php`
- `core/view.php`

Ova dva fajla ne rade istu stvar, ali oba pomazu da projekat bude uredniji.

## 1. Sta radi `seo.php`

Fajl:

```php
model/helpers/seo.php
```

sluzi za SEO podatke i breadcrumbs.

SEO podaci su stvari koje se ubacuju u `<head>` dio stranice:

```html
<title>...</title>
<meta name="description" content="...">
<meta name="robots" content="...">
<link rel="canonical" href="...">
```

U projektu se ti podaci cuvaju u globalnom nizu:

```php
$_output
```

Primjer:

```php
$_output['meta_title']
$_output['meta_description']
$_output['meta_robots']
$_output['canonical']
$_output['breadcrumbs']
```

Layout kasnije procita te vrijednosti i ispise ih u HTML.

## 2. Zasto SEO helper postoji

Bez helpera bi svaki model morao rucno pisati:

```php
$_output['meta_title'] = '...';
$_output['meta_description'] = '...';
$_output['canonical'] = '...';
```

To bi se brzo ponavljalo.

Zato postoji funkcija:

```php
setSEO($type, $data = [])
```

Primjer:

```php
setSEO('product', [
    'name' => $product['name'],
    'description' => $product['description'],
    'category' => $product['category_label'],
    'category_slug' => $product['category_slug'],
    'url' => productUrl($product),
]);
```

To znaci:

> Model kaze koji tip stranice je u pitanju, a SEO helper popuni meta podatke.

## 3. Breadcrumbs

Breadcrumbs su mala navigacija koja pokazuje gdje se korisnik nalazi.

Primjer:

```text
Početna / In2TheShop / Proizvodi / Majice / Product Name
```

U `seo.php` postoji:

```php
function addBreadcrumb(string $label, ?string $url = null): void
```

Ako breadcrumb ima URL, prikazuje se kao link.

Ako nema URL, prikazuje se kao trenutna stranica.

Primjer:

```php
addBreadcrumb('Proizvodi', shopUrl());
addBreadcrumb($name);
```

Ovdje je `Proizvodi` link, a `$name` je trenutna stranica.

## 4. `addShopBreadcrumbs()`

Ova funkcija dodaje osnovne shop breadcrumbs:

```php
function addShopBreadcrumbs(): void
{
    addBreadcrumb('Početna', appUrl('in2theshop'));
    addBreadcrumb('In2TheShop', shopUrl());
}
```

Koristi se na shop stranicama da se isti pocetak breadcrumbsa ne ponavlja stalno.

## 5. `pageTitle()`

Funkcija:

```php
function pageTitle(string $page): string
```

vraca osnovni naslov za stranicu.

Primjer:

```php
pageTitle('login') // Login
pageTitle('news')  // Vijesti
```

Ako ne zna specifican page, koristi:

```php
ucfirst($page)
```

To je fallback.

## 6. `genderLabel()`

U bazi gender moze biti:

```text
male
female
unisex
```

Ali korisniku ne zelimo prikazati `male`, nego:

```text
Muško
Žensko
Unisex
```

Zato postoji:

```php
genderLabel($gender)
```

To je mali helper za prikaz vrijednosti iz baze u ljudski citljivom obliku.

## 7. `cleanMetaDescription()`

Meta description ne treba biti dug tekst sa HTML tagovima.

Zato funkcija:

```php
cleanMetaDescription($text)
```

radi:

- ukloni HTML tagove
- skrati tekst na 160 karaktera

Kod:

```php
return substr(strip_tags($text ?? ''), 0, 160);
```

Ovo je korisno za product opis.

## 8. `setSEO()`

Ovo je glavna funkcija.

```php
function setSEO(string $type, array $data = []): void
```

Prima:

- `$type` - tip stranice
- `$data` - dodatni podaci za tu stranicu

Primjeri tipova:

```text
shop
product
explore
cart
checkout
order_success
```

### Shop

Za shop landing:

```php
setSEO('shop');
```

Postavlja:

- title
- description
- canonical
- breadcrumbs

### Product

Za product stranicu koristi podatke proizvoda:

```php
setSEO('product', [
    'name' => $product['name'],
    'description' => $product['description'],
    'category' => $product['category_label'],
    'category_slug' => $product['category_slug'],
    'url' => productUrl($product),
]);
```

Product SEO title postaje:

```text
Product Name | Shop
```

Description dolazi iz opisa proizvoda.

Canonical treba biti clean product URL.

### Explore

Explore/shop lista mijenja title zavisno od filtera.

Primjeri:

```text
Shop
Majice | Shop
Majice - Muško | Shop
Pretraga: jakna | Shop
```

Ovo se radi kroz:

```php
$category
$gender
$search
$categoryLabel
```

### Cart i checkout

Cart i checkout dobijaju:

```php
$_output['meta_robots'] = 'noindex, nofollow';
```

To znaci:

> Ne zelimo da trazilice indeksiraju korpu i checkout.

Razlog:

- korpa je privatno/stanje korisnika
- checkout nije javni sadrzaj
- nema smisla da Google indeksira neciju korpu

### Order success

Order success ima:

```php
noindex, follow
```

Znaci:

- ne indeksiraj ovu stranicu
- ali smijes pratiti linkove sa nje

## 9. Canonical URL

Canonical govori koja je glavna verzija URL-a.

Ako postoji:

```text
/index.php?page=explore
/shop
```

canonical treba biti:

```text
/shop
```

U `seo.php` se zato sada koriste helperi:

```php
shopUrl()
productUrl()
appUrl('order')
appUrl('cart')
```

## 10. Gdje se SEO podaci ispisuju

U layoutu:

```php
views/layouts/nav.php
```

postoje:

```php
<title><?= e($_output['meta_title'] ?? 'Shop') ?></title>
<meta name="description" content="<?= e($_output['meta_description'] ?? '') ?>">
<meta name="robots" content="<?= e($_output['meta_robots'] ?? 'index, follow') ?>">
```

Ako postoji canonical:

```php
<link rel="canonical" href="<?= e($_output['canonical']) ?>">
```

## 11. Sta radi `view.php`

Fajl:

```php
core/view.php
```

sadrzi funkciju:

```php
view_path(string $name): string
```

Ova funkcija pretvara kratko ime viewa u pravu putanju fajla.

Primjer:

```php
view_path('shop/explore')
```

vrati:

```text
views/pages/shop/explore.php
```

## 12. Zasto postoji `view_path()`

Bez ove funkcije bi svuda morao pisati pune putanje:

```php
DIR_VIEW_PAGES . 'shop/explore.php'
DIR_VIEW_ADMIN . 'products-list.php'
DIR_VIEW_LAYOUTS . 'admin.php'
```

Sa helperom pises samo:

```php
$_output['view'] = 'shop/explore';
```

ili:

```php
$_output['view'] = 'admin/products-list';
```

Layout kasnije sam zna gdje je fajl.

## 13. Kako `view_path()` odlucuje putanju

Funkcija prvo ocisti ime:

```php
$name = trim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $name), DIRECTORY_SEPARATOR);
```

To znaci:

- prihvata `/` i `\`
- prilagodjava putanju operativnom sistemu
- skida visak slash-eva sa pocetka/kraja

Zatim provjerava tip viewa.

## 14. Layout view

Ako ime pocinje sa:

```text
layouts/
```

onda ide u:

```text
views/layouts/
```

Primjer:

```php
view_path('layouts/admin')
```

vraca:

```text
views/layouts/admin.php
```

## 15. Admin view

Ako ime pocinje sa:

```text
admin/
```

onda ide u:

```text
views/admin/
```

Primjer:

```php
view_path('admin/products-list')
```

vraca:

```text
views/admin/products-list.php
```

## 16. Partial view

Ako ime pocinje sa:

```text
partials/
```

onda ide u:

```text
views/partials/
```

Primjer:

```php
view_path('partials/news-latest')
```

vraca:

```text
views/partials/news-latest.php
```

## 17. Public page view

Ako nije layout, admin ili partial, onda ide u:

```text
views/pages/
```

Primjer:

```php
view_path('site/home')
```

vraca:

```text
views/pages/site/home.php
```

Primjer:

```php
view_path('errors/404')
```

vraca:

```text
views/pages/errors/404.php
```

## 18. Kako se view ukljucuje u layoutu

U:

```php
views/layouts/body.php
```

postoji logika:

```php
if (!empty($_output['layout'])) {
    $__viewFile = view_path('layouts/' . $_output['layout']);
} elseif (!empty($_output['view'])) {
    $__viewFile = view_path($_output['view']);
}
```

Ako model postavi:

```php
$_output['layout'] = 'admin';
```

onda se ucitava:

```text
views/layouts/admin.php
```

Ako model postavi:

```php
$_output['view'] = 'shop/product';
```

onda se ucitava:

```text
views/pages/shop/product.php
```

## 19. Kako admin layout ucitava admin view

Admin model postavlja:

```php
$_output['layout'] = 'admin';
$_output['view'] = 'admin/dashboard';
```

Prvo `body.php` ucita:

```text
views/layouts/admin.php
```

Onda admin layout unutra ucita pravi admin view:

```php
$__viewFile = view_path($_output['view']);
include $__viewFile;
```

To znaci:

```text
admin layout je okvir
admin/dashboard je sadrzaj unutar okvira
```

## 20. Kratko objasnjenje za ispit

Mozes reci:

> `seo.php` koristim da centralizujem meta title, description, robots, canonical URL i breadcrumbs. Model samo pozove `setSEO()` sa tipom stranice, a helper popuni SEO podatke u `$_output`. `view.php` koristim da ne pisem rucno pune putanje do view fajlova. Funkcija `view_path()` primi kratko ime viewa, kao `shop/product` ili `admin/dashboard`, i vrati stvarnu putanju fajla koji treba include-ati.

## 21. Bitna napomena o encodingu

Ako u fajlu vidis tekst tipa:

```text
PoÄetna
MuÅ¡ko
NarudÅ¾ba
```

to znaci da je negdje doslo do encoding problema.

Ispravno treba biti:

```text
Početna
Muško
Narudžba
```

Fajlovi trebaju biti snimljeni kao UTF-8, a stranica vec ima:

```html
<meta charset="UTF-8">
```

Zato nije potrebno izbacivati kvačice ako je encoding ispravan.
