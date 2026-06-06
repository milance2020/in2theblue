# Infrastruktura projekta

Ovaj dokument objasnjava kako je projekat organizovan, kako request prolazi kroz aplikaciju i koja je uloga glavnih foldera/fajlova.

Ideja nije da projekat bude "full framework", nego da ima dovoljno jasnu strukturu da se moze odrzavati, objasniti i braniti na ispitu.

## 1. Glavna ideja arhitekture

Projekat je napravljen kao jednostavna PHP aplikacija sa rucno slozenom MVC logikom.

To znaci:

```text
Model priprema podatke i logiku.
View prikazuje HTML.
Controller odlucuje koji model treba ucitati.
Layout spaja zajednicke dijelove stranice.
```

Nije Laravel ili neki veliki framework, ali ima slican osnovni princip.

## 2. Glavna ulazna tacka

Glavni fajl je:

```text
index.php
```

Skoro svaki normalan request prolazi kroz `index.php`.

On radi nekoliko stvari:

1. ucita router
2. procita request parametre
3. pripremi `$_output`
4. pokrene session
5. ucita constants, security, URL helper i view helper
6. ucita public controller
7. ukljuci odgovarajuci model
8. renderuje layout

Najkrace:

```text
index.php je centralni ulaz u aplikaciju.
```

## 3. Request flow

Pojednostavljen tok requesta:

```text
Korisnik otvori URL
        |
        v
index.php
        |
        v
core/router.php
        |
        v
controlers/publicController.php
        |
        v
model/model-*.php
        |
        v
views/layouts/nav.php
views/layouts/header.php
views/layouts/body.php
views/layouts/footer.php
        |
        v
HTML odgovor korisniku
```

Primjer:

```text
/shop
```

Router ga prevede u:

```php
$_GET['page'] = 'explore';
```

Controller zatim napravi model putanju:

```text
model/model-explore.php
```

Model pripremi podatke, a layout ih prikaze.

## 4. `core/router.php`

Router sluzi za clean/nice URL-ove.

Primjeri:

```text
/shop
/cart
/order
/news/15-naslov-vijesti
/shop/kategorija/proizvod
```

Router ove URL-ove pretvara u interne `$_GET` vrijednosti.

Primjer:

```text
/shop/majice/basic-shirt
```

postaje:

```php
$_GET['page'] = 'product';
$_GET['category_slug'] = 'majice';
$_GET['product_slug'] = 'basic-shirt';
```

Prednost:

```text
Korisnik vidi lijep URL, a aplikacija interno i dalje koristi jednostavne page parametre.
```

## 5. `controlers/publicController.php`

Controller je bijela lista stranica koje smiju biti ucitane.

U njemu se provjerava vrijednost:

```php
$_page
```

Ako je stranica dozvoljena, priprema se ime modela.

Primjer:

```php
$_page = 'explore';
```

daje:

```text
model/model-explore.php
```

Ako stranica nije poznata, ide na error/404 flow.

Ovo je bitno jer ne zelis da korisnik kroz URL moze ucitati bilo koji fajl.

## 6. `core/constants.php`

Ovdje su definisane glavne putanje i URL konstante.

Primjeri folder konstanti:

```php
DIR_ROOT
DIR_CORE
DIR_MODEL
DIR_VIEWS
DIR_ASSETS
DIR_MODEL_HELPERS
```

Primjeri fajl konstanti:

```php
FILE_SECURITY_HELPER
FILE_URL_HELPER
FILE_PUBLIC_CONTROLLER
FILE_LAYOUT_NAV
FILE_LAYOUT_BODY
```

Primjeri URL konstanti:

```php
URL_BASE
URL_ASSETS
URL_API_CART
```

Prednost:

```text
Ne pises rucno iste putanje po cijelom projektu.
Ako se promijeni osnovni folder, lakse je prilagoditi projekat.
```

## 7. `$_output` kao zajednicko stanje za render

U `index.php` postoji niz:

```php
$_output = [
    'view' => '',
    'layout' => '',
    'errors' => [],
    'messages' => [],
    'data' => [],
    'cart_count' => 0,
    'meta_title' => '',
    'meta_description' => '',
    'breadcrumbs' => [],
    'canonical' => '',
];
```

Modeli pune ovaj niz.

Layout i view fajlovi ga kasnije citaju.

Primjer:

```php
$_output['view'] = 'shop/explore';
$_output['meta_title'] = 'Shop';
$_output['data']['products'] = $products;
```

Ovo je jednostavan nacin da model prebaci podatke do viewa.

## 8. Model folder

Folder:

```text
model/
```

sadrzi glavne modele za public stranice.

Primjeri:

```text
model-index.php
model-shop.php
model-explore.php
model-product.php
model-news.php
model-order.php
model-cart-checkout.php
model-login.php
model-register.php
```

