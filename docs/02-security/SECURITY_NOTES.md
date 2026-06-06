# Kratko objasnjenje sigurnosnih izmjena

Ovaj dokument objasnjava male sigurnosne izmjene koje su dodane u projekat. Ideja nije bila napraviti "full professional" sistem, nego ubaciti osnovne stvari koje su razumljive i koje se mogu normalno objasniti na ispitu.

## 1. `core/security.php`

Dodao sam novi helper fajl:

```php
core/security.php
```

U njemu su jednostavne funkcije koje se koriste na vise mjesta u projektu.

### `e($value)`

```php
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
```

Ova funkcija sluzi za siguran ispis podataka u HTML.

Primjer:

```php
<?= e($row->title) ?>
```

Ako korisnik u bazu unese nesto kao:

```html
<script>alert('test')</script>
```

bez zastite bi se taj JavaScript mogao izvrsiti u browseru. Sa `e()` funkcijom, to se prikaze kao obican tekst.

Ovo je zastita od osnovnog XSS napada.

## 2. CSRF zastita

CSRF znaci da neko moze pokusati natjerati admina da klikom ili posjetom neke stranice uradi akciju bez namjere, na primjer obrise proizvod.

Zato je dodat token.

### `csrf_token()`

Pravi jedan nasumican token i cuva ga u sesiji:

```php
$_SESSION['csrf_token']
```

### `csrf_input()`

Ova funkcija dodaje hidden input u formu:

```php
<?= csrf_input() ?>
```

To izgleda otprilike ovako:

```html
<input type="hidden" name="csrf_token" value="neki_nasumican_token">
```

### `csrf_verify_or_die()`

Ova funkcija provjerava da li token iz forme odgovara tokenu iz sesije.

Ako ne odgovara, zahtjev se prekida:

```php
csrf_verify_or_die();
```

To sam dodao u admin akcije kao sto su:

```php
model/admin/insert.php
model/admin/update.php
model/admin/delete_product.php
model/admin/insert_news.php
model/admin/update_news_push.php
model/admin/delete_news.php
```

Objasnjenje za ispit:

> Forma dobije skriveni token koji zna samo nasa aplikacija. Kada admin posalje formu, provjerim da li je token isti kao u sesiji. Ako nije, akcija se ne izvrsava.

## 3. `require_admin()`

Dodana je funkcija:

```php
function require_admin(): void
```

Ona provjerava da li je korisnik ulogovan i da li ima admin rolu:

```php
if (empty($_SESSION['ulogovan']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(403);
    exit('Zabranjen pristup');
}
```

Koristi se na admin akcijama.

Objasnjenje za ispit:

> Prije svake admin akcije provjerim da li korisnik stvarno ima admin rolu. Ako nema, vracam 403 Forbidden i prekidam izvrsavanje.

## 4. Baza podataka i `config.php`

Prije su podaci za bazu bili direktno u `connect.php`.

Sada `connect.php` prvo pokusava ucitati:

```php
model/admin/includes/config.php
```

Taj fajl je vec u `.gitignore`, sto znaci da se ne treba slati na GitHub.

Primjer:

```php
return [
    'host' => 'localhost',
    'user' => 'admin',
    'pass' => 'password',
    'name' => 'in2theblue',
];
```

Ako `config.php` ne postoji, konekcija moze citati podatke iz environment varijabli:

```php
DB_HOST
DB_USER
DB_PASS
DB_NAME
```

Objasnjenje za ispit:

> Podaci za bazu nisu vise direktno u glavnom fajlu za konekciju. Odvojeni su u config fajl koji se ne commita, jer lozinke ne treba drzati javno u repozitoriju.

## 5. Prepared statement za news update

U starom kodu je bilo nesto slicno ovome:

```php
SELECT * FROM news WHERE id='$id'
```

To nije dobro jer se vrijednost iz URL-a direktno ubacuje u SQL.

Sada se koristi prepared statement:

```php
$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

Objasnjenje za ispit:

> Prepared statement odvaja SQL komandu od podataka koje korisnik salje. Tako korisnik ne moze lako ubaciti svoj SQL kod kroz URL ili formu.

## 6. Popravljen ispis u admin tabelama

U admin listama su podaci prije ispisivani direktno:

```php
<?= $row->title ?>
```

Sada se ispisuju sigurnije:

```php
<?= e($row->title) ?>
```

Ovo je dodano u:

```php
views/admin/products-list.php
views/admin/news-list.php
```

## 7. Encoding tekstova

Neki tekstovi su imali pokvarena slova, npr:

```text
PoÄetna
MuÅ¡ko
NarudÅ¾ba
```

To je najcesce problem sa encodingom fajla. Da ne bi kvarilo izgled stranice, tekstovi su prebaceni u jednostavan ASCII oblik:

```text
Pocetna
Musko
Narudzba
```

Ovo nije savrseno jezicki, ali je stabilno i citljivo.

## Kako ukratko predstaviti izmjene

Mozes reci:

> Dodao sam osnovni sigurnosni helper. U njemu imam funkciju za siguran HTML ispis, CSRF zastitu za admin forme i provjeru admin role. Takodjer sam prebacio jedan rizican SQL upit na prepared statement i odvojio podatke za bazu u config fajl koji se ne commita. Nisam uvodio framework ni komplikovanu strukturu, nego sam zadrzao postojeći stil projekta i dodao osnovnu zastitu koju razumijem.

## Sta ovo ne rjesava potpuno

Ovo nije kompletna profesionalna sigurnost. Jos bi se moglo dodati:

- bolja validacija uploadovanih slika
- CSRF na sve admin forme, ne samo glavne
- escape u svim viewovima
- bolji sistem za role i permissions
- logovanje gresaka umjesto prikaza gresaka korisniku

Ali za trenutni nivo projekta, ove izmjene su dobar i razumljiv korak naprijed.
