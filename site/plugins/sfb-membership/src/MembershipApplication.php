<?php

use Kirby\Cms\App;
use Kirby\Toolkit\V;

/**
 * Definitions and validation of the digital membership application ("Beitrittserklärung").
 *
 * The groups, their numbers and the fee classes are taken from the PDF form
 * data/beitrittserklarung_stand_03.2026.pdf. When the PDF is replaced by a new version, this class
 * (groups and fee classes) and the coordinates in MembershipPdf have to be adapted.
 *
 * Options (see sfb-membership/index.php):
 * - sfb.membership.recipient: e-mail address of the membership administration which receives the applications
 * - sfb.membership.template:  path of the PDF form (default: data/beitrittserklarung_stand_03.2026.pdf)
 */
class MembershipApplication
{
    /**
     * Value of the group selection for a passive membership
     */
    public const PASSIVE = 'passiv';

    /**
     * Maximum number of additional family members
     */
    public const MAX_FAMILY_MEMBERS = 10;

    /**
     * Fee classes (only one can be chosen)
     */
    public const FEE_CLASSES = [
        'jugend' => 'Jugendliche bis 18 Jahre',
        'erwachsene' => 'Erwachsene',
        'familie' => 'Familienbeitrag',
    ];

    /**
     * Departments with their groups: the key of a group is the group number of the PDF form,
     * 'sonstiges' stands for the entry "Sonstiges" of the PDF form.
     */
    public const DEPARTMENTS = [
        'aikido' => [
            'title' => 'Aikido',
            'groups' => [
                '8001' => 'Aktive Aikido',
                '8002' => 'Jugend Aikido',
            ],
        ],
        'fussball' => [
            'title' => 'Fußball',
            'groups' => [
                '6001' => '1. und 2. Mannschaft Fußball',
                '6002' => 'Ü30 Fußball',
                '6003' => 'Ü50 Fußball',
                '6004' => 'Jugendfußball',
            ],
        ],
        'darts' => [
            'title' => 'Darts',
            'groups' => [
                '9001' => 'Erwachsene Darts',
                '9002' => 'Jugendliche Darts',
            ],
        ],
        'gymnastik' => [
            'title' => 'Gymnastik/Fitness',
            'groups' => [
                '7002' => '(Kurs) Rückenfit | Ulrike Walser',
                '7005' => 'Bodystyling | Edith Zagst',
                '7006' => 'SFB SchulKIDS | Nadine Stehle',
                '7007' => 'Spielturnen | Conny Wilhelm',
                '7008' => 'Eltern+Kind Turnen | Oli Maunz',
                '7010' => '(Kurs) Zumba | Edith Zagst',
                '7013' => '(Kurs) Zwergen Turnen | Bianca Leiprecht',
                'sonstiges' => 'Sonstiges',
            ],
        ],
    ];

    /**
     * Read an option of the sfb-membership plugin.
     *
     * Kirby's option() does not find a value that is written as nested array in the config.php
     * ('sfb' => ['membership' => ['recipient' => ...]]) because the defaults of the plugin are
     * registered under the key 'sfb.membership' and shadow it. Therefore all notations are
     * supported (first match wins):
     * 1. flat key in the config.php: 'sfb.membership.recipient' => ...
     * 2. nested array in the config.php: 'sfb' => ['membership' => ['recipient' => ...]]
     * 3. default of the plugin (see sfb-membership/index.php)
     *
     * @param string $key Option name without the 'sfb.membership.' prefix
     * @param mixed $default
     * @return mixed
     */
    public static function option(string $key, $default = null)
    {
        $options = App::instance()->options();
        $flatKey = 'sfb.membership.' . $key;

        if (isset($options[$flatKey])) {
            return $options[$flatKey];
        }

        if (isset($options['sfb']) && is_array($options['sfb']) && isset($options['sfb']['membership'][$key])) {
            return $options['sfb']['membership'][$key];
        }

        if (isset($options['sfb.membership']) && is_array($options['sfb.membership']) && isset($options['sfb.membership'][$key])) {
            return $options['sfb.membership'][$key];
        }

        return $default;
    }

    /**
     * E-mail address which receives the applications (option sfb.membership.recipient)
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
     * Path of the PDF form which is filled in (option sfb.membership.template)
     */
    public static function templatePath(): string
    {
        $template = static::option('template');

        if (is_string($template) && trim($template) !== '') {
            return $template;
        }

        return App::instance()->root('base') . '/data/beitrittserklarung_stand_03.2026.pdf';
    }

