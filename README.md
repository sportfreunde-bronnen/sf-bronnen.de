# sf-bronnen.de

sourcecode for the website sf-bronnen.de

## Folder structure

|Folder|Description|
|---|---|
|frontend/|frontend stylesheets built with bootstrap4, sass (Dart Sass) and postcss/autoprefixer|
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

The build (`frontend/build.mjs`) needs Node.js 20.19 or newer.

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
(production: `svz.sf-bronnen.de`, local: `svz.sf-bronnen.de.test`) by the plugin `site/plugins/sfb-svz`.
The subdomain must point to the same document root (`public/`) as the main domain, no further webserver configuration is needed.

- `svz.<domain>/` renders the SVZ page with an own header (no main navigation of the club site, with a link back to the club site); the footer stays.
- `<domain>/gesamtverein/allgemeines/svz` (and its subpages) is redirected permanently to the subdomain, every link to the page (navigation, footer, ...) points to the subdomain.
- `svz.<domain>/anfrage` is the own contact form of the SVZ (subpage `anfrage` of the SVZ page, see below); the old URL `<domain>/gesamtverein/allgemeines/svz/anfrage` is redirected to it.
- Every other path on the subdomain is redirected to the same path on the main domain, links in the SVZ site which leave the SVZ site (footer, ...) point to the main domain.
- Requests to any other host (e.g. `localhost:1949`) behave as before.

The domain is configurable in the (gitignored) `site/config/config.php`:

```php
// host of the SVZ site (without scheme and port), default: 'svz.sf-bronnen.de'
// empty = own domain disabled, the page is served below the main domain as usual
'sfb.svz.host' => 'svz.sf-bronnen.de.test',
// optional, URL of the main site, default: the SVZ host without its first label (svz.sf-bronnen.de -> sf-bronnen.de)
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
