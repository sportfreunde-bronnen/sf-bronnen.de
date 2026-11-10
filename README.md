# sf-bronnen.de

sourcecode for the website sf-bronnen.de

## Folder structure

|Folder|Description|
|---|---|
|frontend/|frontend stylesheets built with Bootstrap 5, sass (Dart Sass) and postcss/autoprefixer|
|kirby/|kirby CMS|
|content/|page content|


## Instructions
```bash
# 1. Install dependencies
cd ./frontend
npm install

#2 Start dev mode (watching for changes)
cd ./frontend
npm run dev

#3 Or build the stylesheets once (src/sass/*.scss -> public/assets/css/*.css)
cd ./frontend
npm run build
```

The build (`frontend/build.mjs`) needs Node.js 20.19 or newer. Besides the stylesheets it copies the JavaScript of Bootstrap (`bootstrap.bundle.min.js`, including Popper)
to `public/assets/js`, so scripts and styles always have the same version.

### Grid breakpoints

Besides the Bootstrap breakpoints `sm` (576px), `md` (768px), `lg` (992px) and `xl` (1200px) the grid has the additional breakpoint `xxl`.
It can be used like the others (`col-xxl-*`, `d-xxl-*`, `ps-xxl-*`, `media-breakpoint-up(xxl)`, ...).
Both values are configured in `frontend/src/sass/_variables.scss`:

|Variable|Default|Description|
|---|---|---|
|`$breakpointXxl`|`1500px`|viewport width from which the breakpoint `xxl` applies|
|`$containerMaxWidthXxl`|`1300px`|max width of the `.container` from the breakpoint `xxl` on|

Run `npm run build` after a change.

## Header variants (classic / modern)

The header and the main navigation exist in two variants:

- `classic` (default): dark blue header with the crest, light blue navigation bar (`snippets/header.php`, `snippets/navigation.php`)
- `modern`: graphite info bar with the social links and the department, below it a sticky, frosted main bar with the crest, the name and the menu
  (dropdowns as cards, compact bar and shadow while scrolling, own mobile menu with the social links)
  (`snippets/header-modern.php`, `snippets/navigation-modern.php`, `frontend/src/sass/components/_header-modern.scss`)

Both variants show the crest of the club or, on the pages of the darts department, the crest of the darts department, and both show the social links
(`snippets/social-links.php`). The variant is chosen in the (gitignored) `site/config/config.php`:

```php
// 'classic' (default) or 'modern'
'sfb.header' => 'modern',
```

The nested notation (`'sfb' => ['header' => 'modern']`) is supported as well. For a quick comparison a single request can be switched with
`?header=modern` or `?header=classic` (the query parameter wins over the configuration, it is not kept on further page views).
The Sportvereinszentrum site (own domain) always uses its own header.

## Event pages ("Veranstaltung")

The detail page of an event (`site/templates/veranstaltung.php`, `frontend/src/sass/components/_veranstaltung.scss`) follows the design of the article page:

- header with back link to the events on the start page (`/#veranstaltungen`), area chip, title and the short description, followed by the event image (position "Über dem Text", default) as cover
  (position "Unter dem Text" shows the image below the text)
- the text next to a card "Kurz und knapp" with date, time, place and the optional download (the card is shown above the text on small screens);
  events which are over show a hint (`VeranstaltungPage::isPast()`)
- the recommendation ("Auch interessant") is shown as event card (`snippets/event-card.php`, `frontend/src/sass/components/_event-card.scss`, shared with the start page)

Header, cover, text and recommendation share their styles with the article page (`components/_bericht.scss`, selector `.page--veranstaltung`).

## Text pages with content blocks (template "Default Page")

The default template (`site/templates/default.php`, blueprint `site/blueprints/pages/default.yml`) shows the headline, the classic text and the field "Inhalt"
(Kirby blocks) in a white content card, e.g. the page "Sportgelände". The field offers these blocks (blueprints in `site/blueprints/blocks/`, markup in `site/snippets/blocks/`):

|Block|Description|
|---|---|
|Überschrift|section heading (H2 to H4)|
|Text|formatted text with links, bold/italic text and lists|
|Bild|single image with caption, full width or medium sized|
|Text mit Bild|text and image side by side (image left or right), the image is above the text on small screens|
|Galerie|grid of equally sized thumbnails with 2, 3 or 4 columns; the description of the image file is shown as caption in the lightbox|

