<?php

use Kirby\Cms\App;
use Kirby\Toolkit\V;

/**
 * Definitions and validation of the digital partnership request ("Möglichkeiten einer Werbepartnerschaft").
 *
 * The interests are taken from the PDF form "Vordruck Werbemöglichkeiten Förderverein Sportfreunde Bronnen e.V.".
 * When the offers of the Förderverein change, the list INTERESTS has to be adapted.
 *
 * Options (see sfb-partnership/index.php):
 * - sfb.partnership.recipient: e-mail address which receives the requests
 */
class PartnershipRequest
{
    /**
     * Offers the sender can be interested in (several can be chosen): key => text of the PDF form
     */
    public const INTERESTS = [
        'bandenwerbung' => 'Bandenwerbung',
        'bannerwerbung' => 'Bannerwerbung',
        'pflegepatenschaft' => 'Pflegepatenschaft Parzelle Sportplatz',
        'firmenpraesentation' => 'Firmenpräsentation an einem Heimspieltag',
        'miete-svz' => 'Miete des Sportvereinszentrums',
        'visitenkarte' => 'Auslegen der Visitenkarte',
        'verlinkung' => 'Verlinkung auf der Vereinswebsite',
        'spende' => 'Spende',
        'zweckgebundene-spende' => 'Zweckgebundene Spende',
        'mitgliedschaft' => 'Aktive oder passive Mitgliedschaft im Verein',
        'werbefilm' => 'Werbefilm/Werbeeinblendung Heimspieltage 65"-Monitor Sportvereinszentrum',
    ];

    /**
     * Read an option of the sfb-partnership plugin.
     *
     * Kirby's option() does not find a value that is written as nested array in the config.php
     * ('sfb' => ['partnership' => ['recipient' => ...]]) because the defaults of the plugin are
     * registered under the key 'sfb.partnership' and shadow it. Therefore all notations are
     * supported (first match wins):
     * 1. flat key in the config.php: 'sfb.partnership.recipient' => ...
     * 2. nested array in the config.php: 'sfb' => ['partnership' => ['recipient' => ...]]
     * 3. default of the plugin (see sfb-partnership/index.php)
     *
     * @param string $key Option name without the 'sfb.partnership.' prefix
     * @param mixed $default
     * @return mixed
     */
    public static function option(string $key, $default = null)
    {
        $options = App::instance()->options();
        $flatKey = 'sfb.partnership.' . $key;

        if (isset($options[$flatKey])) {
            return $options[$flatKey];
        }

        if (isset($options['sfb']) && is_array($options['sfb']) && isset($options['sfb']['partnership'][$key])) {
            return $options['sfb']['partnership'][$key];
        }

        if (isset($options['sfb.partnership']) && is_array($options['sfb.partnership']) && isset($options['sfb.partnership'][$key])) {
            return $options['sfb.partnership'][$key];
        }

        return $default;
    }

    /**
     * E-mail address which receives the requests (option sfb.partnership.recipient)
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
     * Are all submitted values keys of INTERESTS?
     */
    public static function validInterests($value): bool
    {
        if (!is_array($value) || $value === []) {
            return false;
        }

        foreach ($value as $key) {
            if (!is_string($key) || !array_key_exists($key, static::INTERESTS)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Readable texts of the chosen interests (in the order of the PDF form, every interest once)
     *
     * @param mixed $keys
     * @return string[]
     */
    public static function interestTitles($keys): array
    {
        $keys = is_array($keys) ? $keys : [];

        return array_values(array_filter(
            static::INTERESTS,
            fn (string $key): bool => in_array($key, $keys, true),
            ARRAY_FILTER_USE_KEY
        ));
    }

    /**
     * Normalized request from the (raw, not HTML-escaped) data of the form
     *
     * @param array $data
     * @return array<string, mixed>
     */
    public static function fromFormData(array $data): array
    {
        $text = fn (string $key): string => trim((string) ($data[$key] ?? ''));

        return [
            'interessen' => static::interestTitles($data['interessen'] ?? []),
            'firma' => $text('firma'),
            'ansprechpartner' => $text('ansprechpartner'),
            'strasse' => $text('strasse'),
            'plz' => $text('plz'),
            'ort' => $text('ort'),
            'telefon' => $text('telefon'),
            'email' => $text('email'),
            'nachricht' => $text('nachricht'),
            'datum' => date('d.m.Y'),
        ];
    }
}
