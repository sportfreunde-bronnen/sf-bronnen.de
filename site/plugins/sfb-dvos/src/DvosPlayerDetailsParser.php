<?php

/**
 * Parses the detailed statistics page of one player on the DVOS website.
 */
final class DvosPlayerDetailsParser
{
    private const SCORES = ['3:0', '3:1', '3:2', '2:3', '1:3', '0:3'];

    /**
     * Whether the DVOS returned its normal player page with an empty name (an unknown player id).
     */
    public static function isEmptyPlayerPage(string $html): bool
    {
        if (trim($html) === '') {
            return false;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        $document->loadHTML('<?xml encoding="utf-8"?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        $heading = $xpath->query('//div[' . self::hasClass('text-mittel') . ']')->item(0);
        if (!$heading instanceof DOMElement) {
            return false;
        }

        return preg_match('/^Auswertung für\s*(.*?)\s*Mannschaft:/u', self::text($heading), $match) === 1
            && trim($match[1]) === '';
    }

    /**
     * Returns null for an unknown player or an unexpected page structure.
     *
     * @return array{name: string, competitions: array<string, array>}|null
     */
    public static function parse(string $html): ?array
    {
        if (trim($html) === '') {
            return null;
        }

        $previous = libxml_use_internal_errors(true);
        $document = new DOMDocument();
        $document->loadHTML('<?xml encoding="utf-8"?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $xpath = new DOMXPath($document);
        $heading = $xpath->query('//div[' . self::hasClass('text-mittel') . ']')->item(0);
        if (!$heading instanceof DOMElement) {
            return null;
        }

        $title = self::text($heading);
        if (!preg_match('/^Auswertung für\s*(.*?)\s*(?:Mannschaft:|$)/u', $title, $match) || trim($match[1]) === '') {
            return null;
        }

        $name = trim($match[1]);
        $competitions = [];
        $headings = $xpath->query('//div[' . self::hasClass('sticky-bg') . ']');

        foreach ($headings as $section) {
            if (!$section instanceof DOMElement) {
                continue;
            }

            $label = self::text($section);
            $blocks = self::followingBlocks($section);

            if (in_array($label, ['Liga', 'Pokal'], true)) {
                $key = strtolower($label);
                $competition = [
                    'summary' => [],
                    'count180' => null,
                    'shortgames' => [],
                    'highfinishes' => [],
                    'results' => [],
                    'games' => [],
                ];

                foreach ($blocks as $block) {
                    $text = self::text($block);
                    if (preg_match('/Anzahl\s*180\s*:\s*(\d+)/iu', $text, $match)) {
                        $competition['count180'] = (int)$match[1];
                    }
                    if (preg_match('/Anzahl\s*short\s*game\s*:\s*\d+\s*(?:\(([^)]*)\))?/iu', $text, $match)) {
                        $competition['shortgames'] = self::numberList($match[1] ?? '');
                    }
                    if (preg_match('/Anzahl\s*high\s*finish\s*:\s*\d+\s*(?:\(([^)]*)\))?/iu', $text, $match)) {
                        $competition['highfinishes'] = self::numberList($match[1] ?? '');
                    }

                    $table = self::widestTable($xpath, $block);
                    if ($table !== null && $competition['summary'] === []) {
                        $competition['summary'] = self::summary($xpath, $table);
                    }
                }

                $competitions[$key] = $competition;
                continue;
            }

            if (preg_match('/^Ergebnisse\s+(Liga|Pokal)$/u', $label, $match)) {
                $key = strtolower($match[1]);
                if (!isset($competitions[$key])) {
                    continue;
                }

                foreach ($blocks as $block) {
                    $table = self::widestTable($xpath, $block);
                    if ($table !== null) {
                        $competitions[$key]['results'] = self::results($xpath, $table);
                        break;
                    }
                }
                continue;
            }

            if ($label === 'Spielergebnisse') {
                foreach ($blocks as $block) {
                    if (self::elementHasClass($block, 'if-row1') && self::elementHasClass($block, 'border-u')) {
                        $game = self::game($xpath, $block, $name);
                        if ($game !== null && isset($competitions[strtolower($game['competition'])])) {
                            $competitions[strtolower($game['competition'])]['games'][] = $game;
                        }
                    }
                }
            }
        }

        if ($competitions === []) {
            return null;
        }

        foreach ($competitions as &$competition) {
            usort($competition['games'], fn (array $a, array $b) => strcmp($b['date'], $a['date']));
        }
        unset($competition);

        return ['name' => $name, 'competitions' => $competitions];
    }

    /** @return DOMElement[] */
    private static function followingBlocks(DOMElement $section): array
    {
        $blocks = [];
        for ($node = $section->nextSibling; $node !== null; $node = $node->nextSibling) {
            if ($node instanceof DOMElement && self::elementHasClass($node, 'sticky-bg')) {
                break;
            }
            if ($node instanceof DOMElement) {
                $blocks[] = $node;
            }
        }

        return $blocks;
    }

    private static function widestTable(DOMXPath $xpath, DOMElement $block): ?DOMElement
    {
        $widest = null;
        $widestColumns = 0;

        foreach ($xpath->query('.//div[' . self::hasClass('tab') . ']', $block) as $table) {
            if (!$table instanceof DOMElement) {
                continue;
            }
            $row = $xpath->query('./div[' . self::hasClass('row') . ']', $table)->item(0);
            $columns = $row instanceof DOMElement ? $xpath->query('./div[' . self::hasClass('cell') . ']', $row)->length : 0;
            if ($columns > $widestColumns) {
                $widest = $table;
                $widestColumns = $columns;
            }
        }

        return $widest;
    }

    private static function summary(DOMXPath $xpath, DOMElement $table): array
    {
        $summary = [];
        $rows = $xpath->query('./div[' . self::hasClass('row') . ']', $table);

        for ($index = 1; $index < $rows->length; $index++) {
            $cells = self::cells($xpath, $rows->item($index));
            if ($cells === []) {
                continue;
            }

            $key = match (strtolower(self::text($cells[0]))) {
                'gesamt' => 'total',
                'einzel' => 'single',
                'doppel' => 'double',
                'teamgame' => 'teamgame',
                default => null,
            };
            if ($key === null) {
                continue;
            }

            $values = [];
            foreach (array_slice($cells, 1) as $cell) {
                $number = self::number(self::text($cell));
                if ($number !== null) {
                    $values[] = $number;
                }
            }
            if (count($values) >= 4) {
                $summary[$key] = [
                    'points' => $values[0],
                    'games' => $values[1],
                    'wins' => $values[2],
                    'quote' => $values[3],
                ];
            }
        }

        return $summary;
    }

    private static function results(DOMXPath $xpath, DOMElement $table): array
    {
        $results = [];
        $rows = $xpath->query('./div[' . self::hasClass('row') . ']', $table);

        for ($index = 1; $index < $rows->length; $index++) {
            $cells = self::cells($xpath, $rows->item($index));
            if (count($cells) < 2) {
                continue;
            }

            $key = match (strtolower(self::text($cells[0]))) {
                'gesamt' => 'total',
                'einzel' => 'single',
                'doppel' => 'double',
                default => null,
            };
            if ($key === null) {
                continue;
            }

            $values = array_map(fn (DOMElement $cell) => self::number(self::text($cell)), array_slice($cells, 1));
            $distribution = [];
            foreach (self::SCORES as $scoreIndex => $score) {
                $distribution[$score] = [
                    'count' => $values[$scoreIndex * 2] ?? null,
                    'quote' => $values[$scoreIndex * 2 + 1] ?? null,
                ];
            }
            $results[$key] = $distribution;
        }

        return $results;
    }

    private static function game(DOMXPath $xpath, DOMElement $row, string $playerName): ?array
    {
        $box = $xpath->query('./div[' . self::hasClass('ifs-box') . ']', $row)->item(0);
        if (!$box instanceof DOMElement) {
            return null;
        }

        $date = null;
        $competition = null;
        $sides = [];
        $legs = null;

        foreach ($box->childNodes as $child) {
            if (!$child instanceof DOMElement) {
                continue;
            }

            if (self::elementHasClass($child, 'ifs-feld2')) {
                $sides[] = self::side($xpath, $child, $playerName);
            } elseif (self::elementHasClass($child, 'ifs-feld1')) {
                $text = self::text($child);
                if ($date === null && preg_match('/^\d{2}\.\d{2}\.\d{4}$/', $text)) {
                    $dateObject = DateTimeImmutable::createFromFormat('!d.m.Y', $text);
                    $date = $dateObject?->format('Y-m-d');
                } elseif ($competition === null && in_array($text, ['Liga', 'Pokal'], true)) {
                    $competition = $text;
                } elseif (preg_match('/^(\d+)\s*:\s*(\d+)$/', $text, $match)) {
                    $legs = [(int)$match[1], (int)$match[2]];
                }
            }
        }

        if ($date === null || $competition === null || count($sides) !== 2 || $legs === null) {
            return null;
        }

        $ownSide = null;
        foreach ($sides as $index => $side) {
            if ($side['hasPlayer']) {
                $ownSide = $index;
                break;
            }
        }
        if ($ownSide === null) {
            return null;
        }

        $otherSide = 1 - $ownSide;
        $for = $ownSide === 0 ? $legs[0] : $legs[1];
        $against = $ownSide === 0 ? $legs[1] : $legs[0];

        return [
            'date' => $date,
            'dateLabel' => (new DateTimeImmutable($date))->format('d.m.Y'),
            'competition' => $competition,
            'type' => count($sides[$ownSide]['players']) + count($sides[$otherSide]['players']) >= 8
                ? 'teamgame'
                : (count($sides[$ownSide]['players']) > 1 ? 'double' : 'single'),
            'home' => $ownSide === 0,
            'won' => $for > $against,
            'legs' => ['for' => $for, 'against' => $against],
            'partners' => array_values(array_filter(
                $sides[$ownSide]['players'],
                fn (string $other) => self::normalized($other) !== self::normalized($playerName)
            )),
            'opponentSquad' => $sides[$otherSide]['squad'],
        ];
    }

    private static function side(DOMXPath $xpath, DOMElement $element, string $playerName): array
    {
        $box = $xpath->query('.//div[' . self::hasClass('if-box') . ']', $element)->item(0);
        $players = [];
        $hasPlayer = false;
        $squad = null;

        if ($box instanceof DOMElement) {
            foreach ($xpath->query('./div[' . self::hasClass('if-row1') . ']', $box) as $line) {
                if (!$line instanceof DOMElement) {
                    continue;
                }

                $text = self::text($line);
                if (self::elementHasClass($line, 'kursiv') || self::elementHasClass($line, 'text-klein')) {
                    $squad = $text !== '' ? $text : $squad;
                    continue;
                }
                if ($text === '' || in_array($text, ['-', '–'], true)) {
                    continue;
                }

                $players[] = $text;
                foreach ($xpath->query('.//span[' . self::hasClass('fett') . ' and ' . self::hasClass('rot') . ']', $line) as $highlight) {
                    if (self::normalized(self::text($highlight)) === self::normalized($playerName)) {
                        $hasPlayer = true;
                    }
                }
                if (self::normalized($text) === self::normalized($playerName)) {
                    $hasPlayer = true;
                }
            }
        }

        return ['players' => $players, 'squad' => $squad, 'hasPlayer' => $hasPlayer];
    }

    private static function numberList(string $text): array
    {
        preg_match_all('/\d+/', $text, $matches);

        return array_map('intval', $matches[0]);
    }

    private static function number(string $text): int|float|null
    {
        $text = str_replace(['%', ' ', "\u{00A0}"], '', $text);
        $text = str_replace(',', '.', $text);
        if (!is_numeric($text)) {
            return null;
        }

        return str_contains($text, '.') ? (float)$text : (int)$text;
    }

    /** @return DOMElement[] */
    private static function cells(DOMXPath $xpath, DOMNode $row): array
    {
        $cells = [];
        foreach ($xpath->query('./div[' . self::hasClass('cell') . ']', $row) as $cell) {
            if ($cell instanceof DOMElement) {
                $cells[] = $cell;
            }
        }

        return $cells;
    }

    private static function normalized(string $text): string
    {
        return mb_strtolower(preg_replace('/\s+/u', ' ', trim($text)));
    }

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