# Prolazak kroz projekat

Ovaj dokument je zamisljen kao vodic kroz cijeli projekat. Cilj je da mozes otvoriti projekat, ici folder po folder, i znati sta se gdje nalazi i kako sve radi zajedno.

Nije duboka tehnicka dokumentacija za svaku funkciju, nego pregled koji pomaze da projekat razumijes kao jednu cjelinu.

## 1. Glavni folderi

U rootu projekta nalaze se glavne cjeline:

```text
api/
assets/
controlers/
core/
model/
views/
index.php
```

Najkrace:

```text
index.php    - ulaz u aplikaciju
core/        - osnovne sistemske stvari
controlers/  - odlucuje koji model se ucitava
model/       - logika, baza, priprema podataka
views/       - HTML prikaz
assets/      - CSS, JS, slike, PDF
api/         - endpointi za JavaScript
```

## 2. `index.php`

`index.php` je centralna tacka projekta.

Kada korisnik otvori stranicu, aplikacija krece odavde.

On radi:

- ucitava router
- uzima `page`, `action`, `id`, `view` iz requesta
- priprema `$_output`
- pokrece session
- ucitava konstante i helpere
- ucitava controller
- ucitava model
- renderuje layout

Bitna ideja:

```text
index.php ne radi svu logiku sam.
On povezuje dijelove aplikacije.
```

## 3. `core/`

Folder:

```text
core/
```

sadrzi osnovne fajlove bez kojih aplikacija ne radi.

Glavni fajlovi:

```text
constants.php
router.php
security.php
view.php
```

### `constants.php`

Ovdje su definisane putanje i URL konstante.

Primjeri:

```php
DIR_MODEL
DIR_VIEWS
DIR_ASSETS
FILE_LAYOUT_NAV
FILE_SECURITY_HELPER
URL_BASE
URL_API_CART
```

Zasto je korisno:

```text
Ne pisemo iste putanje rucno po cijelom projektu.
```

### `router.php`

Router pretvara clean URL u interne `$_GET` vrijednosti.

Primjer:

```text
/shop
```

postaje:

```php
$_GET['page'] = 'explore';
```

Primjer product URL-a:

```text
/shop/majice/basic-shirt
```

postaje:

```php
$_GET['page'] = 'product';
$_GET['category_slug'] = 'majice';
$_GET['product_slug'] = 'basic-shirt';
```

### `security.php`

Ovdje su sigurnosne pomocne funkcije.

Tu spadaju:

- siguran HTML ispis
- CSRF tokeni
- provjera CSRF-a
- flash poruke
- provjera rola
- redirect helperi

Najvaznija ideja:

```text
Sigurnosna logika se ne ponavlja u svakom fajlu, nego stoji centralno.
```

### `view.php`

Ovdje je helper za pronalazak view fajla.

Primjer:

```php
view_path('shop/product')
```

vrati:

```text
views/pages/shop/product.php
```

Ovo pomaze da ne pisemo pune putanje rucno.

## 4. `controlers/`

Folder:

```text
controlers/
```

Trenutno je glavni fajl:

```text
publicController.php
```

On radi kao bijela lista dozvoljenih stranica.

Ako je:

```php
$_page = 'news';
```

controller ucitava:

```text
model/model-news.php
```

Ako stranica nije poznata, ide na error/404.

Zasto je ovo dobro:

```text
Korisnik ne moze kroz URL slobodno birati bilo koji fajl iz projekta.
```

## 5. `model/`

Folder:

```text
model/
```

sadrzi logiku projekta.

Tu su public modeli:

```text
model-index.php
model-shop.php
model-explore.php
model-product.php
model-news.php
model-contact.php
model-order.php
model-order-success.php
model-cart-checkout.php
model-login.php
model-register.php
model-adminPanel.php
```

Model obicno radi:

- provjeri podatke iz requesta
- ucita podatke iz baze
- pozove helper funkcije
- postavi SEO podatke
- postavi `$_output['view']`
- ubaci podatke u `$_output['data']`

Primjer logike:

```text
model-product.php nadje proizvod,
postavi SEO za proizvod,
i kaze layoutu da prikaze shop/product view.
```

## 6. `model/helpers/`

Folder:

```text
model/helpers/
```

sadrzi pomocne funkcije koje koristi vise dijelova projekta.

Glavni helperi:

```text
urlHelper.php
seo.php
cart.php
product-functions.php
siteContent.php
commentModeration.php
reportService.php
```

### `urlHelper.php`

Pravi URL-ove.

Primjeri:

```php
appUrl()
shopUrl()
productUrl()
newsUrl()
assetUrl()
```

Ovo je bitno zbog nice URL-ova.

### `seo.php`

Postavlja:

- meta title
- meta description
- robots
- canonical
- breadcrumbs

