<?php

use Kirby\Toolkit\Str;

/**
 * Class DvosPlayerStatisticsParser
 *
 * Turns the HTML of the player statistics ("Spielerauswertung", ligaauswertung.php) of the DVOS (Dartverband
 * Oberschwaben) into a structured array. The page has no real tables but "div tables" (div.tab > div.row > div.cell)
 * and shows every competition ("Liga", "Pokal") three times (narrow, medium and wide variant for the different screen
 * sizes). Only the variant with the most columns is evaluated, because it contains all values.
 *
 * Result:
 *
 * [
 *   'club' => 'Sportfreunde Bronnen e. V.',
 *   'competitions' => [
 *     'liga' => [
 *       'title' => 'Liga',
 *       'players' => [
 *         [
 *           'id' => 459, 'rank' => 1, 'name' => 'Elias Mäschle', 'firstName' => 'Elias', 'lastName' => 'Mäschle',
 *           'category' => 'junioren', 'active' => true, 'squad' => 'Sportfreunde Bronnen I',
 *           'points' => 21, 'games' => 12, 'wins' => 11, 'quote' => 91.7,
 *           'single' => ['points' => 20, 'games' => 10, 'wins' => 10, 'quote' => 100.0],
 *           'double' => [...], 'teamgame' => [...] (cup only, otherwise null),
 *           'count180' => 4, 'shortgame' => ['count' => 4, 'best' => 17], 'highfinish' => ['count' => 2, 'best' => 109],
 *         ],
 *       ],
 *     ],
 *   ],
 * ]
 *
 * Values the DVOS leaves empty are null.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
final class DvosPlayerStatisticsParser
{
    /** Normalized header of the column => key in the player array (dots: nested array) */
    private const COLUMNS = [
        'platz' => 'rank',
        'name' => 'name',
        'mannschaft' => 'squad',
        'punkte' => 'points',
        'spiele' => 'games',
        'siege' => 'wins',
        'quote' => 'quote',
        'einzelpunkte' => 'single.points',
        'einzelspiele' => 'single.games',
        'einzelsiege' => 'single.wins',
        'einzelquote' => 'single.quote',
        'doppelpunkte' => 'double.points',
        'doppelspiele' => 'double.games',
        'doppelsiege' => 'double.wins',
        'doppelquote' => 'double.quote',
        'teamgamepunkte' => 'teamgame.points',
        'teamgamespiele' => 'teamgame.games',
        'teamgamesiege' => 'teamgame.wins',
        'teamgamequote' => 'teamgame.quote',
        'anzahl180' => 'count180',
        'anzahlshortgame' => 'shortgame.count',
        'bestesshortgame' => 'shortgame.best',
        'anzahlhighfinish' => 'highfinish.count',
        'besteshighfinish' => 'highfinish.best',
    ];

    /** Marker (css class) in front of the name => category; the DVOS explains them in its legend */
    private const CATEGORIES = [
        'dot-red' => 'damen',
        'dot-green' => 'junioren',
        'dot-black' => 'juniorinnen',
        'dot' => 'herren',
    ];

    /** Marker for players which are not registered anymore ("nicht mehr gemeldet") */
    private const INACTIVE_MARKER = 'dot-bwdv';

    /**
     * Parses the HTML of the player statistics. Returns no competitions if the page has an unknown structure.
     *
     * @return array{club: string, competitions: array<string, array{title: string, players: array}>}
     */
    public static function parse(string $html): array
    {
        $result = ['club' => '', 'competitions' => []];

        if (trim($html) === '') {
            return $result;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        // the prefix makes sure that the document is read as UTF-8
        $document->loadHTML('<?xml encoding="utf-8"?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        $column = $xpath->query('//div[' . self::hasClass('content') . ']/div[' . self::hasClass('spalte') . ']')->item(0);

        if (!$column instanceof DOMElement) {
            return $result;
        }

        $title = '';
        // competition => the table with the most columns
        $tables = [];

        foreach ($column->childNodes as $node) {
            if (!$node instanceof DOMElement || $node->tagName !== 'div') {
                continue;
            }

            if (self::elementHasClass($node, 'fett')) {
                $text = self::text($node);
                if (self::elementHasClass($node, 'text-mittel')) {
                    $title = $text;
                } elseif ($result['club'] === '') {
                    $result['club'] = $text;
                }
                continue;
            }

            foreach ($xpath->query('.//div[' . self::hasClass('tab') . ']', $node) as $table) {
                $parsed = self::parseTable($xpath, $table);
                if ($parsed === null) {
                    continue;
                }

                $key = Str::slug($title) ?: 'statistik';
                if (!isset($tables[$key]) || $parsed['columns'] > $tables[$key]['columns']) {
                    $tables[$key] = $parsed + ['title' => $title !== '' ? $title : 'Statistik'];
                }
            }
        }

        foreach ($tables as $key => $table) {
            $result['competitions'][$key] = ['title' => $table['title'], 'players' => $table['players']];
        }

        return $result;
    }

    /**
     * @return array{columns: int, players: array}|null
     */
    private static function parseTable(DOMXPath $xpath, DOMElement $table): ?array
    {
        $rows = $xpath->query('./div[' . self::hasClass('row') . ']', $table);
        if ($rows->length < 2) {
            return null;
        }

        // the header row defines the column of every value
        $keys = [];
        foreach (self::cells($xpath, $rows->item(0)) as $index => $cell) {
            $header = strtolower(preg_replace('/[^a-z0-9]/i', '', self::text($cell)));
            $keys[$index] = self::COLUMNS[$header] ?? null;
        }

        if (!in_array('name', $keys, true)) {
            return null;
        }

        $players = [];
        for ($i = 1; $i < $rows->length; $i++) {
            $player = self::parsePlayer($xpath, $rows->item($i), $keys);
            if ($player !== null) {
                $players[] = $player;
            }
        }

        return $players === [] ? null : ['columns' => count(array_filter($keys)), 'players' => $players];
    }

    private static function parsePlayer(DOMXPath $xpath, DOMElement $row, array $keys): ?array
    {
        $cells = self::cells($xpath, $row);
        $player = [
            'id' => null,
            'rank' => null,
            'name' => '',
            'firstName' => '',
            'lastName' => '',
            'category' => null,
            'active' => true,
            'squad' => null,
            'points' => null,
            'games' => null,
            'wins' => null,
            'quote' => null,
            'single' => null,
            'double' => null,
            'teamgame' => null,
            'count180' => null,
            'shortgame' => null,
            'highfinish' => null,
        ];

        foreach ($keys as $index => $key) {
            if ($key === null || !isset($cells[$index])) {
                continue;
            }

            $cell = $cells[$index];
            $text = self::text($cell);

            if ($key === 'name') {
                self::applyName($xpath, $player, $cell, $text);
            } elseif ($key === 'squad') {
                $player['squad'] = $text !== '' ? $text : null;
            } else {
                $value = self::number($text, str_ends_with($key, 'quote'));
                if (str_contains($key, '.')) {
                    [$group, $field] = explode('.', $key);
                    $player[$group] ??= [];
                    $player[$group][$field] = $value;
                } else {
                    $player[$key] = $value;
                }
            }
        }

        return $player['name'] !== '' ? $player : null;
    }

    /**
     * The name cell holds "Lastname, Firstname", the link to the player (id) and the category marker
     */
    private static function applyName(DOMXPath $xpath, array &$player, DOMElement $cell, string $text): void
    {
        $link = $xpath->query('.//a[@href]', $cell)->item(0);
        if ($link instanceof DOMElement && preg_match('/[?&]id_s=(\d+)/', $link->getAttribute('href'), $match)) {
            $player['id'] = (int)$match[1];
        }

        foreach ($xpath->query('.//div[' . self::hasClass('dot') . ' or contains(@class, "dot-")]', $cell) as $marker) {
            if (self::elementHasClass($marker, self::INACTIVE_MARKER)) {
                $player['active'] = false;
                continue;
            }
            foreach (self::CATEGORIES as $class => $category) {
                if (self::elementHasClass($marker, $class)) {
                    $player['category'] = $category;
                    break;
                }
            }
        }

        $parts = array_map('trim', explode(',', $text, 2));
        $player['lastName'] = $parts[0];
        $player['firstName'] = $parts[1] ?? '';
        $player['name'] = trim($player['firstName'] . ' ' . $player['lastName']);
    }

    /**
     * "91,7 %" => 91.7, "12 " => 12, "" => null
     */
    private static function number(string $text, bool $decimal): int|float|null
    {
        $text = str_replace(['%', ' '], '', $text);
        $text = str_replace(',', '.', $text);

        if (!is_numeric($text)) {
            return null;
        }

        return $decimal ? (float)$text : (int)$text;
    }

    /**
     * @return DOMElement[]
     */
    private static function cells(DOMXPath $xpath, DOMElement $row): array
    {
        $cells = [];
        foreach ($xpath->query('./div[' . self::hasClass('cell') . ']', $row) as $cell) {
            $cells[] = $cell;
        }

        return $cells;
    }

    /**
     * Text of an element, whitespace (including non-breaking spaces) collapsed
     */
    private static function text(DOMNode $node): string
    {
        $text = str_replace("\u{00A0}", ' ', $node->textContent);

        return trim(preg_replace('/\s+/u', ' ', $text));
    }

    private static function elementHasClass(DOMElement $element, string $class): bool
    {
        return in_array($class, preg_split('/\s+/', trim($element->getAttribute('class'))), true);
    }

    private static function hasClass(string $class): string
    {
        return "contains(concat(' ', normalize-space(@class), ' '), ' $class ')";
    }
}
