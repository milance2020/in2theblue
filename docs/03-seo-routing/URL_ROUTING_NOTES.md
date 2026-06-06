# URL routing i nice URLs

Ovaj dokument objasnjava kako projekat koristi clean/nice URL-ove i zasto je bitno izbjegavati dvije adrese za istu stranicu.

## 1. Problem: dvije adrese za istu stranicu

Prije se ista stranica mogla otvoriti na dva nacina.

Primjer:

```text
/v5/index.php?page=explore
/v5/shop
```

Obje adrese mogu prikazati isti shop/explore page.

To nije idealno jer:

- korisnik vidi neuredne linkove
- SEO moze vidjeti dupliran sadrzaj
- kasnije je teze odrzavati linkove
- canonical URL moze biti pogresan

Bolji princip:

```text
Public dio sajta koristi nice URLs.
Interni query URL ostaje samo za routing/fallback/admin.
```

## 2. Sta je nice URL

Nice URL je citljiv URL bez `index.php?page=...`.

Primjeri:

```text
/v5/shop
/v5/cart
/v5/order
/v5/news/15
/v5/shop/category/product
/v5/contact
/v5/login
```

Umjesto:

```text
/v5/index.php?page=explore
/v5/index.php?page=cart-checkout
/v5/index.php?page=product&id=15
```

## 3. Kako router radi

Router je u:

```php
core/router.php
```

On cita clean URL iz:

```php
$_GET['url']
```

Zatim ga prevodi u interne `$_GET` vrijednosti.

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

To znaci da aplikacija interno i dalje moze koristiti `page`, ali korisnik vidi lijep URL.

## 4. URL helperi

Helperi su u:

```php
model/helpers/urlHelper.php
```

Najbitnije funkcije:

```php
appUrl()
pageUrl()
shopUrl()
productUrl()
newsUrl()
logoutUrl()
orderSuccessUrl()
```

### `appUrl()`

Pravi osnovni URL unutar aplikacije.

Primjer:

```php
appUrl('contact')
```

vrati:

```text
/v5/contact
```

### `shopUrl()`

Koristi se za shop/explore stranicu.

Primjer:

```php
shopUrl()
```

vrati:

```text
/v5/shop
```

Ako ima filtere:

```php
shopUrl(['gender' => 'male'])
```

vrati:

```text
/v5/shop?gender=male
```

### `productUrl()`

Ako proizvod ima `category_slug` i `slug`, pravi clean product URL:

```text
/v5/shop/category-slug/product-slug
```

Ako slugovi ne postoje, moze pasti na stari fallback:

```text
index.php?page=product&id=...
```

To je fallback, ali cilj je da proizvodi imaju slugove.

### `newsUrl()`

Pravi URL za vijest:

```text
/v5/news/15-naslov-vijesti
```

## 5. `pageUrl()` sada radi public clean URL

`pageUrl()` je ranije uvijek pravio:

```text
index.php?page=...
```

Sada za public stranice vraca clean URL.

Primjeri:

```php
pageUrl('explore')       // /v5/shop
pageUrl('cart-checkout') // /v5/cart
pageUrl('order')         // /v5/order
pageUrl('login')         // /v5/login
pageUrl('register')      // /v5/register
pageUrl('contact')       // /v5/contact
```

Za admin panel i dalje moze vratiti query URL:

```text
index.php?page=adminPanel
```

To je u redu jer admin nije public SEO dio sajta.

## 6. Canonical URL

Canonical URL govori browserima i trazilicama:

> Ovo je glavna/adresna verzija ove stranice.

Ako postoje dvije adrese:

```text
/index.php?page=explore
/shop
```

canonical treba pokazivati na:

```text
/shop
```

SEO helper je u:

```php
model/helpers/seo.php
```

Tu se canonical za public stranice sada vodi prema nice URL-ovima.

Primjeri:

```php
$_output['canonical'] = shopUrl();
$_output['canonical'] = appUrl('order');
$_output['canonical'] = productUrl($product);
```

Vazno:

```text
Canonical ne prebacuje korisnika na drugi URL.
Canonical samo kaze trazilici koja je glavna verzija stranice.
```

Znaci, ako korisnik otvori:

```text
/v5/index.php?page=explore
```

