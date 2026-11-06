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
