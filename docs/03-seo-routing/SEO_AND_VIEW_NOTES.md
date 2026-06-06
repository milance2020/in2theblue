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

### Dublje objasnjenje canonical taga

Canonical tag je HTML tag koji se stavlja u `<head>`:

```html
<link rel="canonical" href="https://example.com/shop">
```

Njegova ideja je:

```text
Ako se isti ili vrlo slican sadrzaj moze otvoriti preko vise URL-ova,
canonical govori trazilici koja adresa je glavna.
```

Primjer iz projekta:

```text
/v5/index.php?page=explore
/v5/shop
```

Obje adrese mogu prikazati shop listu proizvoda. Ali ne zelimo da Google misli da su to dvije posebne stranice sa istim sadrzajem. Zato canonical treba pokazivati na clean verziju:

```text
/v5/shop
```

Bitno je zapamtiti:

- canonical nije redirect
- canonical ne mijenja URL u browseru
- canonical je signal za trazilice
- 301 redirect je jace rjesenje ako zelis stvarno prebaciti stari URL na novi

U ovom projektu canonical se cuva u:

```php
$_output['canonical']
```

Primjeri:

```php
$_output['canonical'] = appUrl('in2theshop');
$_output['canonical'] = shopUrl();
$_output['canonical'] = productUrl($product);
$_output['canonical'] = appUrl('cart');
$_output['canonical'] = appUrl('order');
```

To znaci da model ili SEO helper pripremi URL, a layout ga kasnije ispise u `<head>`.

### Kada canonical posebno pomaze

Canonical je koristan kada imas:

- stari query URL i novi clean URL
- product stranicu koja moze imati fallback preko `id`
- shop listing sa filterima
- stranicu koja se moze otvoriti preko vise putanja

Primjer product stranice:

```text
/v5/index.php?page=product&id=15
/v5/shop/majice/basic-shirt
```

Bolje je da canonical bude clean product URL:

```text
/v5/shop/majice/basic-shirt
```

Tako trazilica zna da je to glavna verzija proizvoda.

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

### Sta tacno imamo u `<head>` dijelu

U `views/layouts/nav.php` se nalazi pocetak HTML dokumenta i `<head>` dio stranice.

Trenutno se koriste ovi elementi:

```html
<meta charset="UTF-8">
```

Ovo govori browseru da stranica koristi UTF-8 encoding. To je bitno zbog slova kao sto su `č`, `ć`, `š`, `đ`, `ž`.

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

Ovo je bitno za responsive dizajn. Bez ovoga mobilni browseri mogu prikazivati stranicu kao da je desktop stranica smanjena.

```html
<title>...</title>
```

Title je naslov stranice. Vidi se u tabu browsera i cesto se koristi kao naslov rezultata na Google-u.

U projektu dolazi iz:

```php
$_output['meta_title']
```

Ako nije postavljen, koristi se fallback:

```php
'Shop'
```

```html
<meta name="description" content="...">
```

Description je kratak opis stranice. Google ga ne mora uvijek prikazati, ali je dobar SEO signal i pomaze da rezultat izgleda normalno.

U projektu dolazi iz:

```php
$_output['meta_description']
```

Za product stranicu se description uzima iz opisa proizvoda, ali se ocisti funkcijom:

```php
cleanMetaDescription()
```

Ta funkcija uklanja HTML tagove i skracuje tekst.

```html
<meta name="robots" content="index, follow">
```

Robots meta tag govori trazilicama sta da rade sa stranicom.

U projektu dolazi iz:

```php
$_output['meta_robots']
```

Ako nije posebno postavljen, default je:

```text
index, follow
```

To znaci:

- `index` - smijes indeksirati ovu stranicu
- `follow` - smijes pratiti linkove sa ove stranice

Za privatne ili procesne stranice koristimo:

```text
noindex, nofollow
```

To znaci:

- `noindex` - ne prikazuj ovu stranicu u rezultatima pretrage
- `nofollow` - ne prati linkove sa ove stranice

U projektu se ovo koristi za:

```php
cart
checkout
```

Razlog je jednostavan: korpa i narucivanje nisu javni sadrzaj. Nema smisla da Google indeksira neciju korpu ili checkout proces.

Za order success se koristi:

```text
noindex, follow
```

To znaci:

- ne indeksiraj success stranicu
- ali linkove sa nje mozes pratiti

```html
<link rel="canonical" href="...">
```

Canonical pokazuje glavnu verziju trenutne stranice.

Ako `$_output['canonical']` nije prazan, layout ga ispise. Ako nije postavljen, canonical tag se ne ispisuje.

```html
<link rel="icon" ...>
<link rel="apple-touch-icon" ...>
```

Ovo su favicon tagovi. Oni nisu direktno SEO kao title ili description, ali su dio profesionalnog `<head>` setupa. Browser ih koristi za tab ikonu, bookmarke i mobilne prikaze.

### Kako `seo.php` odlucuje robots vrijednost

Na pocetku `setSEO()` funkcije postoji default:

```php
$_output['meta_robots'] = $data['robots'] ?? 'index, follow';
```

To znaci:

```text
Ako stranica ne kaze drugacije, smije se indeksirati.
```

Onda posebni tipovi stranica mogu pregaziti tu vrijednost.

Primjer za cart:

```php
$_output['meta_robots'] = 'noindex, nofollow';
```

Primjer za order success:

```php
$_output['meta_robots'] = 'noindex, follow';
```

Ovo je dobar princip:

```text
Public informativne stranice: index, follow
Privatne/procesne stranice: noindex
```

### Sta je implementirano po tipovima stranica

`shop`:

- title: `In2TheShop`
- description: osnovni opis shopa
- canonical: clean shop landing URL
- breadcrumbs: pocetna + shop

`explore`:

- title se mijenja po kategoriji, spolu ili pretrazi
- description opisuje kolekciju proizvoda
- canonical dolazi iz trenutnog clean URL-a ili fallbacka
- breadcrumbs prate filtere

`product`:

- title koristi ime proizvoda
- description koristi opis proizvoda
- canonical koristi clean product URL
- breadcrumbs vode kroz shop, proizvode, kategoriju i proizvod

`cart`:

- title: korpa
- robots: `noindex, nofollow`
- canonical: `/cart`

`checkout`:

- title: narucivanje
- robots: `noindex, nofollow`
- canonical: `/order`

`order_success`:

- title: uspjesna narudzba
- robots: `noindex, follow`
- breadcrumbs pokazuju da je korisnik zavrsio narudzbu

### Najkrace objasnjenje canonical + robots

Mozes zapamtiti ovako:

```text
Canonical rjesava pitanje: Koji URL je glavni?
Robots rjesava pitanje: Smije li trazilica indeksirati ovu stranicu?
Meta title i description rjesavaju pitanje: Kako stranica izgleda u rezultatima pretrage?
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

> `seo.php` koristim da centralizujem meta title, description, robots, canonical URL i breadcrumbs. Model samo pozove `setSEO()` sa tipom stranice, a helper popuni SEO podatke u `$_output`. Public stranice imaju `index, follow`, dok korpa i checkout imaju `noindex` jer nisu javni sadrzaj za Google. Canonical pokazuje koja je glavna clean URL verzija stranice. `view.php` koristim da ne pisem rucno pune putanje do view fajlova. Funkcija `view_path()` primi kratko ime viewa, kao `shop/product` ili `admin/dashboard`, i vrati stvarnu putanju fajla koji treba include-ati.

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