    /**
     * All groups which can be chosen (without the passive membership): group number => title
     *
     * @return array<string, string>
     */
    public static function groups(): array
    {
        $groups = [];

        foreach (static::DEPARTMENTS as $department) {
            foreach ($department['groups'] as $key => $title) {
                $groups[(string) $key] = $title;
            }
        }

        return $groups;
    }

    /**
     * Is the value the key of a group of the PDF form? (the passive membership is no group)
     */
    public static function isGroup($key): bool
    {
        return is_string($key) && array_key_exists($key, static::groups());
    }

    /**
     * Is the value a valid choice for a person: a group or the passive membership?
     */
    public static function isGroupOrPassive($key): bool
    {
        return $key === static::PASSIVE || static::isGroup($key);
    }

    /**
     * Readable text for a group, e.g. "Fußball: 6004 - Jugendfußball"
     */
    public static function groupTitle(string $key): string
    {
        if ($key === static::PASSIVE) {
            return 'Passive Mitgliedschaft';
        }

        foreach (static::DEPARTMENTS as $department) {
            if (isset($department['groups'][$key])) {
                $title = $department['groups'][$key];

                return $department['title'] . ': ' . (ctype_digit($key) ? $key . ' - ' : '') . $title;
            }
        }

        return $key;
    }

