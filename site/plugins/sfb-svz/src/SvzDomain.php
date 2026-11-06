<?php

use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Http\Response;
use Kirby\Http\Route;
use Kirby\Toolkit\V;

/**
 * Serves the Sportvereinszentrum (SVZ) page on its own (sub)domain, e.g. svz.sf-bronnen.de.
 *
 * Options (see sfb-svz/index.php):
 * - sfb.svz.host:      host name of the SVZ site (without scheme and port), empty = feature disabled
 * - sfb.svz.mainUrl:   URL of the main site, optional (default: the SVZ host without its first label)
 * - sfb.svz.page:      id of the SVZ page
 * - sfb.svz.recipient: e-mail address which receives the requests of the SVZ contact form
 *
 * The feature is only active for requests to the SVZ host or to the main host. Requests to any
 * other host (e.g. localhost:1949 of the docker setup) behave exactly as before.
 */
class SvzDomain
{
    /**
     * Slug of the contact form page (subpage of the SVZ page). On the SVZ domain the form is
     * available as short URL: svz.sf-bronnen.de/anfrage
     */
    public const CONTACT_SLUG = 'anfrage';

    /**
     * Read an option of the sfb-svz plugin.
     *
     * Kirby's option() does not find a value that is written as nested array in the config.php
     * ('sfb' => ['svz' => ['host' => ...]]) because the defaults of the plugin are registered
     * under the key 'sfb.svz' and shadow it. Therefore all notations are supported (first match wins):
     * 1. flat key in the config.php: 'sfb.svz.host' => ...
     * 2. nested array in the config.php: 'sfb' => ['svz' => ['host' => ...]]
     * 3. default of the plugin (see sfb-svz/index.php)
     *
     * @param string $key Option name without the 'sfb.svz.' prefix
     * @param mixed $default
     * @return mixed
     */
    public static function option(string $key, $default = null)
    {
        $options = App::instance()->options();
        $flatKey = 'sfb.svz.' . $key;

        if (isset($options[$flatKey])) {
            return $options[$flatKey];
        }

        if (isset($options['sfb']) && is_array($options['sfb']) && isset($options['sfb']['svz'][$key])) {
            return $options['sfb']['svz'][$key];
        }

        if (isset($options['sfb.svz']) && is_array($options['sfb.svz']) && isset($options['sfb.svz'][$key])) {
            return $options['sfb.svz'][$key];
        }

        return $default;
    }

    /**
     * Configured host name of the SVZ site or null if the feature is disabled
     */
    public static function host(): ?string
    {
        $host = static::option('host');

        return is_string($host) ? static::normalizeHost($host) : null;
    }

    /**
     * Host name of the main site: from sfb.svz.mainUrl, otherwise the SVZ host without its first label
     * (svz.sf-bronnen.de -> sf-bronnen.de)
     */
    public static function mainHost(): ?string
    {
        $mainUrl = static::option('mainUrl');
        if (is_string($mainUrl) && trim($mainUrl) !== '') {
            return static::normalizeHost($mainUrl);
        }

        $host = static::host();
        if ($host === null) {
            return null;
        }

        return preg_replace('/^[^.]+\./', '', $host);
    }

    /**
     * Host name of the current request (lower case, without port)
     */
    public static function currentHost(): string
    {
        return static::normalizeHost((string) App::instance()->environment()->host());
    }

    /**
     * Is the current request addressed to the SVZ host?
     */
    public static function isSvzRequest(): bool
    {
        $host = static::host();

        return $host !== null && $host !== '' && static::currentHost() === $host;
    }

    /**
     * Is the current request addressed to the main host (with or without www)?
     */
    public static function isMainRequest(): bool
    {
        $mainHost = static::mainHost();

        return $mainHost !== null && $mainHost !== ''
            && static::withoutWww(static::currentHost()) === static::withoutWww($mainHost);
    }

    /**
     * Is the SVZ domain configured and is the current request addressed to the SVZ or the main host?
     */
    public static function enabled(): bool
    {
        return static::isSvzRequest() || (static::host() !== null && static::host() !== '' && static::isMainRequest());
    }

    /**
     * Id of the SVZ page
     */
    public static function pageId(): string
    {
        $id = static::option('page');

        return is_string($id) && trim($id, '/') !== '' ? trim($id, '/') : 'gesamtverein/allgemeines/svz';
    }

    /**
     * The SVZ page
     */
    public static function page(): ?Page
    {
        return App::instance()->site()->find(static::pageId());
    }

    /**
     * Id of the contact form page (subpage of the SVZ page)
     */
    public static function contactPageId(): string
    {
        return static::pageId() . '/' . static::CONTACT_SLUG;
    }

    /**
     * The contact form page
     */
    public static function contactPage(): ?Page
    {
        return App::instance()->site()->find(static::contactPageId());
    }

