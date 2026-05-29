# Cart sistem - objasnjenje

Ovaj dokument objasnjava kako radi cart/korpa sistem u projektu.

Cart sistem ima tri glavna dijela:

- PHP session gdje se cuva korpa
- PHP API koji prima akcije iz JavaScripta
- JavaScript koji salje zahtjeve i osvjezava prikaz korpe

## 1. Gdje se cuva korpa

Korpa se cuva u PHP sesiji:

```php
$_SESSION['cart']
```

Struktura izgleda ovako:

```php
$_SESSION['cart'] = [
    product_id => [
        size => quantity
    ]
];
```

Primjer:

```php
$_SESSION['cart'] = [
    15 => [
        'M' => 2,
        'L' => 1
    ],
    22 => [
        'XL' => 1
    ]
];
```

To znaci:

- proizvod ID `15`, velicina `M`, kolicina `2`
- proizvod ID `15`, velicina `L`, kolicina `1`
- proizvod ID `22`, velicina `XL`, kolicina `1`

Ovo je dobar pristup za ovaj projekat jer korisnik ne mora biti ulogovan da bi imao korpu.

## 2. Glavni API endpoint

Glavni endpoint je:

```php
api/cart.php
```

Taj fajl samo ucita pravi API model:

```php
require_once FILE_API_CART;
```

Pravi API kod je u:

```php
model/api/api-cart.php
```

API prima akciju kroz URL:

```text
api/cart.php?action=add
api/cart.php?action=get
api/cart.php?action=remove
api/cart.php?action=increase
api/cart.php?action=decrease
```

Podaci se salju kao JSON.

Primjer:

```json
{
  "id": 15,
  "size": "M",
  "qty": 1
}
```

API uvijek vraca JSON odgovor.

## 3. Cart helper

Glavna logika korpe je u:

```php
model/helpers/cart.php
```

Tu se nalaze funkcije:

```php
cartAdd()
cartRemove()
cartIncrease()
cartDecrease()
cartGet()
cartCount()
cartTotal()
getStock()
```

### `cartAdd()`

Dodaje proizvod u korpu.

Prije dodavanja provjerava:

- da li je ID proizvoda validan
- da li je izabrana velicina
- da li je kolicina veca od nule
- da li ima dovoljno stocka

Ako nema dovoljno zaliha, vraca:

```php
['success' => false, 'message' => 'Not enough stock']
```

### `cartGet()`

Cita proizvode iz `$_SESSION['cart']`, ali zatim iz baze ucita:

- naziv proizvoda
- cijenu
- sliku

Zato frontend dobije punije podatke za prikaz korpe.

### `cartTotal()`

Racuna ukupnu cijenu korpe.

Za svaki proizvod uzima cijenu iz baze i mnozi je sa kolicinom iz sesije.

## 4. JavaScript dio

Glavni JS fajlovi su:

```text
assets/js/shop/shop_god/cart.service.js
assets/js/shop/shop_god/cart.ui.js
assets/js/shop/shop_god/explore.js
assets/js/shop/shop_god/product.js
assets/js/shop/shop_god/checkout.js
```

## 5. `CartService`

Fajl:

```text
assets/js/shop/shop_god/cart.service.js
```

Ovo je mali JS servis koji salje zahtjeve prema PHP API-ju.

Primjeri metoda:

```js
CartService.add(id, size, qty)
CartService.remove(id, size)
CartService.increase(id, size)
CartService.decrease(id, size)
CartService.get()
```

Sve metode na kraju koriste:

```js
fetch(`${apiCart}?action=${action}`)
```

`apiCart` dolazi iz:

```php
window.APP_URLS = {
    apiCart: URL_API_CART
}
```

To se postavlja u layoutu:

```php
views/layouts/nav.php
```

## 6. `CartUI`

Fajl:

```text
assets/js/shop/shop_god/cart.ui.js
```

Ovaj dio ne radi direktno sa bazom. On samo osvjezava prikaz:

- broj proizvoda u navbaru
- mini cart dropdown
- total

Najbitnija metoda:

```js
CartUI.refresh()
```

Ona pozove:

```js
CartService.get()
```

i onda osvjezi HTML na stranici.

## 7. Dodavanje iz explore stranice

Fajl:

```text
assets/js/shop/shop_god/explore.js
```

Tok:

