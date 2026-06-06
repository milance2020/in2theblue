# SEO optimizacija projekta

Ovaj dokument objasnjava kako je projekat trenutno SEO optimizovan i sta bi bili dobri sljedeci koraci.

SEO nije samo jedna stvar. Nije dovoljno imati samo `meta description` ili samo clean URL. Dobar SEO setup se sastoji od vise manjih stvari koje zajedno pomazu trazilicama da bolje razumiju stranicu.

## 1. Sta je vec implementirano

U projektu su vec uvedene neke osnovne SEO stvari:

- clean/nice URL-ovi
- canonical URL
- meta title
- meta description
- robots meta tag
- breadcrumbs
- bolja struktura view i SEO helpera
- favicon tagovi
- responsive layout
- 404 stranica
- optimizovanije slike

Ovo je sasvim solidna osnova za studentski projekat, praktikum i diplomski.

## 2. Clean URL-ovi

Ranije su stranice izgledale ovako:

```text
index.php?page=explore
index.php?page=product&id=15
```

Sada public dio sajta koristi ljepse URL-ove:

```text
/shop
/cart
/order
/news
/shop/kategorija/proizvod
```

Ovo je bolje jer:

- korisniku je lakse procitati URL
- URL izgleda profesionalnije
- Google lakse razumije strukturu stranice
- manje se rucno pisu linkovi po projektu

Interno projekat i dalje moze koristiti `page`, ali korisnik vidi clean URL. To radi router.

## 3. URL helperi

Umjesto da se linkovi pisu rucno, koriste se helper funkcije:

```php
appUrl()
pageUrl()
shopUrl()
productUrl()
newsUrl()
assetUrl()
```

Ovo je dobro jer se logika za URL-ove drzi na jednom mjestu.

Primjer:

```php
shopUrl()
```

vrati shop URL.

```php
productUrl($product)
```

vrati URL proizvoda.

Prednost:

```text
Ako kasnije promijenis strukturu URL-a, ne moras traziti svaki link po projektu.
Promijenis helper i vecina linkova se sama popravi.
```

## 4. Canonical URL

Canonical govori trazilicama koja je glavna verzija stranice.

Primjer:

```text
/index.php?page=explore
/shop
```

Ako obje adrese mogu otvoriti isti sadrzaj, canonical treba pokazivati na:

```text
/shop
```

U projektu se canonical postavlja kroz:

```php
$_output['canonical']
```

Zatim se u `<head>` ispisuje:

```html
<link rel="canonical" href="...">
```

Bitno:

- canonical nije redirect
- canonical ne mijenja URL u browseru
- canonical govori Google-u koja verzija je glavna

Za produkciju bi jos bolje bilo dodati `301 redirect` sa starih public query URL-ova na clean URL-ove.

## 5. Meta title

Meta title je naslov stranice.

U HTML-u izgleda ovako:

```html
<title>In2TheShop</title>
```

U projektu dolazi iz:

```php
$_output['meta_title']
```

Ovo je vazno jer:

- vidi se u browser tabu
- Google ga cesto koristi kao naslov rezultata
- korisniku govori gdje se nalazi

Primjeri:

```text
In2TheShop
Shop
Majice | Shop
Product Name | Shop
Korpa | Shop
```

Kod shop liste title se mijenja zavisno od filtera, kategorije ili pretrage. To je dobar detalj jer stranica ne mora uvijek imati isti genericki naslov.

## 6. Meta description

Meta description je kratak opis stranice.

U HTML-u izgleda ovako:

```html
<meta name="description" content="Online shop sa modernim proizvodima.">
```

U projektu dolazi iz:

```php
$_output['meta_description']
```

Za product stranicu description se pravi iz opisa proizvoda.

Tu se koristi:

```php
cleanMetaDescription()
```

Ova funkcija:

- uklanja HTML tagove
- skracuje tekst

To je korisno jer meta description ne treba biti veliki HTML tekst, nego kratak normalan opis.

## 7. Robots meta tag

Robots meta tag govori trazilicama da li smiju indeksirati stranicu.

U HTML-u:

```html
<meta name="robots" content="index, follow">
```

Najcesce vrijednosti:

