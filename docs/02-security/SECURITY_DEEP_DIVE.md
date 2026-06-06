# Security deep dive

Ovaj dokument objasnjava sigurnosne stvari koje su dodane u projekat, ali malo sire nego `SECURITY_NOTES.md`.

Cilj nije da naucis napamet funkcije, nego da razumijes zasto postoje i kako bi ih mogao sam napraviti u buducnosti.

## 1. Sta znaci sigurnost u web aplikaciji

Web aplikacija prima podatke od korisnika:

- kroz URL
- kroz forme
- kroz JSON zahtjeve
- kroz upload fajlova
- kroz cookies i session

Osnovno pravilo sigurnosti je:

> Nikad ne vjeruj podacima koji dolaze od korisnika.

Korisnik ne mora koristiti tvoju formu. Moze poslati zahtjev rucno preko browsera, Postmana, JavaScripta ili nekog drugog alata.

Zato backend mora provjeriti:

- da li je korisnik ulogovan
- da li ima pravo za akciju
- da li je zahtjev dosao iz nase forme
- da li su podaci validni
- da li se podaci sigurno ispisuju u HTML
- da li SQL upiti nisu ranjivi

U ovom projektu su dodani osnovni dijelovi:

- `e()` za siguran HTML ispis
- CSRF tokeni za admin forme
- `require_admin()` za provjeru admin pristupa
- config za bazu izvan glavnog koda
- prepared statement umjesto direktnog SQL-a
- flash poruke koje rade preko sesije

## 2. `core/security.php`

Glavni sigurnosni helper je:

```php
core/security.php
```

On sadrzi funkcije koje se mogu koristiti kroz projekat.

To je bolje nego da isti kod kopiras u vise fajlova.

Primjer:

```php
require_admin();
csrf_verify_or_die();
```

Ako ove provjere trebaju u 10 admin akcija, ne pises svaki put cijeli `if`, nego pozoves funkciju.

## 3. XSS i funkcija `e()`

### Sta je XSS

XSS znaci Cross-Site Scripting.

To je napad gdje neko ubaci JavaScript kod u stranicu.

Primjer: korisnik u komentar unese:

```html
<script>alert('Napad')</script>
```

Ako ti taj komentar ispises ovako:

```php
<?= $comment ?>
```

browser moze pokusati izvrsiti taj JavaScript.

To je opasno jer JavaScript na stranici moze:

- citati dijelove stranice
- slati zahtjeve u ime korisnika
- krasti podatke ako nisu dobro zasticeni
- mijenjati izgled stranice

### Kako pomaze `htmlspecialchars`

U PHP-u se koristi:

```php
htmlspecialchars()
```

Ona pretvara opasne HTML karaktere u tekst.

Primjer:

```html
<script>
```

postaje nesto sto browser prikaze kao tekst, a ne izvrsava kao HTML/JS.

U projektu je napravljena kraca funkcija:

```php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
```

Zato se u viewu moze pisati:

```php
<?= e($row->title) ?>
```

umjesto:

```php
<?= htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8') ?>
```

### Kada koristiti `e()`

Koristi `e()` kada ispisujes podatak koji moze doci iz:

- baze
- forme
- URL-a
- sesije
- korisnickog unosa

Primjer:

```php
<h1><?= e($product['name']) ?></h1>
<p><?= e($product['description']) ?></p>
```

Ako si ti 100% siguran da je vrijednost hardcoded i nije korisnicki unos, onda nije toliko kriticno. Ali dobra navika je: u viewu skoro sve sto je promjenjivo ide kroz `e()`.

## 4. Authorization: `require_admin()`

### Authentication vs authorization

Ovo su dva razlicita pojma.

Authentication znaci:

> Ko si ti?

Primjer: login provjerava username i password.

Authorization znaci:

> Sta ti smijes raditi?

Primjer: user je ulogovan, ali ne smije otvoriti admin panel.

### Sta radi `require_admin()`

U projektu postoji:

```php
function require_admin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['ulogovan']) || ($_SESSION['role'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Zabranjen pristup');
    }
}
```

Ova funkcija provjerava:

- da li session postoji
- da li je korisnik ulogovan
- da li ima role `admin`

Ako nema pravo, prekida izvrsavanje i vraca HTTP 403.

### Sta znaci HTTP 403

`403 Forbidden` znaci:

> Stranica postoji, ali korisnik nema dozvolu da je otvori.

To nije isto kao 404.

`404 Not Found` znaci:

> Stranica ne postoji.

## 5. CSRF zastita

Ovo je najvazniji dio dokumenta.

### Sta je CSRF

CSRF znaci Cross-Site Request Forgery.

To je napad gdje napadac pokusa natjerati browser ulogovanog korisnika da posalje zahtjev tvojoj aplikaciji.