stranica se i dalje moze prikazati, ali canonical u `<head>` treba pokazivati na:

```text
/v5/shop
```

To pomaze Google-u da ne tretira obje adrese kao dvije jednako vazne stranice.

## 7. Canonical vs 301 redirect

Canonical i 301 redirect nisu ista stvar.

### Canonical

Canonical je SEO signal.

Koristi se kada stranica moze postojati na vise URL-ova, ali zelis reci:

```text
Ovaj URL je glavni.
```

Primjer:

```text
/v5/index.php?page=explore
/v5/shop
```

Canonical treba biti:

```text
/v5/shop
```

### 301 redirect

301 redirect stvarno prebacuje korisnika i browser na novi URL.

Primjer:

```text
/v5/index.php?page=explore
```

automatski ide na:

```text
/v5/shop
```

To je jace rjesenje za produkciju jer stari public URL vise ne ostaje kao posebna adresa.

### Sta je najbolje u ovom projektu

Trenutno je dobro sto:

- public linkovi uglavnom koriste helper funkcije
- canonical pokazuje na clean URL
- router podrzava nice URL-ove

Za produkciju bi jos bolje bilo dodati 301 redirect za stare public query URL-ove.

Primjeri koje ima smisla redirectati:

```text
/v5/index.php?page=explore       -> /v5/shop
/v5/index.php?page=shop          -> /v5/in2theshop
/v5/index.php?page=contact       -> /v5/contact
/v5/index.php?page=news          -> /v5/news
/v5/index.php?page=cart-checkout -> /v5/cart
/v5/index.php?page=order         -> /v5/order
```

Admin query URL-ove ne treba dirati:

```text
index.php?page=adminPanel&view=orders
index.php?page=adminPanel&view=content
```

Oni nisu public SEO dio sajta i normalno je da ostanu query URL-ovi.

## 8. Sta smo pregledali

Pretrazeni su linkovi koji koriste:

```text
index.php?page=
pageUrl()
appUrl()
shopUrl()
productUrl()
newsUrl()
```

Public propusti koji su popravljeni:

- cart empty link je prebacen na `shopUrl()`
- register forma je prebacena na `appUrl('register')`
- login/logout linkovi u admin layoutu koriste helper funkcije
- product comments JS vise ne koristi `index.php?page=product&id=...`
- SEO canonical koristi clean URL gdje treba
- product canonical koristi `productUrl($product)`

## 9. Sta je namjerno ostavljeno kao query URL

Admin panel je ostavljen kao query URL:

```text
index.php?page=adminPanel&view=orders
index.php?page=adminPanel&view=viewNews
index.php?page=adminPanel&action=delete
```

To je prihvatljivo jer:

- admin nije SEO/public dio
- admin ima puno internih view/action parametara
- jednostavnije je za odrzavanje

Pravilo:

```text
Public site = nice URLs
Admin panel = query URLs su OK
```

## 10. Aktivni cart JS

Aktivni cart JS je u:

```text
assets/js/shop/shop_god/
```

## 11. Kako ubuduce dodavati linkove

Nemoj rucno pisati:

```php
index.php?page=explore
```

Koristi helper:

```php
shopUrl()
```

Nemoj rucno pisati:

```php
index.php?page=product&id=15
```

Koristi:

```php
productUrl($product)
```

Za vijesti:

```php
newsUrl($news)
```

Za obicne public stranice:

```php
appUrl('contact')
appUrl('login')
appUrl('register')
```

Za admin je OK:

```php
index.php?page=adminPanel&view=orders
```

## 12. Kratko objasnjenje za ispit

Mozes reci:

> Aplikacija interno koristi `page` parametar da zna koji model i view treba ucitati, ali korisniku se prikazuju clean URL-ovi. Router clean URL pretvara u interne parametre. Linkovi u public dijelu se prave preko helper funkcija kao sto su `shopUrl`, `productUrl` i `newsUrl`, pa ne moram rucno pisati `index.php?page=...`. Canonical URL u SEO helperu pokazuje trazilicama koja je glavna clean verzija stranice. Ako zelim potpuno ukloniti stare public query URL-ove iz upotrebe, onda se za produkciju dodaje 301 redirect sa starog URL-a na novi clean URL.
