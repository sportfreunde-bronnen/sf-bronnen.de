<?php

use Kirby\Cms\App;
use Kirby\Http\Remote;

require_once __DIR__ . '/DvosPlayerStatisticsParser.php';
require_once __DIR__ . '/DvosPlayerDetailsParser.php';

/**
 * Class DvosPlayerStatistics
 *
 * Player statistics of a club from the website of the DVOS (Dartverband Oberschwaben). The DVOS offers the data
 * as HTML only, so the page is requested in the background, parsed (see DvosPlayerStatisticsParser) and cached
 * with the Kirby cache 'sfb.dvos.playerstatistics' (activated by the plugin sfb-dvos, the lifetime is the option
 * 'sfb.dvos.cacheMinutes', 12 hours by default). The cache stores the prepared data as JSON.
 *
 * If the DVOS is not reachable the last data which could be fetched is still used and
 * the DVOS is requested again after a short pause only, so a failing DVOS does not slow down every page view.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
final class DvosPlayerStatistics
{
    private const CACHE = 'sfb.dvos.playerstatistics';

    /** Cached data version; increment to rebuild entries without validated data and detail timestamps */
    private const DATA_VERSION = 3;

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
        if (is_array($data) && ($data['version'] ?? null) === self::DATA_VERSION) {
            return $data;
        }

        $fallback = $cache->get($key . '-fallback');
        $fallback = is_array($fallback) ? $fallback : null;

        if ($cache->get($key . '-retry') === true && $fallback !== null) {
            return $fallback;
        }

        if ($fallback !== null) {
            // Write the retry marker before the request in case the PHP process is interrupted.
            $cache->set($key . '-retry', true, self::RETRY_MINUTES);
        }

        try {
            $data = self::load($kirby, $clubId, $fallback);
        } catch (Throwable) {
            $data = null;
        }

        if ($data === null) {
            return $fallback;
        }

        $cache->remove($key . '-retry');
        $cache->set($key, $data, (int)$kirby->option('sfb.dvos.cacheMinutes'));
        $cache->set($key . '-fallback', $data, 0);

        return $data;
    }

    /**
     * Requests and prepares the statistics, null if the DVOS answers with an error or an unknown page
     */
    private static function load(App $kirby, int $clubId, ?array $fallback): ?array
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
            if (!self::isComplete($statistics)) {
                return null;
            }
        } catch (Throwable) {
            return null;
        }

        $playerIds = [];
        $expectedCompetitions = [];
        foreach ($statistics['competitions'] as $competitionKey => $competition) {
            foreach ($competition['players'] as $player) {
                if (($player['id'] ?? 0) > 0) {
                    $playerId = (int)$player['id'];
                    $playerIds[] = $playerId;
                    if (($player['games'] ?? 0) > 0) {
                        $expectedCompetitions[$playerId][$competitionKey] = true;
                    }
                }
            }
        }

        $details = self::loadPlayerDetails(
            array_values(array_unique($playerIds)),
            (string)$kirby->option('sfb.dvos.playerUrl'),
            max(1, (int)$kirby->option('sfb.dvos.playerConcurrency')),
            $expectedCompetitions
        );

        foreach ($statistics['competitions'] as &$competition) {
            foreach ($competition['players'] as &$player) {
                $id = (int)($player['id'] ?? 0);
                if ($id === 0) {
                    continue;
                }

                if (isset($details[$id]) && $details[$id]['data'] !== null) {
                    $player['details'] = $details[$id]['data'];
                } elseif (($details[$id]['failed'] ?? false) && ($previous = self::fallbackPlayerDetails($fallback, $id)) !== null) {
                    $player['details'] = $previous;
                }
            }
            unset($player);
        }
        unset($competition);

        return [
            'version' => self::DATA_VERSION,
            'clubId' => $clubId,
            'club' => $statistics['club'],
            'sourceUrl' => self::sourceUrl($clubId),
            'fetchedAt' => time(),
            'competitions' => $statistics['competitions'],
        ];
    }

    /** Reject changed or partially parsed club tables before they can replace the last good cache entry. */
    private static function isComplete(array $statistics): bool
    {
        $requiredColumns = [
            'rank', 'name', 'squad', 'points', 'games', 'wins', 'quote',
            'single.games', 'single.wins', 'single.quote',
            'double.games', 'double.wins', 'double.quote',
            'count180', 'shortgame.count', 'shortgame.best', 'highfinish.count', 'highfinish.best',
        ];
        $readableFields = ['rank', 'points', 'games', 'wins', 'quote'];

        if (($statistics['competitions'] ?? []) === []) {
            return false;
        }

        foreach ($statistics['competitions'] as $competition) {
            if (($competition['titleRecognized'] ?? false) !== true
                || array_diff($requiredColumns, $competition['columns'] ?? []) !== []) {
                return false;
            }

            $hasReadablePlayer = false;
            foreach ($competition['players'] ?? [] as $player) {
                $readableCount = 0;
                foreach ($readableFields as $field) {
                    if (is_int($player[$field] ?? null) || is_float($player[$field] ?? null)) {
                        $readableCount++;
                    }
                }
                if ($readableCount >= 3) {
                    $hasReadablePlayer = true;
                    break;
                }
            }

            if (!$hasReadablePlayer) {
                return false;
            }
        }

        return true;
    }

    /**
     * Requests unique player pages in bounded parallel batches. Failures are marked so the last cached details can
     * be reused without making a failed DVOS request invalidate the club statistics.
     *
     * @return array<int, array{data: ?array, failed: bool}>
     */
    private static function loadPlayerDetails(array $playerIds, string $url, int $concurrency, array $expectedCompetitions = []): array
    {
        $results = [];
        $queue = $playerIds;
        $concurrency = min(8, $concurrency);
        $deadline = microtime(true) + 30;

        while ($queue !== [] && microtime(true) < $deadline) {
            $batch = array_splice($queue, 0, $concurrency);
            $multi = curl_multi_init();
            $handles = [];

            foreach ($batch as $id) {
                $handle = curl_init($url . $id);
                $remaining = max(1, (int)(($deadline - microtime(true)) * 1000));
                curl_setopt_array($handle, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => 4,
                    CURLOPT_CONNECTTIMEOUT_MS => min(3000, $remaining),
                    CURLOPT_TIMEOUT_MS => min(8000, $remaining),
                    CURLOPT_USERAGENT => 'sf-bronnen.de DVOS statistics',
                ]);
                curl_multi_add_handle($multi, $handle);
                $handles[$id] = $handle;
            }

            $running = 0;
            do {
                $status = curl_multi_exec($multi, $running);
                if ($status !== CURLM_OK || $running === 0) {
                    break;
                }
                $remaining = $deadline - microtime(true);
                if ($remaining <= 0) {
                    break;
                }
                if (curl_multi_select($multi, min(1.0, $remaining)) === -1) {
                    usleep(100000);
                }
            } while (microtime(true) < $deadline);

            foreach ($handles as $id => $handle) {
                $content = curl_multi_getcontent($handle);
                $successful = curl_errno($handle) === 0 && $content !== false && curl_getinfo($handle, CURLINFO_HTTP_CODE) === 200;
                try {
                    $parsed = $successful ? DvosPlayerDetailsParser::parse($content) : null;
                    $emptyPage = $successful && $parsed === null && DvosPlayerDetailsParser::isEmptyPlayerPage($content);
                } catch (Throwable) {
                    $parsed = null;
                    $emptyPage = false;
                }

                $failed = !$successful || ($parsed === null && !$emptyPage);
                if ($parsed !== null && !self::isCompleteDetails($parsed, $expectedCompetitions[$id] ?? [])) {
                    $parsed = null;
                    $failed = true;
                }
                if ($parsed !== null) {
                    $parsed['fetchedAt'] = time();
                }
                $results[$id] = ['data' => $parsed, 'failed' => $failed];
                curl_multi_remove_handle($multi, $handle);
            }
            curl_multi_close($multi);
        }

        foreach ($queue as $id) {
            $results[$id] = ['data' => null, 'failed' => true];
        }

        return $results;
    }

    private static function fallbackPlayerDetails(?array $fallback, int $playerId): ?array
    {
        foreach ($fallback['competitions'] ?? [] as $competition) {
            foreach ($competition['players'] ?? [] as $player) {
                if ((int)($player['id'] ?? 0) === $playerId && is_array($player['details'] ?? null)) {
                    $details = $player['details'];
                    if (!isset($details['fetchedAt']) && isset($fallback['fetchedAt'])) {
                        $details['fetchedAt'] = (int)$fallback['fetchedAt'];
                    }
                    return $details;
                }
            }
        }

        return null;
    }

    /** Ensure every competition with club-level games has usable player-level totals and match data. */
    private static function isCompleteDetails(array $details, array $expectedCompetitions): bool
    {
        foreach ($expectedCompetitions as $competitionKey => $_) {
            $competition = $details['competitions'][$competitionKey] ?? null;
            $summary = $competition['summary']['total'] ?? null;

            if (!is_array($summary)) {
                return false;
            }
            foreach (['points', 'games', 'wins', 'quote'] as $field) {
                if (!is_int($summary[$field] ?? null) && !is_float($summary[$field] ?? null)) {
                    return false;
                }
            }
            if (($competition['games'] ?? []) === [] || ($competition['results'] ?? []) === []) {
                return false;
            }
        }

        return true;
    }
}