Uloga modela:

- provjeri request
- ucita podatke iz baze
- pozove helper funkcije
- postavi SEO
- odredi koji view se prikazuje
- pripremi `$_output['data']`

Model ne bi trebao biti zaduzen da pravi kompletan HTML. To je posao viewa.

## 9. Admin model folder

Folder:

```text
model/admin/
```

sadrzi admin akcije i admin logiku.

Primjeri:

```text
insert_news.php
delete_news.php
content_edit.php
update_content.php
dashboard.php
```

Admin dio ima posebnu ulogu:

- upravljanje proizvodima
- upravljanje vijestima
- pregled narudzbi
- uredjivanje sadrzaja
- role admin/moderator

Ovo je odvojeno od public modela jer admin panel nije isto sto i javni dio sajta.

## 10. API folder

Folder:

```text
api/
```

sadrzi endpoint fajlove koje JavaScript moze pozivati bez reloadanja cijele stranice.

Primjer:

```text
api/cart.php
```

Cart JS salje request prema API endpointu, a API vraca JSON odgovor.

To je korisno jer:

- korpa moze raditi dinamicno
- nije potreban reload za svaku akciju
- PHP i JavaScript dijele posao

## 11. `model/api/`

Folder:

```text
model/api/
```

sadrzi API model logiku.

Ideja:

```text
api/cart.php je endpoint.
model/api/api-cart.php radi stvarnu logiku.
```

Ovo je bolje nego da sav API kod stoji direktno u endpoint fajlu.

## 12. Helperi

Folder:

```text
model/helpers/
```

sadrzi pomocne funkcije koje se koriste na vise mjesta.

Primjeri:

```text
seo.php
urlHelper.php
cart.php
product-functions.php
siteContent.php
reportService.php
commentModeration.php
```

Helperi postoje da se logika ne ponavlja.

Primjer:

```php
productUrl($product)
```

se koristi gdje god treba napraviti link proizvoda.

Da toga nema, svuda bi se rucno pisali URL-ovi.

## 13. Security helper

Fajl:

```text
core/security.php
```

sadrzi sigurnosne pomocne funkcije.

Tu spadaju stvari kao:

- escaping outputa
- CSRF tokeni
- provjera CSRF tokena
- flash poruke
- provjera rola
- redirect helperi

Ovaj fajl je bitan jer se sigurnosna logika drzi centralno.

Primjer:

```php
e($value)
```

koristi se za siguran ispis teksta u HTML.

## 14. View folder

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

Ovo je dobro jer se public stranice, admin stranice, layouti i mali reusable dijelovi ne mijesaju.

## 15. Layouti

Folder:

```text
views/layouts/
```

sadrzi zajednicke dijelove stranice:

```text
nav.php
header.php
body.php
footer.php
admin.php
```

Render ide redom:

```php
include FILE_LAYOUT_NAV;
include FILE_LAYOUT_HEADER;
include FILE_LAYOUT_BODY;
include FILE_LAYOUT_FOOTER;
```

Prednost:

```text
Ne moras ponavljati navigaciju, head i footer u svakoj stranici.
```

## 16. `core/view.php`

Fajl:

```text
core/view.php
```

ima funkciju:

```php
view_path()
```

Ona pretvara kratko ime viewa u stvarnu putanju.

Primjer:

```php
view_path('shop/product')
```

vrati:

```text
views/pages/shop/product.php
```

Za admin:

```php
view_path('admin/dashboard')
```

vrati:

```text
views/admin/dashboard.php
```

Ovo cini render urednijim.

## 17. Public pages

Folder:

```text
views/pages/
```

sadrzi public stranice.

Primjeri:

```text
views/pages/site/home.php
views/pages/shop/explore.php
views/pages/shop/product.php
views/pages/shop/order.php
views/pages/errors/404.php
```

Public stranice su ono sto obican korisnik vidi.

## 18. Admin views

Folder:

```text
views/admin/
```

sadrzi admin panel stranice.

Admin layout je okvir, a admin view je konkretan sadrzaj.

Primjer:

```text
views/layouts/admin.php
views/admin/dashboard.php
```

To znaci:

```text
admin.php pravi sidebar/header okvir,
dashboard.php je sadrzaj unutar admin panela.
```

## 19. Partials

Folder:

```text
views/partials/
```

sadrzi manje komade viewa koji se mogu ubaciti na vise mjesta.

Primjer:

```text
news-latest.php
```

Partial je koristan kada ne zelis kopirati isti HTML na vise stranica.

## 20. Assets folder

Folder:

```text
assets/
```

sadrzi staticke fajlove:

```text
css/
js/
images/
```

Ovo su fajlovi koje browser direktno ucitava.

