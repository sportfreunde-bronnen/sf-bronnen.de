<?php

/**
 * Class VeranstaltungPage
 *
 * An event of the club, shown as card on the start page.
 */
class VeranstaltungPage extends RoleAwarePage
{
    private static $months = [
        1 => 'Jan', 'Feb', 'Mär', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dez',
    ];

    private static $weekdays = ['So.', 'Mo.', 'Di.', 'Mi.', 'Do.', 'Fr.', 'Sa.'];

    /**
     * Returns the start of the event as timestamp
     *
     * @return int|null
     */
    public function getStart()
    {
        return strtotime((string)$this->datum()) ?: null;
    }

    /**
     * Returns the end of the event as timestamp (null if the event has no end)
     *
     * @return int|null
     */
    public function getEnd()
    {
        return strtotime((string)$this->datumbis()) ?: null;
    }

    /**
     * Day of the month of the start (e.g. 05)
     *
     * @return string
     */
    public function getDay()
    {
        $start = $this->getStart();
        return $start ? date('d', $start) : '';
    }

    /**
     * Abbreviated month of the start in german (e.g. Mär)
     *
     * @return string
     */
    public function getMonthShort()
    {
        $start = $this->getStart();
        return $start ? self::$months[(int)date('n', $start)] : '';
    }

    /**
     * Whether the event is over (events without time last until the end of the day, events without end until their start)
     *
     * @return bool
     */
    public function isPast()
    {
        $reference = $this->getEnd() ?: $this->getStart();
        if (!$reference) {
            return false;
        }
        if (!$this->hasTime($reference)) {
            $reference = strtotime('tomorrow', $reference) - 1;
        }
        return $reference < time();
    }

    /**
     * Readable date of the event (e.g. "Sa., 12.12.2026" or "20.07.2018 – 22.07.2018" for events over several days)
     *
     * @return string
     */
    public function getDateLabel()
    {
        $start = $this->getStart();
        if (!$start) {
            return '';
        }
        $end = $this->getEnd();
        if ($end && date('Y-m-d', $end) !== date('Y-m-d', $start)) {
            return date('d.m.Y', $start) . ' – ' . date('d.m.Y', $end);
        }
        return self::$weekdays[(int)date('w', $start)] . ', ' . date('d.m.Y', $start);
    }

    /**
     * Readable time of the event (e.g. "19:00 Uhr" or "14:00 – 17:00 Uhr"), empty if the event has no time
     *
     * @return string
     */
    public function getTimeLabel()
    {
        $start = $this->getStart();
        if (!$start || !$this->hasTime($start)) {
            return '';
        }
        $label = date('H:i', $start);
        $end = $this->getEnd();
        if ($end && $this->hasTime($end) && date('Y-m-d', $end) === date('Y-m-d', $start)) {
            $label .= ' – ' . date('H:i', $end);
        }
        return $label . ' Uhr';
    }

    /**
     * Title of the area (top level page) the event belongs to
     *
     * @return string
     */
    public function getAreaTitle()
    {
        $area = $this->parents()->last();
        return $area ? (string)$area->title() : '';
    }

    private function hasTime($timestamp)
    {
        return date('H:i', $timestamp) !== '00:00';
    }
}
