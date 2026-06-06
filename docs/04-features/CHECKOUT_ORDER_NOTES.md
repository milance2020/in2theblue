# Checkout i narudžbe

Ovaj dio objašnjava šta se dešava kada korisnik završi kupovinu.

## 1. Korpa dolazi iz sessiona

Korpa se čuva u `$_SESSION['cart']`. To znači da svaki korisnik u svom browseru ima svoju trenutnu korpu.

U `model/model-order.php` koristimo:

```php
$cart = $_SESSION['cart'] ?? [];
$items = cartGet($conn);
```

`cartGet($conn)` uzima podatke iz sessiona i preko baze dodaje detalje proizvoda: naziv, cijenu, sliku, veličinu i subtotal.

## 2. CSRF zaštita

Checkout forma sada ima:

```php
<?= csrf_input() ?>
```

To ubacuje skriveni input sa tokenom. Kada korisnik pošalje formu, model provjerava token:

```php
csrf_verify_or_die();
```

Poenta: narudžba se ne može poslati sa neke druge stranice bez validnog tokena iz naše sesije.

## 3. Validacija podataka

Prije upisa u bazu provjeravamo:

- da korpa nije prazna
- da ime postoji
- da email ima validan format
- da telefon, adresa, grad i država nisu prazni

Ako nešto nije uredu, greške se dodaju u `$errors` i prikažu se korisniku na checkout stranici.

## 4. Ponovna provjera zaliha

Stock se provjerava kada korisnik dodaje proizvod u korpu, ali se sada provjerava još jednom prije upisa narudžbe.

Razlog: korisnik može staviti proizvod u korpu, čekati, a u međuvremenu se zaliha može promijeniti.

## 5. Transaction

Narudžba se ne upisuje u jednu tabelu, nego u više tabela:

- `customers`
- `orders`
- `order_items`

Zato se koristi transaction:

```php
$conn->begin_transaction();
```

Ako sve uspije, poziva se:

```php
$conn->commit();
```

Ako nešto pukne, poziva se:

```php
$conn->rollback();
```

To znači: ili se cijela narudžba spremi kako treba, ili se ne spremi ništa napola.

## 6. Siguran ispis u viewu

U `views/pages/shop/order.php` podaci se ispisuju preko:

```php
<?= e($value) ?>
```

To štiti HTML od nepoželjnog unosa korisnika ili teksta iz baze.

Primjer: ako neko pokuša ubaciti HTML/JS u ime proizvoda ili veličinu, browser to neće izvršiti kao kod.

## 7. Order success stranica

`model/model-order-success.php` više ne prikazuje samo ID iz URL-a.

Sada prvo provjeri da narudžba stvarno postoji u bazi:

```php
SELECT id, total_price, created_at
FROM orders
WHERE id = ?
LIMIT 1
```

Ako narudžba ne postoji, prikazuje se 404 stranica.

Poenta: URL može svako promijeniti ručno, zato ne vjerujemo samo parametru iz URL-a.

## 8. Admin detalj narudžbe

Admin detalj narudžbe čita:

- podatke iz `orders`
- podatke kupca iz `customers`
- stavke narudžbe iz `order_items`
- naziv i sliku proizvoda iz `products2`

U viewu se tekstualni podaci ispisuju preko `e()`, npr:

```php
<?= e($order->full_name) ?>
```

To je bitno jer su ime, email, adresa i telefon podaci koje korisnik unosi kroz formu.

Ako admin otvori nepostojeću narudžbu, model postavlja 404 view umjesto da stranica pukne.