## 21. CSS infrastruktura

CSS je podijeljen na public i admin dio:

```text
assets/css/public/
assets/css/admin/
```

Public CSS dalje ima:

```text
base/
components/
layout/
pages/
shop/
```

Admin CSS ima:

```text
components/
layout/
pages/
```

Prednost:

```text
Lakše je naci stil za odredjeni dio stranice.
Manje se ponavljaju iste klase.
Public i admin stilovi nisu izmijesani.
```

## 22. JavaScript infrastruktura

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

Cart sistem je posebno u:

```text
assets/js/shop/shop_god/
```

Primjeri:

```text
cart.service.js
cart.ui.js
checkout.js
```

Ideja:

- service fajl komunicira sa API-em
- UI fajl osvjezava prikaz korpe
- checkout fajl vodi logiku narucivanja

## 23. Images infrastruktura

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

Ova podjela je korisna jer shop slike, bar slike, news slike i faviconi nisu u istom folderu.

## 24. Baza i konekcija

Konekcija na bazu je u:

```text
model/admin/includes/connect.php
```

Modeli i admin akcije koriste konekciju kada trebaju raditi sa bazom.

Za produkciju bi bilo bolje kasnije premjestiti osjetljive podatke u `.env` ili config fajl koji nije javno dostupan.

## 25. Role sistem

Projekat ima role:

```text
admin
moderator
user
```

Admin i moderator imaju pristup admin panelu, ali ne moraju imati iste dozvole.

Osnovna ideja:

```text
Moderator uredjuje sadrzaj, prati narudzbe i poruke.
Admin ima sve to plus dodatne upravljacke funkcije.
```

Role sistem je dio infrastrukture jer odredjuje ko smije koristiti koje dijelove aplikacije.

## 26. Flash poruke

Flash poruke su kratke poruke koje se prikazuju nakon akcije.

Primjeri:

```text
Proizvod je uspjesno dodan.
Narudzba je sacuvana.
Nemate dozvolu za ovu akciju.
```

One se obicno cuvaju u sessionu i prikazu poslije redirecta.

To je korisno jer korisnik dobije povratnu informaciju nakon forme ili akcije.

## 27. Content edit infrastruktura

Projekat ima editable content dio.

To znaci da neki tekstovi na stranici ne moraju biti hardkodirani u viewu, nego se mogu uredjivati iz admin panela.

Primjeri:

- footer sadrzaj
- bar hero tekst
- shop hero tekst
- contact page tekst

Helper:

```text
model/helpers/siteContent.php
```

sluzi da se taj sadrzaj ucita i pripremi.

## 28. Kako objasniti infrastrukturu na odbrani

Mozes reci:

> Projekat ima centralnu ulaznu tacku `index.php`. Request prvo prolazi kroz router, koji clean URL pretvara u interne `page` parametre. Zatim controller preko bijele liste odlucuje koji model smije biti ucitan. Model priprema podatke, SEO informacije i view koji treba prikazati. Layout fajlovi zatim renderuju navigaciju, header, body i footer. Public i admin dijelovi su odvojeni kroz modele, view fajlove i CSS/JS strukturu. Helperi se koriste da se zajednicka logika kao URL-ovi, SEO, sigurnost, cart i editable content ne ponavlja po projektu.

## 29. Sta je dobro u ovoj infrastrukturi

Dobro je sto:

- postoji centralni `index.php`
- router podrzava clean URL-ove
- controller koristi bijelu listu stranica
- modeli su odvojeni po funkciji
- view fajlovi su odvojeni od logike
- layout sprjecava ponavljanje nav/header/footer koda
- helperi cuvaju zajednicku logiku
- admin i public dio su razdvojeni
- CSS i JS su podijeljeni po oblasti
- postoji API za cart

## 30. Sta bi bili napredni koraci

Ako bi se projekat dalje razvijao, dobri koraci bi bili:

1. Prebaciti config podatke u `.env`
2. Uvesti jednostavniji autoload umjesto puno `require_once`
3. Napraviti jasniji service sloj za narudzbe, proizvode i korisnike
4. Uvesti migracije za bazu
5. Odvojiti admin rute od public ruta
6. Dodati 301 redirect za stare public URL-ove
7. Dodati sitemap i robots.txt
8. Dodati vise automatskih testova
9. Napraviti bolji error logging za produkciju

## 31. Kratka verzija

Najkrace:

```text
index.php vodi aplikaciju.
router sredjuje URL.
controller bira model.
model priprema podatke.
view prikazuje HTML.
layout spaja stranicu.
helperi cuvaju zajednicku logiku.
assets drze CSS, JS i slike.
api folder sluzi za AJAX/JSON funkcije.
```

To je infrastruktura projekta.