1. Korisnik klikne velicinu, npr. `M`.
2. JS upise tu velicinu u `data-size` na dugme.
3. Korisnik klikne `Dodaj u korpu`.
4. JS pozove:

```js
CartService.add(id, size, 1)
```

5. Ako API vrati `success: true`, pozove se:

```js
CartUI.refresh()
```

## 8. Dodavanje sa product stranice

Fajl:

```text
assets/js/shop/shop_god/product.js
```

Na product stranici korisnik bira:

- velicinu iz select polja
- kolicinu preko `+` i `-`

Zatim JS salje:

```js
CartService.add(id, size, qty)
```

PHP opet provjerava stock, tako da korisnik ne moze samo preko browsera dodati vise nego sto postoji u bazi.

## 9. Cart stranica

Cart stranica koristi:

```php
views/pages/shop/cart.php
```

i JS:

```text
assets/js/shop/shop_god/checkout.js
```

Iako se fajl zove `checkout.js`, on zapravo renderuje cart stranicu.

Radi ovo:

- ucita korpu preko `CartService.get()`
- prikaze proizvode
- omoguci `+`, `-` i remove
- osvjezava total
- ako je korpa prazna, prikaze empty state

## 10. Checkout / narudzba

Checkout model je:

```php
model/model-order.php
```

On radi dva posla:

1. Prikaze pregled narudzbe iz session korpe.
2. Kada korisnik posalje formu, upise narudzbu u bazu.

Kod narudzbe se upisuje:

- customer u tabelu `customers`
- order u tabelu `orders`
- proizvodi u tabelu `order_items`

Nakon toga se korpa brise:

```php
unset($_SESSION['cart']);
```

i korisnik ide na:

```php
order-success/{orderId}
```

## 11. Order success

Fajlovi:

```php
model/model-order-success.php
views/pages/shop/order-success.php
```

Tu se samo prikaze potvrda narudzbe i ID narudzbe.

## 12. Sta je dobro u ovom sistemu

Dobre stvari:

- korpa radi preko sesije, pa nije potreban login
- API vraca JSON, sto je dobro za JS
- stock se provjerava na serveru, ne samo u JavaScriptu
- koristi se prepared statements za citanje proizvoda i stocka
- JS je podijeljen na servis (`CartService`) i UI dio (`CartUI`)
- cart stranica se moze osvjezavati bez reloadovanja cijele stranice

## 13. Sta bi se kasnije moglo popraviti

Ovo nisu hitne stvari, ali su dobre za buduce poboljsanje:

### 1. Ukloniti stari cart JS

Postoji folder:

```text
assets/js/shop/shop_old/
```

To izgleda kao stara verzija cart sistema. Ako se vise ne koristi, moze zbunjivati i kasnije bi se mogao ukloniti.

### 2. Izbaciti duplu cart logiku iz `model-cart-checkout.php`

U `model-cart-checkout.php` postoji stara remove akcija i poseban `cartCount()`.

Posto vec postoji centralni API:

```php
model/api/api-cart.php
model/helpers/cart.php
```

bolje je da se sva cart logika drzi tamo.

### 3. Dodati CSRF za checkout formu

Narudzba se salje kao POST forma u:

```php
model/model-order.php
```

Mogao bi se dodati `csrf_input()` u formu i `csrf_verify_or_die()` u model.

### 4. Jos jednom provjeriti stock pri kreiranju narudzbe

Stock se provjerava kod dodavanja u korpu, ali dobro je provjeriti ga opet prije upisa narudzbe.

Razlog: od trenutka dodavanja u korpu do checkouta stock se mogao promijeniti.

### 5. Popraviti encoding tekstove

U nekim cart/order viewovima jos ima pokvarenih karaktera, npr:

```text
VaÅ¡a korpa
NaruÄi
DrÅ¾ava
```

To je isti encoding problem koji se ranije pojavljivao.

## Kratko objasnjenje za ispit

Mozes reci:

> Korpa se cuva u PHP sesiji kao niz proizvoda, velicina i kolicina. JavaScript ne mijenja direktno bazu, nego salje zahtjeve na cart API. API koristi helper funkcije koje provjeravaju stock u bazi i mijenjaju `$_SESSION['cart']`. Kada korisnik ide na checkout, PHP iz session korpe napravi pregled narudzbe, a nakon slanja forme upisuje kupca, narudzbu i stavke narudzbe u bazu. Nakon uspjesne narudzbe korpa se brise iz sesije.