- The images must be uploaded to the page first (files section of the page), the blocks select them from there.
- A click on an image opens it in the lightbox (Magnific Popup, initialised in `public/assets/js/custom.js`); the images of a gallery can be browsed.
- Pages without blocks look as before: headline, classic text (field "Text (klassisch)") and all images of the page as grid below the text.
  A page with blocks shows the classic text above the blocks and places the images only via the blocks.
- The styles are in `frontend/src/sass/components/_blocks.scss` (run `npm run build` after a change), the image markup shared by the blocks is `snippets/block-image.php`.
- Hidden blocks (eye icon in the Panel) are not rendered.

## Spam protection (Cloudflare Turnstile)

The contact form and the membership certificate form are protected by
[Cloudflare Turnstile](https://developers.cloudflare.com/turnstile/) (plugin `site/plugins/sfb-turnstile`).
The widget is rendered with `snippet('turnstile', ['action' => '...'])`; the token is verified server-side
(siteverify) by the `turnstileGuard` in the controller before the form action runs.

Add the secret key of the widget to the (gitignored) `site/config/config.php`. Until it is set, the protected forms reject every submission.

```php
'sfb.turnstile.secret' => '<secret key from the Cloudflare dashboard>',
// optional, defaults to ['sf-bronnen.de', 'www.sf-bronnen.de', 'sf-bronnen.de.test']
// for local development add 'localhost' here, never in the production config:
// 'sfb.turnstile.hostnames' => ['localhost'],
```

The same options can also be written as nested array (`'sfb' => ['turnstile' => ['secret' => '...', 'hostnames' => [...]]]`);
the guard reads both notations. The public site key is `sfb.turnstile.sitekey` (default is set in the plugin).

## Sportvereinszentrum (SVZ) on its own subdomain

The page "Sportvereinszentrum" (`gesamtverein/allgemeines/svz`) is served on its own subdomain
(production: `vermietung.sf-bronnen.de`, local: `vermietung.sf-bronnen.de.test`) by the plugin `site/plugins/sfb-svz`.
The subdomain must point to the same document root (`public/`) as the main domain, no further webserver configuration is needed.

- `svz.<domain>/` renders the SVZ page with an own header (no main navigation of the club site, with a link back to the club site); the footer stays.
- `<domain>/gesamtverein/allgemeines/svz` (and its subpages) is redirected permanently to the subdomain, every link to the page (navigation, footer, ...) points to the subdomain.
- `svz.<domain>/anfrage` is the own contact form of the SVZ (subpage `anfrage` of the SVZ page, see below); the old URL `<domain>/gesamtverein/allgemeines/svz/anfrage` is redirected to it.
- Every other path on the subdomain is redirected to the same path on the main domain, links in the SVZ site which leave the SVZ site (footer, ...) point to the main domain.
- Every page of the SVZ site (start page, `/anfrage`, further subpages) carries `<link rel="canonical">` pointing to its URL on the subdomain (without query string), see `SvzDomain::canonicalUrl()` and `site/snippets/header.php`. Other pages and requests to any other host get no canonical tag.
- Requests to any other host (e.g. `localhost:1949`) behave as before.

The domain is configurable in the (gitignored) `site/config/config.php`:

```php
// host of the SVZ site (without scheme and port), default: 'vermietung.sf-bronnen.de'
// empty = own domain disabled, the page is served below the main domain as usual
'sfb.svz.host' => 'vermietung.sf-bronnen.de.test',
// optional, URL of the main site, default: the SVZ host without its first label (vermietung.sf-bronnen.de -> sf-bronnen.de)
// 'sfb.svz.mainUrl' => 'https://www.sf-bronnen.de',
// optional, id of the SVZ page, default: 'gesamtverein/allgemeines/svz'
// 'sfb.svz.page' => 'gesamtverein/allgemeines/svz',
// recipient of the requests of the SVZ contact form, as long as it is empty the form rejects every submission
'sfb.svz.recipient' => 'svz@example.com',
```

The nested notation (`'sfb' => ['svz' => ['host' => '...', 'recipient' => '...']]`) is supported as well.

### SVZ contact form

The form (`site/templates/svzcontact.php`, `site/controllers/svzcontact.php`) asks for first name, last name, e-mail, phone, message
and additionally for the date, the package (room or beverage package), active member (yes/no) and service staff (yes/no).
The request is mailed to `sfb.svz.recipient` (action `SvzMailAction` in `site/plugins/uniform-actions`) using the SMTP account of the main contact form.
The form is protected by Cloudflare Turnstile (action `svz-contact`); the SVZ host is accepted as Turnstile hostname automatically.

A click on a day of the availability calendar shows the button "Den xx.xx.xxxx direkt anfragen" below the calendar, which opens the form
with the date preselected (`/anfrage?datum=YYYY-MM-DD`). Texts of the form page are editable in the panel (page "Anfrage" below the SVZ page).

## Digital membership application ("Mitglied werden")

The page "Mitglied werden" (`gesamtverein/mitglieder/mitglied-werden`, template `membership`) offers the membership application as an online form
(`site/templates/membership.php`, `site/controllers/membership.php`, `public/assets/js/membership.js`, `frontend/src/sass/membership.scss`).
After the submission the PDF form `data/beitrittserklarung_stand_03.2026.pdf` (Beitrittserklärung und Vollmacht) is filled in automatically and sent
as attachment, together with a short summary of the data, to the e-mail address of the membership administration. The mail is sent with the SMTP account
of the main contact form (action `MembershipMailAction`), the applicant is set as reply-to address. The form is protected by Cloudflare Turnstile
(action `membership`), see above.

The recipient is configurable in the (gitignored) `site/config/config.php`, as long as it is empty the form rejects every submission:

```php
// e-mail address of the membership administration which receives the applications
'sfb.membership.recipient' => 'mitglieder@example.com',
// optional, path of the PDF form, default: data/beitrittserklarung_stand_03.2026.pdf in the project folder
// 'sfb.membership.template' => '/path/to/beitrittserklaerung.pdf',
```

The nested notation (`'sfb' => ['membership' => ['recipient' => '...']]`) is supported as well.

What is filled in:

- personal data (name, address, date of entry (default: today), date of birth, e-mail, phone), the groups / the passive membership and the fee class are entered or checked in the form
- additional family members (only for the fee class "Familienbeitrag", up to 10 with name, date of birth and group) are written into the line "weitere Familienmitglieder" as far as they fit; all of them are listed on an appendix page (page 4)
- the SEPA direct debit mandate (IBAN with checksum validation, BIC, bank, account holder)
- place and date (place from the form, today's date) and the signatures: they are generated from the entered names with a handwriting font
  (signature of the member on the data protection page, signature of the account holder on the SEPA mandate)

The PDF has no form fields, therefore the texts are written at fixed positions on top of the original pages (`site/plugins/sfb-membership/src/MembershipPdf.php`).
**If the PDF form is replaced by a new version, the coordinates in `MembershipPdf` and the groups / fee classes in `MembershipApplication` have to be adapted.**
The groups (with their group numbers) are defined in `MembershipApplication::DEPARTMENTS`.

The PDF is created with [FPDI](https://github.com/Setasign/FPDI) and [TCPDF](https://tcpdf.org) (`composer install`). The signature font
"Herr Von Muellerhoff" (SIL Open Font License, see `site/plugins/sfb-membership/fonts/OFL.txt`) is converted to the TCPDF font format
(`TCPDF_FONTS::addTTFfont`), the converted files are part of the repository. The IBAN is only partly readable in the e-mail (it is complete in the PDF).
The texts of the page are editable in the panel (page "Mitglied werden" below "Mitglieder").

## Partnership request of the Förderverein ("Partnerschaft")

The page "Partnerschaft" (`gesamtverein/foerderverein/partnerschaft`, template `partnership`) replaces the PDF form "Vordruck Werbemöglichkeiten
Förderverein Sportfreunde Bronnen e.V." (`site/templates/partnership.php`, `site/controllers/partnership.php`, `frontend/src/sass/partnership.scss`).
Companies and private persons choose the offers they are interested in (Bandenwerbung, Bannerwerbung, Pflegepatenschaft, Spende, ...), enter company, contact person,
address, phone and e-mail and confirm the data protection consent (replaces "Datum, Unterschrift", the date of the request is added automatically).
The request is sent as e-mail (summary of all data, the sender is set as reply-to address) to the Förderverein. The mail is sent with the SMTP account of the
main contact form (action `PartnershipMailAction`, plugin `site/plugins/sfb-partnership`). The form is protected by Cloudflare Turnstile (action `partnership`), see above.
The design is shared with the membership application (`membership.css`), `partnership.css` only adds the list of the offers.

The recipient is configurable in the (gitignored) `site/config/config.php`, as long as it is empty the form rejects every submission:

```php
// e-mail address which receives the partnership requests
'sfb.partnership.recipient' => 'foerderverein@example.com',
```

The nested notation (`'sfb' => ['partnership' => ['recipient' => '...']]`) is supported as well.

The offers are defined in `PartnershipRequest::INTERESTS` (plugin `sfb-partnership`). **If the offers of the Förderverein change, this list has to be adapted.**
The texts of the page and the contact person are editable in the panel (page "Partnerschaft" below "Förderverein").

## Fee calculator ("Beitragsrechner")

The page "Beiträge" (`gesamtverein/allgemeines/beitraege`, template `beitraege`) shows a calculator below the fee cards where interested people can calculate their annual fee
(`site/templates/beitraege.php`, `site/models/beitraege.php`, `public/assets/js/fee-calculator.js`, `frontend/src/sass/fee-calculator.scss`).
The calculator is a progressive enhancement: it is hidden until the script runs.

The amounts are not hard-coded: `BeitraegePage::calculator()` reads them from the structure field "Beiträge" of the page (panel), so a change of the fees is picked up automatically.
The rows of the structure field are assigned to the fee classes by a part of their name (`jugend`, `erwachsen`, `famil`, `passiv`, case-insensitive, see `BeitraegePage::FEE_CLASSES`).
If a row is missing, the calculator is not shown.

Calculation (per year):

- Jugendlich / Erwachsen: base fee + the fees of the chosen departments (several departments can be chosen)
- Familie: base fee once per family (pF) + the department fees per head (pK) for every family member, each member (including the main person) chooses his or her own departments, up to 11 members
- Passiv: base fee only, a passive member cannot choose a department
- Aikido is included in the total, with a note that it is collected by a separate direct debit mandate (`BeitraegePage::DEPARTMENTS`, `separate`)
- A department is only offered if its fee in the fee class is greater than zero

## External menu links ("Externer Link")

A menu entry can point to an external page, e.g. "Spielbetrieb" > "Erste Mannschaft" of the darts department opens `https://www.dvos.de/liga.php?a_liga=53`.
In the panel create a subpage with the template "Externer Link" (`site/blueprints/pages/link.yml`) and enter the target in the field "Link-Ziel";
the toggle "In neuem Tab öffnen" (default: yes) adds `target="_blank" rel="noopener"` to the menu entry. The page has to be "listed" to appear in the menu.

`LinkPage::url()` (`site/models/link.php`) returns the external target, so the main navigation (`site/snippets/navigation.php`, `site/snippets/navigation-modern.php`)
and the footer link directly to the external page. The template (`site/templates/link.php`) is only a fallback: it redirects if the internal URL of the page is called directly.
A link page which has subpages of its own is shown as group heading (like "Allgemeines"), not as link.

## Player statistics of the darts department ("Spielerstatistik")

The page "Darts" > "Spielbetrieb" > "Spielerstatistik" (`content/6_darts/3_spielbetrieb/2_spielerstatistik`, template "Spielerstatistik", `site/blueprints/pages/spielerstatistik.yml`)
shows the statistics of the players of "Liga" and "Pokal": the best players as cards (most points, wins, best quote, 180s, highest high finish) and a table with all values
(points, games, wins, quote, single / double / team game, 180s, high finishes, short games). The style is in `frontend/src/sass/spielerstatistik.scss` (`public/assets/css/spielerstatistik.css`).

The data only exists as HTML on the website of the DVOS (Dartverband Oberschwaben), so it is requested in the background (plugin `site/plugins/sfb-dvos`):

- `POST https://www.dvos.de/ligaauswertung.php?seite=0` with `input[2]=<id of the club>`, the id of the Sportfreunde Bronnen is `71` (field "Verein beim DVOS" of the page)
- `DvosPlayerStatisticsParser` turns the "div tables" of the page into an array (the DVOS sends every table in three widths, only the widest one with all columns is used)
- `DvosPlayerStatistics::forClub()` caches the prepared data **for 12 hours** with the Kirby cache `sfb.dvos.playerstatistics` (stored as JSON in `storage/cache`).
  The cache is activated by the plugin itself, nothing has to be added to the (gitignored) `config.php`
- If the DVOS cannot be reached (or answers with an unknown page) the last good data is used for up to two weeks and the DVOS is requested again after 10 minutes only.
  Without any data the page shows a note with a link to the DVOS

Options (all optional, in the `config.php`):

```php
'sfb.dvos.cacheMinutes' => 12 * 60,                // lifetime of the prepared statistics
'sfb.dvos.timeout' => 10,                          // seconds until a request to the DVOS is aborted
'sfb.dvos.cache.playerstatistics' => false,        // switch the cache off, e.g. while developing
```

To refresh the data immediately delete the folder `storage/cache/<host>/sfb/dvos` (the folder name depends on the host name).

## Role based page permissions ("Seitenrechte")

Kirby only knows page permissions (`access`, `list`, `read`, `update`, ...) per user role **and page template**, they are never page specific and are not inherited by subpages.
The plugin `site/plugins/sfb-roles` adds rules per page tree: admins decide in the panel which pages (including **all** their subpages, also those created later) are restricted for a role.
Everything else keeps the permissions of the role blueprint. The public website is not affected, pages stay visible for visitors.

### Setup

1. Create a role as blueprint `site/blueprints/users/<role>.yml`. The role "Redakteur" (`site/blueprints/users/editor.yml`) is an example: panel access without system, users and languages.
   The page permissions of the role (`pages`, `files`) stay in the role blueprint, the plugin only restricts them for the selected pages.
2. Open the site page of the panel and the tab "Seitenrechte". Every rule consists of a **role**, a **level** and the **pages** (multiple selection). The rules are stored in `content/site.txt` (field `rolePermissions`, pages are referenced by UUID),
   so they stay valid if a page is renamed or moved. Only admins can change them (`options.update` in `site/blueprints/site.yml`).

| Level | Effect for the role |
|-------|---------------------|
| Nur ansehen (`readonly`) | The page is visible, but cannot be changed: edit, title, slug, status, template, sort, duplicate, move, delete and creating subpages are rejected by the server. The same applies to the files of the page (upload, replace, rename, edit, sort, delete). Other pages cannot be moved below it. In the panel the form and the actions are disabled. |
| Ausblenden (`hidden`) | The page is invisible in the panel (page tree, lists, search, page picker) and cannot be opened by its panel URL ("not found"), neither can its files. |

If a page is affected by several rules the stricter level wins (Ausblenden before Nur ansehen). Rules for deleted pages, unknown roles or invalid levels are ignored.
The admin is never restricted, neither are visitors who are not logged in.

### How it works

- `RoleAwarePage` (`site/plugins/sfb-roles/src/RoleAwarePage.php`) is the base class of all page models (registered as `default` model, the models in `site/models` extend it).
  It overrides `isAccessible()` and `isListable()` (level "hidden") and returns `RolePagePermissions` as `permissions()` (level "readonly", all write actions are denied).
- `RolePageAccess` resolves the rules once per request and role (page IDs of the selected pages) and decides by the ID prefix, so subpages are covered without any further configuration.
- Kirby has no file models: the hooks `file.*:before` (create, replace, update, delete, changeName, changeSort, changeTemplate) and `page.move:before` in `site/plugins/sfb-roles/index.php` cover the files and moving pages.

### Limits

- No exceptions inside a restricted tree: a subpage below a read only page cannot be made editable again.
- Rights for users, system and languages stay in the role blueprint.
- The home and error page can be hidden as well. This is possible, but the role then cannot reach them in the panel.
- Kirby 6 is going to remove the page permission `read`. The plugin does not use it, it overrides `isAccessible()` and `isListable()`. After an update to Kirby 6 please check these overrides and the hooks.