Bitna stvar:

> Browser automatski salje cookies za domen na koji ide zahtjev.

Ako si ti ulogovan u admin panel, tvoj browser ima session cookie.

Ako neka druga stranica uspije poslati zahtjev prema tvom sajtu, browser moze uz taj zahtjev poslati i tvoju session cookie.

Tada server misli:

> Ovo je zahtjev od ulogovanog admina.

### Primjer CSRF napada

Zamisli da admin brisanje proizvoda radi preko linka:

```text
https://example.com/index.php?page=adminPanel&action=delete&id=15
```

Admin je ulogovan u tvoj admin panel.

Napadac napravi drugu stranicu i ubaci:

```html
<img src="https://example.com/index.php?page=adminPanel&action=delete&id=15">
```

Kada admin otvori tu zlonamjernu stranicu, browser pokusa ucitati sliku.

Ali `src` zapravo pogodi tvoju delete rutu.

Ako tvoj server samo provjerava session, moze obrisati proizvod jer je admin cookie poslan automatski.

To je CSRF.

Admin nije kliknuo "obrisi" u tvojoj aplikaciji, ali je njegov browser poslao zahtjev.

### Zasto session nije dovoljan

Session kaze:

> Ko je korisnik?

Ali CSRF token kaze:

> Da li je zahtjev dosao iz forme koju je nasa aplikacija napravila?

To su dvije razlicite stvari.

Zato provjeravamo i jedno i drugo:

```php
require_admin();
csrf_verify_or_die();
```

`require_admin()` provjerava korisnika.

`csrf_verify_or_die()` provjerava token forme.

## 6. Kako CSRF token radi

### Korak 1: server napravi token

U projektu postoji:

```php
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}
```

Ako token ne postoji, napravi se novi nasumicni token.

Primjer tokena:

```text
8e1f0b92a4c29d7c6f5a1...
```

Bitno:

- token je dug
- token je nasumican
- cuva se u sesiji
- napadac ga ne zna

### Korak 2: token se ubaci u formu

U formi se pozove:

```php
<?= csrf_input() ?>
```

Ta funkcija vrati hidden input:

```html
<input type="hidden" name="csrf_token" value="neki_token">
```

Korisnik ga ne vidi, ali browser ga posalje kada se forma submituje.

### Korak 3: server provjeri token

U action fajlu se pozove:

```php
csrf_verify_or_die();
```

Funkcija:

```php
function csrf_verify_or_die(): void
{
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';

    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Invalid CSRF token');
    }
}
```

Ona uzme token iz forme i uporedi ga sa tokenom iz sesije.

Ako nisu isti, akcija se prekida.

### Zasto napadac ne moze pogoditi token

Napadac moze napraviti formu koja salje zahtjev prema tvojoj aplikaciji.

Ali ne zna token koji je tvoja aplikacija spremila u adminovu sesiju.

Bez tokena zahtjev pada.

Zato CSRF zastita radi.

## 7. `hash_equals`

Za poredjenje tokena koristi se:

```php
hash_equals($_SESSION['csrf_token'], $token)
```

Umjesto:

```php
$_SESSION['csrf_token'] === $token
```

`hash_equals` je sigurniji za poredjenje tajnih vrijednosti jer je napravljen da smanji rizik od timing napada.

Za tvoj nivo je dovoljno da znas:

> Kada poredim sigurnosne tokene, bolje je koristiti `hash_equals`.

## 8. CSRF za GET linkove

Idealno, akcije koje mijenjaju podatke ne bi trebale ici preko GET linkova.

Primjer:

```text
action=delete&id=15
```

Bolje bi bilo da delete ide preko POST forme.

Ali u projektu trenutno postoje delete linkovi.

Zato je dodano:

```php
csrf_url()
```

Ona doda token u URL:

```php
&<?= csrf_url() ?>
```

Primjer:

```php
<a href="index.php?page=adminPanel&action=delete&id=15&<?= csrf_url() ?>">
    Obrisi
</a>
```

To je bolje nego bez tokena.

Ali vazno za razumjeti:

> Najbolja praksa je da delete/update akcije idu preko POST forme, ne preko obicnog linka.

Za projekat je trenutna verzija prihvatljiv jednostavan korak, ali kasnije bi delete bilo bolje prebaciti na POST.

## 9. Gdje je CSRF dodat

CSRF je dodat na glavne admin forme i akcije.

Primjeri formi:

```php
views/admin/product-insert.php
views/admin/product-update.php
views/admin/news-insert.php
views/admin/news-update.php
views/admin/order-detail.php
views/admin/user-insert.php
```

Primjeri action fajlova:

