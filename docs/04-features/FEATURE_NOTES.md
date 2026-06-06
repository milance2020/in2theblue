# Dashboard, flash poruke i error stranice

Ovaj dokument objasnjava tri dodatka koja su ubacena u projekat:

- admin dashboard
- flash poruke
- 404 i 403 stranice

Ideja je da sve ostane jednostavno i razumljivo za objasniti na ispitu.

## 1. Admin dashboard

Dashboard je pocetna stranica admin panela.

Kada admin otvori:

```text
index.php?page=adminPanel
```

prikazuje se:

```php
views/admin/dashboard.php
```

Podaci za dashboard se pripremaju u:

```php
model/admin/dashboard.php
```

Dashboard trenutno prikazuje 4 kartice:

- narudzbe na cekanju
- aktivni proizvodi
- komentari na cekanju
- neprocitane poruke

Primjer logike:

```php
SELECT COUNT(*) AS total FROM orders WHERE status = 'Pending'
```

To znaci da dashboard ne prikazuje sve podatke iz baze, nego samo brojeve koji adminu brzo govore sta treba pogledati.

Objasnjenje za ispit:

> Dashboard sluzi kao kratak pregled admin panela. Umjesto da admin odmah ide u tabele, prvo vidi najbitnije brojeve i klikom moze otvoriti detaljan pregled.

## 2. Flash poruke

Flash poruke su kratke poruke koje se prikazu nakon redirecta.

Primjer:

```text
Proizvod je uspjesno dodan.
```

ili:

```text
Greska pri brisanju proizvoda.
```

Funkcije su dodane u:

```php
core/security.php
```

### `flash_set()`

Ova funkcija spremi poruku u sesiju:

```php
flash_set('success', 'Proizvod je uspjesno dodan.');
```

Poruka se cuva u:

```php
$_SESSION['flash']
```

### `flash_render()`

Ova funkcija prikaze poruke i onda ih obrise:

```php
<?= flash_render() ?>
```

Zato se zovu "flash" poruke: prikazu se jednom i nestanu.

Koriste se u:

```php
views/layouts/admin.php
views/layouts/body.php
```

Objasnjenje za ispit:

> Flash poruke koristim da korisnik nakon akcije dobije povratnu informaciju. Poruka se upise u sesiju prije redirecta, a nakon prikaza se brise da se ne ponavlja stalno.

## 3. 404 stranica

404 stranica se prikazuje kada korisnik otvori stranicu koja ne postoji.

Dodani fajlovi:

```php
model/model-error.php
model/model-error404.php
views/pages/errors/404.php
```

U `model-error.php` se postavlja:

```php
http_response_code(404);
```

To browseru i serveru govori da stranica nije pronadjena.

Objasnjenje za ispit:

> Umjesto prazne ili pokvarene stranice, korisnik dobije normalnu 404 stranicu sa linkovima nazad na pocetnu i shop.

## 4. 403 stranica

403 stranica se prikazuje kada korisnik nema dozvolu za pristup.

Dodani fajl:

```php
views/pages/errors/403.php
```

Ovo se koristi za situacije kada neko nije admin, a pokusa otvoriti admin dio.

Objasnjenje za ispit:

> 404 znaci da stranica ne postoji, a 403 znaci da stranica postoji, ali korisnik nema dozvolu da joj pristupi.

## 5. CSS za nove dijelove

Dodani su i posebni CSS fajlovi:

```text
assets/css/admin/pages/dashboard.css
assets/css/public/components/flash.css
assets/css/public/pages/error.css
```

Oni su povezani kroz postojece glavne CSS fajlove:

```text
assets/css/admin/adminPanel.css
assets/css/public/style.css
```

To znaci da PHP nije morao dobiti nove `<link>` tagove.

## Kratko za odbranu

Mozes reci:

> Dodao sam admin dashboard kao pocetni pregled najvaznijih podataka. Dodao sam flash poruke da admin nakon akcije vidi da li je nesto uspjesno uradjeno ili je doslo do greske. Takodjer sam dodao 404 i 403 stranice da aplikacija ljepse reaguje na nepostojecu stranicu ili zabranjen pristup. Sve je uradjeno jednostavno kroz postojece modele, viewove i sesiju.
