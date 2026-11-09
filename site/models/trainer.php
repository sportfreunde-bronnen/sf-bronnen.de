<?php

use Kirby\Toolkit\Html;

/**
 * Class TrainerPage
 *
 * Coach chronicle: all coaches of the club, the current one and the former ones grouped by decade.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class TrainerPage extends RoleAwarePage
{
    /** @var array|null cache of the prepared coach stations (newest first) */
    private ?array $stations = null;

    /**
     * Returns all coach stations, the newest first.
     *
     * Every station is an array with the keys:
     *  - name:      HTML safe name, remarks in brackets are wrapped in <span class="coach-note">
     *  - period:    period of time, e.g. "07/2017 – 12/2018"
     *  - startYear: year the station begins (null if the period holds no year)
     *  - isCurrent: true if the station is still running ("seit 06/2024")
     *
     * @return array
     */
    public function getCoachStations(): array
    {
        if ($this->stations !== null) {
            return $this->stations;
        }

        $stations = [];
        foreach ($this->coaches()->toStructure() as $coach) {
            $period = $this->normalizeWhitespace((string)$coach->zeitraum());
            $name = $this->normalizeWhitespace((string)$coach->name());
            if ($name === '' && $period === '') {
                continue;
            }

            $stations[] = [
                'name' => $this->formatName($name),
                'period' => str_replace(' - ', ' – ', $period),
                'startYear' => preg_match('/\d{4}/', $period, $match) ? (int)$match[0] : null,
                'isCurrent' => (bool)preg_match('/^seit\b/iu', $period),
            ];
        }

        // the editors maintain the stations in chronological order, the page shows the newest first
        return $this->stations = array_reverse($stations);
    }

    /**
     * Number of all coach stations (including the current one)
     */
    public function getCoachStationCount(): int
    {
        return count($this->getCoachStations());
    }

    /**
     * Year of the oldest coach station (null if unknown)
     */
    public function getFirstCoachYear(): ?int
    {
        $years = array_filter(array_column($this->getCoachStations(), 'startYear'));
        return $years ? min($years) : null;
    }

    /**
     * Returns the current coach station (null if there is none)
     */
    public function getCurrentCoach(): ?array
    {
        foreach ($this->getCoachStations() as $station) {
            if ($station['isCurrent']) {
                return $station;
            }
        }
        return null;
    }

    /**
     * Returns the former coach stations grouped by the decade they began in, the newest decade first.
     *
     * @return array list of ['label' => '2010er', 'stations' => [...]]
     */
    public function getFormerCoachDecades(): array
    {
        $groups = [];
        foreach ($this->getCoachStations() as $station) {
            if ($station['isCurrent']) {
                continue;
            }

            $label = $station['startYear'] !== null
                ? (floor($station['startYear'] / 10) * 10) . 'er'
                : 'Weitere';
            $groups[$label][] = $station;
        }

        $decades = [];
        foreach ($groups as $label => $stations) {
            $decades[] = ['label' => (string)$label, 'stations' => $stations];
        }
        return $decades;
    }

    /**
     * Escapes the name and wraps remarks in brackets (e.g. "(Spielertrainer)") for a separate styling
     */
    private function formatName(string $name): string
    {
        $name = Html::encode($name);
        return preg_replace('/\(([^)]*)\)/u', '<span class="coach-note">($1)</span>', $name);
    }

    /**
     * Folds line breaks and repeated blanks (the YAML block scalars of the content file contain them)
     */
    private function normalizeWhitespace(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', $value));
    }
}