```php
model/admin/insert.php
model/admin/update.php
model/admin/delete_product.php
model/admin/insert_news.php
model/admin/update_news_push.php
model/admin/delete_news.php
model/admin/update_orders.php
model/admin/insert_users_bp.php
```

U formi:

```php
<?= csrf_input() ?>
```

U action fajlu:

```php
require_admin();
csrf_verify_or_die();
```

## 10. Prepared statements

### Problem

Lose:

```php
$id = $_GET['id'];
$sql = "SELECT * FROM news WHERE id = '$id'";
```

Ako korisnik promijeni URL, moze pokusati ubaciti SQL.

### Bolje

```php
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

Ovdje SQL i podatak nisu direktno zalijepljeni jedan u drugi.

`?` je placeholder.

`bind_param("i", $id)` kaze:

- ovo je integer
- ubaci ga sigurno

Objasnjenje:

> Prepared statement odvaja SQL naredbu od korisnickih podataka.

## 11. Config za bazu

Prije su podaci za bazu bili direktno u:

```php
connect.php
```

To nije dobro jer lozinka moze zavrsiti u gitu.

Sada `connect.php` cita:

```php
model/admin/includes/config.php
```

Taj fajl je u `.gitignore`, pa se ne bi trebao commitati.

Koncept:

> Tajne podatke ne drzimo u javnom kodu.

## 12. Flash poruke i sigurnost

Flash poruke nisu direktno sigurnosna zastita, ali pomazu da akcije budu jasnije korisniku.

Primjer:

```php
flash_set('success', 'Proizvod je uspjesno dodan.');
```

Poruka se spremi u sesiju i prikaze jednom.

Kod prikaza se koristi `e()`:

```php
$html .= e($message);
```

To je vazno jer i poruke treba sigurno ispisati.

## 13. Kako razmisljati kada dodajes novu admin akciju

Svaki put kada dodajes novu admin formu, pitaj se:

### 1. Da li korisnik mora biti admin?

Ako da:

```php
require_admin();
```

### 2. Da li forma mijenja podatke?

Ako da:

U formu:

```php
<?= csrf_input() ?>
```

U action:

```php
csrf_verify_or_die();
```

### 3. Da li podaci idu u SQL?

Ako da, koristi prepared statement:

```php
$stmt = $conn->prepare("...");
```

### 4. Da li ispisujem podatke u HTML?

Ako da:

```php
<?= e($value) ?>
```

### 5. Da li korisnik treba vidjeti rezultat?

Ako da:

```php
flash_set('success', 'Akcija je uspjesna.');
```

## 14. Primjer kompletne admin akcije

Forma:

```php
<form method="post" action="index.php?page=adminPanel&action=updateExample">
    <?= csrf_input() ?>

    <input type="text" name="title">

    <button type="submit">Sacuvaj</button>
</form>
```

Action:

```php
require_once FILE_SECURITY_HELPER;
require_admin();
csrf_verify_or_die();

$title = trim($_POST['title'] ?? '');

if ($title === '') {
    flash_set('error', 'Naslov je obavezan.');
    header('Location: index.php?page=adminPanel&view=example');
    exit;
}

$stmt = $conn->prepare("UPDATE example SET title = ? WHERE id = ?");
$stmt->bind_param("si", $title, $id);
$stmt->execute();

flash_set('success', 'Podaci su sacuvani.');
header('Location: index.php?page=adminPanel&view=example');
exit;
```

Ovo je dobra osnovna struktura:

1. provjeri pravo
2. provjeri CSRF
3. uzmi podatke
4. validiraj
5. prepared statement
6. flash poruka
7. redirect

## 15. Najkrace objasnjenje za ispit

Mozes reci:

> Dodao sam sigurnosni helper koji sadrzi osnovne funkcije za zastitu aplikacije. `e()` koristim za siguran ispis podataka u HTML i zastitu od XSS-a. `require_admin()` provjerava da li korisnik ima admin pristup. CSRF zastita radi tako sto server napravi nasumican token, spremi ga u sesiju i ubaci u formu kao hidden input. Kada se forma posalje, server provjeri da li token iz forme odgovara tokenu iz sesije. Tako sprjecavamo da neka druga stranica posalje zahtjev u ime ulogovanog admina. SQL upite koji koriste korisnicke podatke radim preko prepared statementa da se podaci ne lijepe direktno u SQL.

## 16. Sta jos nije savrseno

Ovo je dobar osnovni nivo, ali nije kraj sigurnosti.

Kasnije se moze dodati:

- permission sistem za vise rola
- CSRF za sve forme, ne samo glavne admin forme
- delete akcije preko POST umjesto GET
- bolja validacija uploadovanih slika
- logovanje admin akcija
- ogranicenje broja login pokusaja
- sigurnije session cookie opcije

Ali za trenutni projekat, ovo je dobar i razumljiv sigurnosni temelj.