    /**
     * E-mail address which receives the requests of the SVZ contact form (option sfb.svz.recipient)
     * or null if it is not configured (or not a valid address).
     */
    public static function recipient(): ?string
    {
        $recipient = static::option('recipient');

        if (!is_string($recipient)) {
            return null;
        }

        $recipient = trim($recipient);

        return $recipient !== '' && V::email($recipient) ? $recipient : null;
    }

    /**
     * URL of the contact form, optionally with a preselected date (Y-m-d).
     * Falls back to the contact form of the club if the SVZ contact form page does not exist.
     */
    public static function contactUrl(?string $date = null): string
    {
        $page = static::contactPage();

        if ($page === null) {
            return static::link('home/kontakt') . '?rec=svz';
        }

        return $page->url() . ($date === null ? '' : '?' . http_build_query(['datum' => $date]));
    }

    /**
     * Does the path belong to the SVZ page (the page itself or one of its subpages)?
     */
    public static function isSvzPath(string $path): bool
    {
        $path = trim($path, '/');
        $id = static::pageId();

        return $path === $id || str_starts_with($path, $id . '/');
    }

    /**
     * Absolute URL on the SVZ domain
     */
    public static function url(string $path = ''): string
    {
        return static::absolute(static::host(), $path);
    }

    /**
     * Absolute URL on the main domain
     */
    public static function mainUrl(string $path = ''): string
    {
        return static::absolute(static::mainHost(), $path);
    }

    /**
     * Link to a page of the main site. On the SVZ domain an absolute URL on the main domain is
     * returned, everywhere else the root relative path (as before).
     */
    public static function link(string $path = ''): string
    {
        if (static::isSvzRequest()) {
            return static::mainUrl($path);
        }

        return '/' . ltrim($path, '/');
    }

    /**
     * URL of a page: on the SVZ domain every page except the SVZ page itself is located on the main domain.
     */
    public static function pageUrl(Page $page): string
    {
        if (static::isSvzRequest() && !static::isSvzPath($page->id())) {
            return static::mainUrl($page->isHomePage() ? '' : $page->uri());
        }

        return $page->url();
    }

    /**
     * Route action (catch all, runs after the routes of the Kirby core like panel, api and media)
     *
     * - SVZ domain, start page: render the SVZ page
     * - SVZ domain, /anfrage: render the contact form page
     * - SVZ domain, old URL of the SVZ page or the contact form page: redirect to the short URL
     * - SVZ domain, any other page: redirect to the main site
     * - main domain, URL of the SVZ page: redirect to the SVZ domain
     *
     * @param string|null $path
     * @return mixed
     */
    public static function route($path = null)
    {
        if (!static::enabled()) {
            Route::next();
        }

        $kirby = App::instance();
        $path = trim((string) $path, '/');
        $queryString = $kirby->request()->query()->toString();
        $query = $queryString === '' ? '' : '?' . $queryString;

        if (static::isSvzRequest()) {
            if ($path === '') {
                $page = static::page();
                if ($page === null) {
                    Route::next();
                }

                return $kirby->site()->visit($page);
            }

            if ($path === static::CONTACT_SLUG) {
                $contactPage = static::contactPage();

                if ($contactPage !== null) {
                    return $kirby->site()->visit($contactPage);
                }
            }

            if ($path === static::pageId()) {
                return Response::redirect(static::url() . '/' . $query, 301);
            }

            if ($path === static::contactPageId()) {
                return Response::redirect(static::url(static::CONTACT_SLUG) . $query, 301);
            }

            if (str_starts_with($path, static::pageId() . '/')) {
                // subpages of the SVZ page (e.g. old calendar links) are rendered as usual
                Route::next();
            }

            return Response::redirect(static::mainUrl($path) . $query, 302);
        }

        if (static::isSvzPath($path)) {
            if ($path === static::pageId()) {
                $target = static::url() . '/';
            } elseif ($path === static::contactPageId()) {
                $target = static::url(static::CONTACT_SLUG);
            } else {
                $target = static::url($path);
            }

            return Response::redirect($target . $query, 301);
        }

        Route::next();
    }

    /**
     * Build an absolute URL (scheme and port are taken from the current request)
     */
    protected static function absolute(?string $host, string $path = ''): string
    {
        $base = parse_url(App::instance()->url());
        $url = ($base['scheme'] ?? 'https') . '://' . $host;

        if (isset($base['port'])) {
            $url .= ':' . $base['port'];
        }

        $path = ltrim($path, '/');

        return $path === '' ? $url : $url . '/' . $path;
    }

    /**
     * Lower case host name without scheme, path and port
     */
    protected static function normalizeHost(string $host): string
    {
        $host = strtolower(trim($host));
        $host = preg_replace('#^[a-z][a-z0-9+.-]*://#', '', $host);
        $host = preg_replace('#[/?\#].*$#', '', $host);

        return preg_replace('/:\d+$/', '', $host);
    }

    protected static function withoutWww(string $host): string
    {
        return preg_replace('/^www\./', '', $host);
    }
}
