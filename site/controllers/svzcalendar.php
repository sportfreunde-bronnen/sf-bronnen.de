<?php
use Uniform\Form;
return function ($kirby) {

    /** @var Kirby\Cms\App $kirby */
    $data = [];

    $cache = $kirby->cache('svz');

    if (($bookings = $cache->get('bookings')) === null) {

        $spreadsheet_url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vT5dqsnNxy6grN9XCCbeTVEhxpGe2wbUoxB9PN4OUMBw9TnGZTBgsOYu0IMOcKadvE4-stAFPAAeMnT/pub?gid=0&single=true&output=csv';
        $csv = file_get_contents($spreadsheet_url);
        $rows = explode("\n",$csv);

        for ($i = 0; $i < count($rows); $i++) {
            if (!in_array($i, [0, 1, 2, 3])) {
                $data[] = str_getcsv($rows[$i]);
            }
        }

        foreach ($data as $entry) {
            $date = $entry[2];
            if (false !== strtotime($date)) {
                $bookings[date('Y-m-d', strtotime($date))] = $entry;
            }
        }

        $cache->set('bookings', $bookings, 15);

    } else {
        $bookings = $cache->get('bookings');
    }

    return [
        'bookings' => $bookings,
        'year' => $_GET['calYear'] ?? date('Y'),
        'month' => $_GET['calMonth'] ?? date('m')
    ];

};