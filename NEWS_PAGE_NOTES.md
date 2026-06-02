# News page refactor

Ovaj dokument objasnjava kako je sredjena news stranica i zasto je to uradjeno.

## 1. Sta je bio problem

News stranica je ranije bila dosta jednostavna:

- prikazivala je jednu glavnu vijest
- ispod je bila lista ostalih vijesti
- public stranica je imala i inline edit mode za ulogovane korisnike
- SEO je bio genericki
- nije bilo normalnog empty state-a ako nema vijesti
- kategorija iz baze se nije koristila u prikazu

Najveci konceptualni problem je bio:

> Public stranica je mijesala prikaz vijesti i editovanje vijesti.

Bolje je da public stranica samo prikazuje vijesti, a admin panel sluzi za editovanje.

## 2. Fajlovi koji su promijenjeni

Glavni fajlovi:

```php
model/model-news.php
views/pages/site/news.php
assets/css/public/pages/news.css
views/partials/news-latest.php
```

Dodatno je dopunjen:

```php
model/helpers/urlHelper.php
```

zbog helpera za prikaz putanja slika iz baze.

## 3. Novi model: `model-news.php`

Model sada radi vise jasnih stvari:

1. Ucita trenutnu vijest.
2. Ako postoji `id` u URL-u, ucita tu vijest.
3. Ako nema `id`, ucita najnoviju vijest.
4. Ako postoji `category`, filtrira vijesti po kategoriji.
5. Ucita ostale vijesti za grid.
6. Ako nema vijesti, view dobije empty state.
7. Postavi SEO podatke za trenutnu vijest.

## 4. URL tok

News ruta moze raditi ovako:

```text
/news
```

Prikazuje najnoviju vijest.

```text
/news/15
```

Prikazuje vijest sa ID-em `15`.

```text
/news?category=bar
```

Prikazuje najnoviju vijest iz kategorije `bar` i ostale vijesti iz iste kategorije.

## 5. Kategorije

U modelu postoji niz:

```php
$newsCategories = [
    '' => 'Sve',
    'bar' => 'Bar',
    'rooms' => 'Rooms',
    'shop' => 'Shop',
];
```

Ovaj niz se koristi za filter linkove i za prikaz kategorije na karticama.

Ako korisnik posalje kategoriju koja nije dozvoljena, model je ignorise:

```php
if (!in_array($category, $allowedCategories, true)) {
    $category = '';
}
```

To je jednostavna validacija.

## 6. SEO za vijesti

Ranije je news stranica koristila genericki:

```php
setSEO('news');
```

Sada, ako postoji konkretna vijest, model postavlja SEO prema toj vijesti:

```php
$_output['meta_title'] = $currentNews['title'] . ' | Vijesti';
$_output['meta_description'] = cleanMetaDescription($currentNews['content'] ?? '');
$_output['canonical'] = newsUrl($currentNews);
```

To znaci da svaka vijest moze imati svoj title, description i canonical URL.

## 7. Novi public view

Fajl:

```php
views/pages/site/news.php
```

sada ima strukturu:

- header stranice
- category filter
- empty state ako nema vijesti
- featured/current article
- grid sa ostalim vijestima

Inline edit je uklonjen iz public stranice.

Editovanje vijesti treba ostati u admin panelu.

## 8. Siguran ispis

U viewu se koristi:

```php
e()
```

Primjer:

```php
<?= e($currentNews['title']) ?>
```

To je vazno jer naslov i sadrzaj vijesti dolaze iz baze.

Za tekst clanka:

```php
<?= nl2br(e($currentNews['content'])) ?>
```

`e()` prvo sigurno escape-a tekst, a `nl2br()` zatim nove redove pretvara u `<br>`.

## 9. `storedFileUrl()`

U `urlHelper.php` dodat je helper:

```php
storedFileUrl()
```

Razlog:

Putanje slika iz baze mogu biti razlicite:

```text
assets/images/...
/v5/assets/images/...
https://...
```

Ako se uvijek koristi `appUrl()`, moze se desiti dupliranje:

```text
/v5/v5/assets/...
```

`storedFileUrl()` provjerava putanju i vraca ispravan URL.

## 10. Latest news partial

Fajl:

```php
views/partials/news-latest.php
```

sredjen je da:

- koristi `e()`
- koristi `storedFileUrl()` za sliku
- link vodi na konkretnu vijest preko `newsUrl($news)`

Prije je link vodio samo na:

```text
/news
```

Sada vodi na:

```text
/news/{id}
```

## 11. CSS

Fajl:

```css
assets/css/public/pages/news.css
```

je preuredjen.

Sada ima:

- stil za news preview na home stranici
- stil za news page header
- category filter tabs
- featured article layout
- article body
- grid kartice
- empty state
- responsive pravila

## 12. Koncept koji treba razumjeti

News stranica sada ima dvije uloge:

### News index

```text
/news
```

Prikazuje najnoviju vijest i listu ostalih.

### News detail

```text
/news/15
```

Prikazuje konkretnu vijest i ostale vijesti ispod.

To je jednostavan pristup za manji projekat.

Kasnije bi se moglo napraviti:

```text
/news
/news/slug-vijesti
```

ali za trenutni nivo ID ruta je sasvim OK.

## 13. Sta bi se moglo kasnije dodati

Kasnije se news sistem moze prosiriti sa:

- slugom za vijesti
- published/draft statusom
- boljim upload validation za slike
- autorom vijesti
- pagination za veci broj vijesti
- related news po kategoriji
- search po vijestima

Ali trenutna verzija je dobar i razumljiv korak naprijed.

## 14. Kratko objasnjenje za ispit

Mozes reci:

> News stranicu sam preuredio tako da public dio samo prikazuje vijesti, a editovanje ostaje u admin panelu. Model ucitava trenutnu vijest, ostale vijesti i opcionalno filtrira po kategoriji. Za svaku vijest se postavlja poseban SEO title, description i canonical URL. View koristi siguran ispis preko `e()`, a CSS je preuredjen u article layout sa responsive karticama za ostale vijesti.