Model pozove:

```php
setSEO('product', $data);
```

a helper pripremi SEO podatke.

### `cart.php`

Pomaze oko korpe.

Korpa je vezana za session i API.

### `siteContent.php`

Pomaze oko editabilnog sadrzaja.

Tu spada:

- footer tekstovi
- bar hero tekst
- shop hero tekst
- contact tekst

## 7. `model/admin/`

Folder:

```text
model/admin/
```

sadrzi admin akcije.

Primjeri:

```text
dashboard.php
view_orders.php
view_products.php
view_news.php
content_edit.php
update_content.php
insert_news.php
delete_news.php
update_products.php
```

Admin dio se koristi za:

- dashboard
- proizvode
- vijesti
- narudzbe
- poruke
- komentare
- editovanje sadrzaja

Ovo je odvojeno od public modela jer admin panel ima drugaciju logiku i druge dozvole.

## 8. `model/api/`

Folder:

```text
model/api/
```

sadrzi logiku za API.

Primjer:

```text
api-cart.php
```

Tu se obradjuje cart API logika, dok je endpoint u `api/cart.php`.

## 9. `api/`

Folder:

```text
api/
```

sadrzi fajlove koje JavaScript poziva.

Glavni primjer:

```text
api/cart.php
```

Ovaj fajl je endpoint.

Znaci:

```text
JavaScript posalje request -> api/cart.php -> PHP obradi -> vrati JSON.
```

Ovo se koristi da korpa radi dinamicno, bez stalnog reloadanja stranice.

## 10. `views/`

Folder:

```text
views/
```

sadrzi HTML prikaz.

Podjela:

```text
views/layouts/
views/pages/
views/admin/
views/partials/
```

## 11. `views/layouts/`

Layout fajlovi su zajednicki dijelovi stranice.

Primjeri:

```text
nav.php
header.php
body.php
footer.php
admin.php
```

Render ide redom:

```text
nav -> header -> body -> footer
```

`nav.php` sadrzi:

- pocetak HTML dokumenta
- `<head>`
- meta tagove
- CSS linkove
- navigaciju

`body.php` ukljucuje pravi view.

`footer.php` zatvara stranicu i prikazuje footer.

`admin.php` je layout za admin panel.

## 12. `views/pages/`

Ovdje su public stranice.

Podjela:

```text
views/pages/site/
views/pages/shop/
views/pages/errors/
```

### `views/pages/site/`

Sadrzi opste public stranice:

```text
home.php
shop.php
news.php
contact.php
login.php
register.php
rooms.php
```

### `views/pages/shop/`

Sadrzi shop stranice:

```text
explore.php
product.php
cart.php
order.php
order-success.php
```

### `views/pages/errors/`

Sadrzi error stranice:

```text
403.php
404.php
```

## 13. `views/admin/`

Ovdje su admin panel view fajlovi.

Primjeri:

```text
dashboard.php
orders-list.php
order-detail.php
products-list.php
product-insert.php
news-list.php
news-insert.php
content-edit.php
comments.php
messages.php
```

Ovi fajlovi se prikazuju unutar admin layouta.

## 14. `views/partials/`

Partial je mali komad HTML-a koji se moze ubaciti na vise mjesta.

Primjer:

```text
news-latest.php
```

To je korisno kada ne zelis kopirati isti blok na vise stranica.

## 15. `assets/`

Folder:

```text
assets/
```

sadrzi sve sto browser ucitava kao staticki fajl:

```text
css/
js/
images/
```

## 16. CSS

CSS je u:

```text
assets/css/
```

Podjela:

```text
assets/css/public/
assets/css/admin/
```

Public CSS:

```text
base/
components/
layout/
pages/
shop/
```

Admin CSS:

```text
components/
layout/
pages/
```

Ovo znaci da stilovi nisu svi nagurani u jedan fajl. Lakše je naci gdje se sta mijenja.

## 17. JavaScript

JS je u:

```text
assets/js/
```

Podjela:

```text
admin/
layout/
shop/
sliders/
```

Vazni shop JS fajlovi:

```text
cart.service.js
cart.ui.js
checkout.js
product.js
explore.js
comments.js
```

Ideja:

- `cart.service.js` komunicira sa API-em
- `cart.ui.js` prikazuje stanje korpe
- `checkout.js` vodi checkout formu
- `comments.js` radi komentare

## 18. Slike i media fajlovi

Slike su u:

```text
assets/images/
```

Podjela:

```text
favicon/
images_bar/
images_news/
images_rooms/
images_shop/
```

Shop slike proizvoda su u:

```text
assets/images/images_shop/products/
```

Bar slike su u:

```text
assets/images/images_bar/
```

Favicon je u:

```text
assets/images/favicon/
```

PDF meni je u:

```text
assets/images/images_bar/Menu2025.pdf
```

## 19. Cart sistem

Cart sistem radi kroz PHP session, API i JavaScript.

Tok:

```text
Korisnik klikne add to cart
        |
        v
JavaScript pozove api/cart.php
        |
        v
PHP promijeni session cart
        |
        v
API vrati JSON
        |
        v
JavaScript osvjezi prikaz korpe
```

To je dobar primjer kombinacije frontend i backend logike.

## 20. Checkout i narudzbe

Checkout/narudzba dio koristi:

```text
model/model-order.php
views/pages/shop/order.php
views/pages/shop/order-success.php
model/helpers/reportService.php
```

Korisnik popuni formu, PHP validira podatke i kreira narudzbu.

Nakon uspjeha ide na order success stranicu.

## 21. News sistem

News dio koristi:

```text
model/model-news.php
views/pages/site/news.php
views/partials/news-latest.php
model/model-news-latest.php
```

Admin dio za vijesti:

```text
model/admin/insert_news.php
model/admin/view_news.php
model/admin/update_News.php
model/admin/delete_news.php
views/admin/news-list.php
views/admin/news-insert.php
views/admin/news-update.php
```

Vijesti imaju public prikaz i admin upravljanje.

## 22. Content edit

Content edit dio omogucava da se neki tekstovi uredjuju iz admin panela.

Koristi:

```text
model/helpers/siteContent.php
model/admin/content_edit.php
model/admin/update_content.php
views/admin/content-edit.php
```

Trenutno se moze editovati:

- footer
- bar hero
- shop hero
- contact tekst

## 23. Role sistem

Admin panel razlikuje role.

Glavne role:

```text
admin
moderator
user
```

Moderator moze imati ogranicene admin funkcije, dok admin ima sire dozvole.

Ovo je bitno jer ne treba svaki korisnik imati isti pristup.

## 24. SEO dio

SEO se najvise nalazi u:

```text
model/helpers/seo.php
views/layouts/nav.php
model/helpers/urlHelper.php
```

SEO helper postavlja:

- title
- description
- robots
- canonical
- breadcrumbs

Layout to ispisuje u `<head>`.

URL helperi pomazu da canonical i linkovi koriste clean URL.

## 25. Security dio

Security se najvise nalazi u:

```text
core/security.php
```

Bitne stvari:

- `e()` za siguran HTML ispis
- CSRF tokeni
- provjera rola
- flash poruke
- redirect helperi

Ovo je vazno jer projekat ima forme, login, admin panel i akcije koje mijenjaju podatke.

## 26. Kako bih ja prezentovao projekat

Ako projekat trebas pokazati na odbrani, dobar redoslijed je:

1. Pokazati `index.php` kao centralni ulaz
2. Pokazati `router.php` i clean URL logiku
3. Pokazati `publicController.php` i bijelu listu stranica
4. Pokazati jedan model, npr. `model-product.php`
5. Pokazati odgovarajuci view, npr. `views/pages/shop/product.php`
6. Pokazati layout, posebno `nav.php` i `body.php`
7. Pokazati helpere: `seo.php`, `urlHelper.php`, `security.php`
8. Pokazati cart API i JS
9. Pokazati admin panel i role sistem
10. Pokazati dokumentaciju koju si pripremio

## 27. Sta je najjace u projektu

Najbolje stvari u projektu:

- nije vise sve u jednom fajlu
- public i admin dio su odvojeni
- URL-ovi su sredjeni
- SEO je centralizovan
- security helper postoji
- cart ima API + JS pristup
- content se moze editovati iz admin panela
- CSS i JS su bolje organizovani
- dokumentacija objasnjava sta je radjeno

## 28. Sta bi se moglo dalje srediti

Ako bi se nastavilo dalje, realni koraci su:

1. Dodati 301 redirect za stare public URL-ove
2. Dodati `robots.txt`
3. Dodati `sitemap.xml`
4. Premjestiti database config u sigurniji config ili `.env`
5. Uvesti jednostavan autoload
6. Dodati vise alt tekstova za slike
7. Dodati Open Graph tagove
8. Dodati structured data za proizvode
9. Dodati basic testove za helper funkcije

## 29. Kratka verzija

Najkrace:

```text
Projekat je organizovan kao jednostavna PHP MVC aplikacija.
index.php vodi request.
router sredjuje clean URL.
controller bira model.
model priprema podatke.
view prikazuje HTML.
layout spaja zajednicke dijelove.
helperi cuvaju zajednicku logiku.
assets folder drzi CSS, JS i slike.
api folder sluzi za JavaScript requeste.
admin panel je odvojen od public dijela.
```

To je osnovni prolazak kroz projekat.