```text
index, follow
```

Znaci:

- indeksiraj stranicu
- prati linkove sa stranice

```text
noindex, nofollow
```

Znaci:

- ne indeksiraj stranicu
- ne prati linkove sa stranice

```text
noindex, follow
```

Znaci:

- ne indeksiraj stranicu
- ali smijes pratiti linkove

U projektu:

```text
Public stranice: index, follow
Cart: noindex, nofollow
Checkout: noindex, nofollow
Order success: noindex, follow
```

Ovo ima smisla jer korpa, checkout i success stranica nisu javni sadrzaj za Google.

## 8. Breadcrumbs

Breadcrumbs pokazuju korisniku gdje se nalazi.

Primjer:

```text
Pocetna / In2TheShop / Proizvodi / Majice / Product Name
```

U projektu se pune kroz:

```php
addBreadcrumb()
addShopBreadcrumbs()
```

Dobro je sto se breadcrumbs ne pisu rucno u svakom viewu, nego ih SEO helper priprema.

SEO korist:

- bolja navigacija za korisnika
- jasnija struktura stranice
- Google lakse razumije hijerarhiju sadrzaja

## 9. Favicon i osnovni head tagovi

U `<head>` dijelu postoje:

```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
```

`charset` je bitan za nasa slova.

`viewport` je bitan za responsive prikaz na mobitelu.

Favicon tagovi su dodani za:

- browser tab
- bookmark
- mobilne uredjaje

Ovo nije najveci SEO faktor, ali doprinosi profesionalnom dojmu stranice.

## 10. Responsive dizajn

Admin panel i public stranice su sredjivane da budu responsive.

Ovo je bitno jer Google i korisnici puno gledaju stranice preko mobitela.

Ako stranica nije mobile-friendly:

- losije korisnicko iskustvo
- veca sansa da korisnik brzo ode
- slabiji dojam na odbrani i u produkciji

## 11. 404 stranica

Projekat ima custom 404 stranicu.

To je dobro jer korisnik ne dobije praznu ili ruznu gresku ako otvori nepostojeci URL.

SEO korist:

- jasnije rukovanje nepostojecim stranicama
- korisnik se moze vratiti na normalan dio sajta
- projekt izgleda kompletnije

## 12. Slike i performanse

Slike su dio SEO-a jer performanse uticu na korisnicko iskustvo.

U projektu su neke velike slike smanjene i sredjene, posebno kartice i PDF/slike gdje je bilo prevelikih fajlova.

Dobro pravilo:

```text
Slika treba biti dovoljno kvalitetna da izgleda dobro,
ali ne toliko velika da usporava stranicu.
```

Za produkciju je dobro koristiti:

- manje dimenzije za kartice
- kompresovane slike
- WebP format gdje ima smisla
- `alt` tekst za vazne slike

## 13. Sta je jos dobro dodati

Ovo nisu obavezne stvari za osnovni projekat, ali su dobri sljedeci koraci.

## 14. 301 redirect za stare public URL-ove

Canonical vec pomaze, ali 301 redirect je jos jaci.

Primjer:

```text
/index.php?page=explore
```

treba prebaciti na:

```text
/shop
```

Prednost:

- korisnik uvijek zavrsi na clean URL-u
- Google jasnije vidi da je stari URL zamijenjen
- manje duplog sadrzaja

Admin query URL-ove ne treba dirati.

## 15. Sitemap.xml

Sitemap je XML fajl koji navodi vazne stranice sajta.

Primjer:

```text
/in2theshop
/in2thebar
/shop
/news
/contact
/shop/kategorija/proizvod
```

Sitemap pomaze Google-u da lakse pronadje stranice.

Za ovaj projekat sitemap bi mogao ukljuciti:

- glavne public stranice
- sve proizvode
- sve vijesti
- kategorije shopa

## 16. Robots.txt

`robots.txt` je fajl u rootu sajta koji daje osnovne instrukcije crawlerima.

Primjer:

```text
User-agent: *
Disallow: /admin
Disallow: /model
Disallow: /core
Sitemap: https://example.com/sitemap.xml
```

Ovo ne sluzi kao sigurnosna zastita, ali govori crawlerima koje dijelove ne treba obilaziti.

