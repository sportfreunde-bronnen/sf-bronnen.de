<?php

/**
 * Class SvzPage
 *
 * Landing page of the "Sportvereinszentrum" (SVZ) which is rented out to guests.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class SvzPage extends DownloadsPage
{
    /** Google spreadsheet (published as CSV) which holds the bookings */
    private const BOOKINGS_CSV_URL = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT5dqsnNxy6grN9XCCbeTVEhxpGe2wbUoxB9PN4OUMBw9TnGZTBgsOYu0IMOcKadvE4-stAFPAAeMnT/pub?gid=0&single=true&output=csv';

    /** Lifetime of the cached bookings in minutes */
    private const BOOKINGS_CACHE_MINUTES = 15;

    /** Number of header rows in the spreadsheet which must be skipped */
    private const BOOKINGS_HEADER_ROWS = 4;

    /** Number of months which can be browsed in the availability calendar */
    public const CALENDAR_MONTHS = 24;

    /**
     * Bookable packages
     */
    public const PACKAGES = [
        'getraenke' => [
            'title' => 'Getränkepaket',
            'subtitle' => 'Raummiete + Getränke von uns',
            'icon' => 'fa-beer',
            'description' => 'Kein Stress für Sie. Sie mieten den Raum und beziehen die Getränke zu unseren gewohnten Konditionen. Es gilt der angezeigte Mindestverzehr.',
        ],
        'raum' => [
            'title' => 'Raumpaket',
            'subtitle' => 'Nur Raummiete',
            'icon' => 'fa-key',
            'description' => 'Sie mieten ausschließlich die Räumlichkeiten. Die Getränke sowie die Verpflegung sind nicht enthalten. Bitte beachten Sie, dass beim Raumpaket die Nutzung unseres Kühlraums <b>nicht möglich</b> ist. Ein Kühlschrank steht Ihnen natürlich zur Verfügung.',
        ],
    ];

    /**
     * Rentable rooms
     */
    public const ROOMS = [
        'klein' => [
            'title' => 'Kleiner Wirtschaftsraum',
            'subtitle' => 'mit Küche',
            'size' => '75 m²',
            'capacity' => 'bis 35 Personen',
            'icon' => 'fa-glass',
        ],
        'gross' => [
            'title' => 'Großer Wirtschaftsraum',
            'subtitle' => 'mit Küche',
            'size' => '165 m²',
            'capacity' => 'bis 100 Personen',
            'icon' => 'fa-users',
        ],
    ];

    /**
     * Returns the booked days as array: 'Y-m-d' => spreadsheet row.
     * Returns null if the bookings are currently not available (e.g. spreadsheet not reachable).
     *
     * @return array|null
     */
    public function bookings(): ?array
    {
        $cache = $this->kirby()->cache('svz');

        if (($bookings = $cache->get('bookings')) !== null) {
            return $bookings;
        }

        $context = stream_context_create([
            'http' => ['timeout' => 5, 'follow_location' => 1],
        ]);
        $csv = @file_get_contents(self::BOOKINGS_CSV_URL, false, $context);

        if ($csv === false || $csv === '') {
            return null;
        }

        $bookings = [];
        $rows = explode("\n", $csv);

        foreach ($rows as $index => $row) {
            if ($index < self::BOOKINGS_HEADER_ROWS || trim($row) === '') {
                continue;
            }

            $entry = str_getcsv($row, ',', '"', '\\');
            $timestamp = strtotime($entry[1] ?? '');

            if ($timestamp !== false) {
                $bookings[date('Y-m-d', $timestamp)] = $entry;
            }
        }

        $cache->set('bookings', $bookings, self::BOOKINGS_CACHE_MINUTES);

        return $bookings;
    }

    /**
     * Returns the prices grouped by package, each with its rooms:
     *
     * [
     *   'getraenke' => [
     *     'title' => ..., 'subtitle' => ..., 'description' => ...,
     *     'rows' => [
     *       'klein' => ['room' => [...], 'rent' => 160.0, 'minimum' => 140.0, 'total' => 300.0,
     *                   'memberRent' => 80.0, 'memberTotal' => 220.0],
     *     ],
     *   ],
     * ]
     *
     * Also usable as data basis for a price calculator.
     *
     * @return array
     */
    public function priceGroups(): array
    {
        $groups = [];

        foreach ($this->prices()->toStructure() as $price) {
            $package = (string)$price->package();
            $room = (string)$price->room();

            if (!isset(self::PACKAGES[$package], self::ROOMS[$room])) {
                continue;
            }

            $groups[$package] ??= self::PACKAGES[$package] + ['key' => $package, 'rows' => []];

            $rent = (float)$price->rent()->value();
            $minimum = (float)$price->minimum()->value();
            $memberRent = (float)$price->memberrent()->value();

            $groups[$package]['rows'][$room] = [
                'key' => $room,
                'room' => self::ROOMS[$room],
                'rent' => $rent,
                'minimum' => $minimum,
                'total' => $rent + $minimum,
                'memberRent' => $memberRent,
                'memberTotal' => $memberRent + $minimum,
            ];
        }

        // keep the order defined in self::PACKAGES
        return array_filter(
            array_replace(array_fill_keys(array_keys(self::PACKAGES), null), $groups)
        );
    }

    /**
     * Formats a price in german notation, e.g. 1234.5 => "1.234,50 €"
     *
     * @param float $amount
     * @return string
     */
    public static function formatPrice(float $amount): string
    {
        $hasCents = fmod($amount, 1) !== 0.0;

        return number_format($amount, $hasCents ? 2 : 0, ',', '.') . ' €';
    }
}
