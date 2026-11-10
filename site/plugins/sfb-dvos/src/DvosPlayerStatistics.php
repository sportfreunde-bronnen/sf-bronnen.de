<?php

use Kirby\Cms\App;
use Kirby\Http\Remote;

require_once __DIR__ . '/DvosPlayerStatisticsParser.php';

/**
 * Class DvosPlayerStatistics
 *
 * Player statistics of a club from the website of the DVOS (Dartverband Oberschwaben). The DVOS offers the data
 * as HTML only, so the page is requested in the background, parsed (see DvosPlayerStatisticsParser) and cached
 * with the Kirby cache 'sfb.dvos.playerstatistics' (activated by the plugin sfb-dvos, the lifetime is the option
 * 'sfb.dvos.cacheMinutes', 12 hours by default). The cache stores the prepared data as JSON.
 *
 * If the DVOS is not reachable the last data which could be fetched is still used (for up to two weeks) and
 * the DVOS is requested again after a short pause only, so a failing DVOS does not slow down every page view.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
final class DvosPlayerStatistics
{
    private const CACHE = 'sfb.dvos.playerstatistics';

    /** Lifetime of the copy which is used if the DVOS is not reachable (2 weeks) */
    private const FALLBACK_MINUTES = 60 * 24 * 14;

    /** Pause until the DVOS is requested again after a failed request */
    private const RETRY_MINUTES = 10;

    /** Public page of the DVOS with the statistics of a club (used as source link) */
    private const PUBLIC_URL = 'https://www.dvos.de/ligaauswertung.php?id_v=';

    /**
     * Public page of the DVOS with the statistics of the club, e.g. to link to it if the data is not available
     */
    public static function sourceUrl(int $clubId): string
    {
        return self::PUBLIC_URL . $clubId;
    }

    /**
     * Returns the player statistics of the club (id of the club at the DVOS) or null if they are not available:
     *
     * [
     *   'clubId' => 71, 'club' => 'Sportfreunde Bronnen e. V.', 'sourceUrl' => '...', 'fetchedAt' => 1700000000,
     *   'competitions' => ['liga' => ['title' => 'Liga', 'players' => [...]], 'pokal' => [...]],
     * ]
     *
     * @see DvosPlayerStatisticsParser for the structure of the players
     */
    public static function forClub(int $clubId, ?App $kirby = null): ?array
    {
        $kirby ??= App::instance();
        $cache = $kirby->cache(self::CACHE);
        $key = 'club-' . $clubId;

        $data = $cache->get($key);
        if (is_array($data)) {
            return $data;
        }

        $fallback = $cache->get($key . '-fallback');
        $fallback = is_array($fallback) ? $fallback : null;

        if ($cache->get($key . '-retry') === true) {
            return $fallback;
        }

        $data = self::load($kirby, $clubId);

        if ($data === null) {
            $cache->set($key . '-retry', true, self::RETRY_MINUTES);

            return $fallback;
        }

        $cache->set($key, $data, (int)$kirby->option('sfb.dvos.cacheMinutes'));
        $cache->set($key . '-fallback', $data, self::FALLBACK_MINUTES);

        return $data;
    }

    /**
     * Requests and prepares the statistics, null if the DVOS answers with an error or an unknown page
     */
    private static function load(App $kirby, int $clubId): ?array
    {
        try {
            $response = Remote::post((string)$kirby->option('sfb.dvos.url'), [
                'data' => ['input' => [2 => $clubId]],
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'],
                'timeout' => (int)$kirby->option('sfb.dvos.timeout'),
            ]);

            if ($response->code() !== 200) {
                return null;
            }

            $statistics = DvosPlayerStatisticsParser::parse((string)$response->content());
        } catch (Throwable) {
            return null;
        }

        // an error page of the DVOS or a changed layout must never replace good data
        if ($statistics['competitions'] === []) {
            return null;
        }

        return [
            'clubId' => $clubId,
            'club' => $statistics['club'],
            'sourceUrl' => self::sourceUrl($clubId),
            'fetchedAt' => time(),
            'competitions' => $statistics['competitions'],
        ];
    }
}