    /**
     * Rule 'membershipGroups': a list of groups and/or the passive membership, only known values
     *
     * @param mixed $value
     */
    public static function validGroups($value): bool
    {
        if (!is_array($value) || $value === []) {
            return false;
        }

        foreach ($value as $key) {
            if (!static::isGroupOrPassive($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Rule 'membershipPassive': the passive membership excludes every group
     *
     * @param mixed $value
     */
    public static function validPassiveExclusive($value): bool
    {
        return !is_array($value) || !in_array(static::PASSIVE, $value, true) || count($value) === 1;
    }

    /**
     * Rule 'membershipFamily': list of additional family members. Every member needs a name, a
     * first name, a valid date of birth and a group (or the passive membership).
     *
     * @param mixed $value
     */
    public static function validFamily($value): bool
    {
        if (!is_array($value) || count($value) > static::MAX_FAMILY_MEMBERS) {
            return false;
        }

        foreach ($value as $member) {
            if (!is_array($member)) {
                return false;
            }

            foreach (['nachname', 'vorname'] as $field) {
                if (!isset($member[$field]) || !is_string($member[$field])) {
                    return false;
                }

                $length = mb_strlen(trim($member[$field]));

                if ($length === 0 || $length > 100) {
                    return false;
                }
            }

            if (!static::isBirthDate($member['geburtsdatum'] ?? null)) {
                return false;
            }

            if (!static::isGroupOrPassive($member['gruppe'] ?? null)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Rule 'membershipIban': valid IBAN (structure, length of German IBANs and checksum)
     *
     * @param mixed $value
     */
    public static function validIban($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        $iban = static::normalizeIban($value);

        if (preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{11,30}$/', $iban) !== 1) {
            return false;
        }

        if (str_starts_with($iban, 'DE') && strlen($iban) !== 22) {
            return false;
        }

        // checksum (ISO 7064 mod 97-10): move the first four characters to the end, letters become numbers (A=10 ... Z=35)
        $numeric = '';
        foreach (str_split(substr($iban, 4) . substr($iban, 0, 4)) as $char) {
            $numeric .= ctype_digit($char) ? $char : (string) (ord($char) - 55);
        }

        $remainder = 0;
        foreach (str_split($numeric, 7) as $chunk) {
            $remainder = (int) ($remainder . $chunk) % 97;
        }

        return $remainder === 1;
    }

    /**
     * Rule 'membershipBic': structure of a BIC (8 or 11 characters)
     *
     * @param mixed $value
     */
    public static function validBic($value): bool
    {
        return is_string($value) && preg_match('/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/', static::normalizeBic($value)) === 1;
    }

    /**
     * IBAN without whitespace in upper case
     */
    public static function normalizeIban(string $iban): string
    {
        return strtoupper((string) preg_replace('/\s+/', '', $iban));
    }

    /**
     * BIC without whitespace in upper case
     */
    public static function normalizeBic(string $bic): string
    {
        return strtoupper((string) preg_replace('/\s+/', '', $bic));
    }

    /**
     * IBAN in groups of four characters, e.g. "DE89 3704 0044 0532 0130 00"
     */
    public static function formatIban(string $iban): string
    {
        return trim(chunk_split(static::normalizeIban($iban), 4, ' '));
    }

    /**
     * IBAN for the e-mail to the membership administration: only the country, the checksum and the last four
     * characters are readable, e.g. "DE89 **** **** **** **30 00"
     */
    public static function maskIban(string $iban): string
    {
        $iban = static::normalizeIban($iban);

        if (strlen($iban) <= 8) {
            return $iban;
        }

        return static::formatIban(substr($iban, 0, 4) . str_repeat('*', strlen($iban) - 8) . substr($iban, -4));
    }

    /**
     * Parse a date of the format YYYY-MM-DD (strict: 2026-02-31 is invalid)
     */
    public static function parseDate($date): ?DateTimeImmutable
    {
        if (!is_string($date)) {
            return null;
        }

        $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date ? $parsed : null;
    }

    /**
     * Date of birth: valid date, not in the future, not before 1900
     *
     * @param mixed $date
     */
    public static function isBirthDate($date): bool
    {
        $parsed = static::parseDate($date);

        return $parsed !== null && $parsed->format('Y-m-d') >= '1900-01-01' && $parsed->format('Y-m-d') <= date('Y-m-d');
    }

    /**
     * Desired date of entry: valid date, from yesterday (tolerance for forms which were opened shortly
     * before midnight) up to one year in the future
     *
     * @param mixed $date
     */
    public static function isEntryDate($date): bool
    {
        $parsed = static::parseDate($date);

        if ($parsed === null) {
            return false;
        }

        $day = $parsed->format('Y-m-d');

        return $day >= date('Y-m-d', strtotime('-1 day')) && $day <= date('Y-m-d', strtotime('+1 year'));
    }

    /**
     * Date as DD.MM.YYYY, an invalid date is returned unchanged
     */
    public static function formatDate($date): string
    {
        $parsed = static::parseDate($date);

        return $parsed !== null ? $parsed->format('d.m.Y') : (string) $date;
    }

    /**
     * The data of a submitted form in the structure MembershipPdf and the mail expect
     *
     * @param array $raw Raw (not HTML-escaped) data of the form
     */
    public static function fromFormData(array $raw): array
    {
        $text = fn(string $field): string => isset($raw[$field]) && is_string($raw[$field]) ? trim($raw[$field]) : '';

        $groups = [];
        foreach ((array) ($raw['gruppen'] ?? []) as $group) {
            if (is_scalar($group)) {
                $groups[] = (string) $group;
            }
        }

        $feeClass = $text('beitragsklasse');

        return [
            'nachname' => $text('nachname'),
            'vorname' => $text('vorname'),
            'strasse' => $text('strasse'),
            'plz' => $text('plz'),
            'ort' => $text('ort'),
            'eintrittsdatum' => $text('eintrittsdatum'),
            'geburtsdatum' => $text('geburtsdatum'),
            'email' => $text('email'),
            'telefon' => $text('telefon'),
            'gruppen' => $groups,
            'beitragsklasse' => $feeClass,
            // family members only belong to the fee class "Familienbeitrag"
            'familie' => $feeClass === 'familie' ? static::cleanFamily($raw['familie'] ?? []) : [],
            'iban' => static::normalizeIban($text('iban')),
            'bic' => static::normalizeBic($text('bic')),
            'kreditinstitut' => $text('kreditinstitut'),
            'kontoinhaberNachname' => $text('kontoinhaberNachname'),
            'kontoinhaberVorname' => $text('kontoinhaberVorname'),
            // date of the signatures
            'datum' => date('d.m.Y'),
        ];
    }

    /**
     * Clean the submitted list of family members: only the known fields, trimmed, completely
     * empty rows removed, numbered continuously.
     *
     * @param mixed $members
     * @return array<int, array{nachname: string, vorname: string, geburtsdatum: string, gruppe: string}>
     */
    public static function cleanFamily($members): array
    {
        if (!is_array($members)) {
            return [];
        }

        $clean = [];

        foreach ($members as $member) {
            if (!is_array($member)) {
                continue;
            }

            $row = [];
            foreach (['nachname', 'vorname', 'geburtsdatum', 'gruppe'] as $field) {
                $row[$field] = isset($member[$field]) && is_string($member[$field]) ? trim($member[$field]) : '';
            }

            // the group is preselected in the form, therefore it does not count as input
            if ($row['nachname'] === '' && $row['vorname'] === '' && $row['geburtsdatum'] === '') {
                continue;
            }

            $clean[] = $row;
        }

        return $clean;
    }
}
