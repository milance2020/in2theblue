# Content, role sistem i news slug URL

## 1. News slug URL

Prije je news link izgledao ovako:

```txt
/news/15
```

Sada `newsUrl($news)` pravi ljepši link:

```txt
/news/15-naslov-vijesti
```

ID je i dalje prvi dio URL-a, zato ne treba nova kolona u bazi. Router uzme broj iz URL-a:

```php
$_GET['id'] = (int) $segments[1];
```

Ako je URL `/news/15-naslov-vijesti`, `(int)` izvuče `15`.

Prednost: stari linkovi `/news/15` i dalje rade, a novi linkovi su ljepši za SEO i čitljiviji korisniku.

## 2. Editable content

Tabela `site_content` sada ne služi samo za footer.

Koristi se i za:

- home hero tekst
- kontakt uvodni tekst
- kontakt podatke
- footer

Helper `model/helpers/siteContent.php` ima default vrijednosti. Ako tabela nema podatke, stranica se i dalje prikazuje normalno.

Admin/moderator uređuje sadržaj kroz:

```txt
Admin Panel -> Uredi sadržaj
```

Forma snima podatke preko `update_content.php`, a model za prikaz forme je `content_edit.php`.

## 3. Role sistem

U `model/model-adminPanel.php` sada svaka ruta ima svoje dozvoljene role:

```php
'insertUsers' => [
    'view' => 'admin/user-insert',
    'roles' => ['admin'],
],
```

Primjer:

- admin može sve
- moderator može narudžbe, poruke, komentare, vijesti i sadržaj
- moderator ne može dodavati korisnike, brisati proizvode ili uređivati proizvode

Provjera se radi preko:

```php
roleCanAccess($config['roles'])
```

To je lakše objasniti nego dvije odvojene liste za admina i moderatora.
