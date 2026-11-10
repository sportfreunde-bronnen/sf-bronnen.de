<?php

/**
 * Class SpielerstatistikPage
 *
 * Player statistics of the darts department. The data comes from the website of the DVOS (Dartverband Oberschwaben),
 * see plugin sfb-dvos.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class SpielerstatistikPage extends RoleAwarePage
{
    /** Id of the Sportfreunde Bronnen at the DVOS */
    public const DEFAULT_CLUB_ID = 71;

    /**
     * Id of the club at the DVOS (field "club")
     */
    public function clubId(): int
    {
        $clubId = (int)$this->content()->get('club')->value();

        return $clubId > 0 ? $clubId : self::DEFAULT_CLUB_ID;
    }

    /**
     * Prepared statistics (cached for 12 hours) or null if the DVOS is not reachable and no older data exists
     *
     * @see DvosPlayerStatistics::forClub()
     */
    public function statistics(): ?array
    {
        return DvosPlayerStatistics::forClub($this->clubId(), $this->kirby());
    }

    /**
     * Public page of the DVOS with the statistics of the club
     */
    public function sourceUrl(): string
    {
        return DvosPlayerStatistics::sourceUrl($this->clubId());
    }

    /**
     * The best players of a competition for the highlight cards:
     *
     * [['key' => 'wins', 'label' => 'Meiste Siege', 'value' => '11', 'names' => ['Elias Mäschle'], 'note' => null], ...]
     *
     * Players with the same best value are all listed. Highlights nobody has a value for are left out.
     */
    public function highlights(array $players): array
    {
        $highlights = [];
        $maxGames = max(array_map(fn (array $player) => (int)$player['games'], $players) ?: [0]);
        // a quote is only meaningful with a number of games: at least half of the games of the most active player
        $minGames = max(1, (int)ceil($maxGames / 2));

        $definitions = [
            [
                'key' => 'points',
                'label' => 'Meiste Punkte',
                'value' => fn (array $player) => $player['points'],
                'format' => fn ($value) => self::number($value) . ' Punkte',
            ],
            [
                'key' => 'wins',
                'label' => 'Meiste Siege',
                'value' => fn (array $player) => $player['wins'],
                'format' => fn ($value) => self::number($value) . ' Siege',
            ],
            [
                'key' => 'quote',
                'label' => 'Beste Quote',
                'note' => 'ab ' . $minGames . ' Spielen',
                'value' => fn (array $player) => (int)$player['games'] >= $minGames ? $player['quote'] : null,
                'format' => fn ($value) => self::quote($value),
            ],
            [
                'key' => 'count180',
                'label' => 'Meiste 180er',
                'value' => fn (array $player) => ($player['count180'] ?? 0) > 0 ? $player['count180'] : null,
                'format' => fn ($value) => self::number($value) . '×',
            ],
            [
                'key' => 'highfinish',
                'label' => 'Höchstes High-Finish',
                'value' => fn (array $player) => ($player['highfinish']['best'] ?? 0) > 0 ? $player['highfinish']['best'] : null,
                'format' => fn ($value) => self::number($value),
            ],
        ];

        foreach ($definitions as $definition) {
            $best = null;
            $names = [];

            foreach ($players as $player) {
                $value = $definition['value']($player);

                if ($value === null) {
                    continue;
                }

                if ($best === null || $value > $best) {
                    $best = $value;
                    $names = [$player['name']];
                } elseif ($value == $best) {
                    $names[] = $player['name'];
                }
            }

            if ($best !== null) {
                $highlights[] = [
                    'key' => $definition['key'],
                    'label' => $definition['label'],
                    'value' => $definition['format']($best),
                    'names' => $names,
                    'note' => $definition['note'] ?? null,
                ];
            }
        }

        return $highlights;
    }

    /**
     * Number in german notation, e.g. 1234 => "1.234"; "–" for values the DVOS does not provide
     */
    public static function number(int|float|null $value): string
    {
        return $value === null ? '–' : number_format($value, 0, ',', '.');
    }

    /**
     * Quote in percent in german notation, e.g. 91.7 => "91,7 %"
     */
    public static function quote(int|float|null $value): string
    {
        return $value === null ? '–' : number_format($value, 1, ',', '.') . ' %';
    }
}
