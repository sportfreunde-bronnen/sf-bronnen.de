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
