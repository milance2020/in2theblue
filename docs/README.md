# Dokumentacija projekta

Ovdje je sortirana dokumentacija projekta po oblastima.

Ako prvi put prolazis kroz projekat, najbolji redoslijed je:

1. `01-project/PROJECT_WALKTHROUGH.md`
2. `01-project/PROJECT_INFRASTRUCTURE.md`
3. `02-security/SECURITY_NOTES.md`
4. `03-seo-routing/URL_ROUTING_NOTES.md`
5. `03-seo-routing/SEO_PROJECT_OVERVIEW.md`
6. `04-features/CART_SYSTEM_NOTES.md`
7. `04-features/CHECKOUT_ORDER_NOTES.md`

## 01 Project

Opsti pregled projekta i infrastrukture.

- `01-project/PROJECT_WALKTHROUGH.md` - prolazak kroz foldere i glavne dijelove projekta
- `01-project/PROJECT_INFRASTRUCTURE.md` - objasnjenje request flowa, MVC logike, helpera, layouta i asseta

## 02 Security

Sigurnosne izmjene i koncepti.

- `02-security/SECURITY_NOTES.md` - krace objasnjenje sigurnosnih izmjena
- `02-security/SECURITY_DEEP_DIVE.md` - detaljnije objasnjenje XSS, CSRF, rola, prepared statements i sigurnosnog razmisljanja

## 03 SEO Routing

URL routing, SEO helperi i optimizacija.

- `03-seo-routing/URL_ROUTING_NOTES.md` - nice URL-ovi, canonical, 301 redirect ideja i URL helperi
- `03-seo-routing/SEO_AND_VIEW_NOTES.md` - `seo.php`, `view.php`, head tagovi, canonical i robots
- `03-seo-routing/SEO_PROJECT_OVERVIEW.md` - siri pregled SEO optimizacije projekta i koraci naprijed

## 04 Features

Dokumentacija glavnih funkcionalnosti.

- `04-features/CART_SYSTEM_NOTES.md` - cart API, session korpa i JS dio
- `04-features/CHECKOUT_ORDER_NOTES.md` - checkout, validacija, zalihe, transakcija i order success
- `04-features/CONTENT_ROLES_NEWS_NOTES.md` - editable content, role sistem i news slug URL
- `04-features/FEATURE_NOTES.md` - dashboard, flash poruke i error stranice
- `04-features/NEWS_PAGE_NOTES.md` - refactor news stranice i news detail flow

## 05 Frontend

CSS i frontend organizacija.

- `05-frontend/CSS_STRUCTURE.md` - public/admin CSS struktura i kako su stilovi razdvojeni

## Sta jos eventualno dodati

Dokumentacija je sada vec dobro pokrivena. Ako se bude dalje sredjivalo, korisni novi dokumenti bi bili:

- `DATABASE_NOTES.md` - glavne tabele i relacije
- `ADMIN_PANEL_NOTES.md` - sta moze admin, sta moderator
- `PRODUCT_SHOP_NOTES.md` - workflow proizvoda, kategorija, slika, stocka i shop prikaza
- `PRODUCTION_CHECKLIST.md` - sta uraditi prije deploya