Bitno:

```text
robots.txt nije sigurnost.
Ako nesto mora biti privatno, to se stiti loginom i autorizacijom.
```

## 17. Alt tekst za slike

Slike bi trebale imati smislen `alt`.

Primjer:

```html
<img src="..." alt="Crna In2TheBar majica">
```

Alt tekst pomaze:

- pristupacnosti
- korisnicima koji ne vide sliku
- trazilicama da razumiju sliku

Ne treba pretjerivati sa keywordima.

Lose:

```text
majica majice shop online kupi najbolja majica
```

Dobro:

```text
Crna majica sa In2TheBar logom
```

## 18. Open Graph tagovi

Open Graph tagovi pomazu kada se link dijeli na Facebook, Instagram, Viber, Discord i slicno.

Primjer:

```html
<meta property="og:title" content="In2TheShop">
<meta property="og:description" content="Online shop sa modernim proizvodima.">
<meta property="og:image" content="...">
<meta property="og:url" content="...">
```

Ovo nije klasicni Google SEO, ali je dobro za dijeljenje linkova.

Za projekat bi bilo dobro dodati:

- `og:title`
- `og:description`
- `og:image`
- `og:url`
- `og:type`

## 19. Structured data

Structured data je dodatni JSON koji trazilicama preciznije opisuje sadrzaj.

Za product stranicu bi se mogao dodati `Product` schema.

Primjer ideje:

```text
Product name
Product image
Description
Price
Availability
```

To moze pomoci Google-u da bolje razumije proizvod.

Ne bih ovo radio prerano. Ovo je dobar napredniji korak kada osnovni SEO vec radi.

## 20. Bolji SEO za vijesti

News stranica se moze dodatno poboljsati.

Dobri koraci:

- svaka vijest ima svoj clean URL
- svaka vijest ima svoj title
- svaka vijest ima svoj description
- svaka vijest ima canonical
- dodati datum objave
- dodati sliku i alt tekst

Ako se vijesti koriste ozbiljnije, mogu imati i `Article` structured data.

## 21. Bolji SEO za proizvode

Product stranice su najvaznije za shop dio.

Dobri koraci:

- svaki proizvod ima unikatan slug
- svaki proizvod ima dobar opis
- svaka slika ima alt tekst
- canonical koristi clean product URL
- meta title sadrzi ime proizvoda
- description dolazi iz opisa proizvoda
- dodati product structured data kasnije

## 22. Koraci naprijed po prioritetu

Najrealniji redoslijed:

1. Dodati 301 redirect za stare public query URL-ove
2. Dodati `robots.txt`
3. Dodati `sitemap.xml`
4. Provjeriti alt tekst za bitne slike
5. Dodati Open Graph tagove
6. Dodati bolji SEO za pojedinacne vijesti
7. Dodati structured data za proizvode
8. Dodatno optimizovati slike u WebP gdje ima smisla

## 23. Kako objasniti na odbrani

Mozes reci:

> Projekat je SEO optimizovan tako sto public dio koristi clean URL-ove, a linkovi se generisu kroz URL helper funkcije. SEO podaci su centralizovani u `seo.php`, gdje se za svaki tip stranice postavljaju title, description, robots, canonical i breadcrumbs. Canonical pokazuje trazilicama glavnu verziju URL-a, dok robots meta tag kontrolise da li se stranica smije indeksirati. Public stranice su `index, follow`, a korpa i checkout su `noindex` jer nisu javni sadrzaj. U `<head>` se ispisuju osnovni SEO tagovi, favicon tagovi i responsive viewport. Kao sljedeci produkcijski koraci mogu se dodati 301 redirecti, sitemap, robots.txt, Open Graph tagovi i structured data za proizvode.

## 24. Kratka verzija

Trenutno projekat ima dobru osnovu:

- clean URLs
- canonical
- meta title
- meta description
- robots tag
- breadcrumbs
- responsive head setup
- favicon
- 404 stranica

Sljedeci najvazniji koraci:

- 301 redirect za stare public URL-ove
- sitemap.xml
- robots.txt
- alt tekst za slike
- Open Graph tagovi
- structured data za proizvode
