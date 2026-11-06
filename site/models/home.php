<?php

/**
 * Startpage model
 */
class HomePage extends Page
{

    static $latestCacheFile = __DIR__ . '/../cache/latest.json';

    /**
     * Areas of the club (top level pages) in the order they are offered as news filter, with their icon
     */
    const AREAS = [
        'gesamtverein' => 'fa-users',
        'fussball'     => 'fa-futbol-o',
        'darts'        => 'fa-bullseye',
        'gymnastik'    => 'fa-heartbeat',
        'aikido'       => 'fa-shield',
    ];

    /**
     * Number of news which are shown at once (for all areas as well as for a single area)
     */
    const NEWS_LIMIT = 6;

    /**
     * Returns the areas of the club which exist as page
     *
     * @return array[] slug, title, icon, url and the url of the news archive (or null) of every area
     */
    public function getAreas()
    {
        $areas = [];
        foreach (self::AREAS as $slug => $icon) {
            $area = $this->site()->find($slug);
            if (!$area) {
                continue;
            }
            $archive = $area->index()->filterBy('template', 'aktuelles')->first();
            $areas[$slug] = [
                'slug'       => $slug,
                'title'      => (string)$area->title(),
                'icon'       => $icon,
                'url'        => $area->url(),
                'archiveUrl' => $archive ? $archive->url() : null,
            ];
        }
        return $areas;
    }

    /**
     * Returns the slug of the area (top level page) a page belongs to
     *
     * @param \Kirby\Cms\Page $page
     * @return string
     */
    public function getAreaSlug($page)
    {
        $area = $page->parents()->last();
        return $area ? (string)$area->slug() : '';
    }

    /*
     * Selects all future events
     */
    public function getNextEvents()
    {
        return
            $this
                ->site()
                ->index()
                ->listed()
                ->filterBy('template', 'veranstaltung')
                ->filter(function ($page) {
                    if (strtotime($page->datum()) === -1) {
                        return false;
                    }
                    return (strtotime($page->datum()) > time());
                })
                ->sortBy('datum', 'asc')
                ->limit(3);
    }

    /**
     * Returns the latest news of the club (newest first) for the filterable news section:
     * the latest news in total plus the latest news of every area, so that every filter has something to show.
     *
     * @return \Kirby\Cms\Pages
     */
    public function getLatestNews()
    {
        $news = $this
            ->site()
            ->index()
            ->listed()
            ->filterBy('template', 'bericht')
            ->sortBy('datum', 'desc');

        $ids = $news->limit(self::NEWS_LIMIT)->pluck('id');
        foreach (array_keys(self::AREAS) as $slug) {
            $areaNews = $news->filter(function ($page) use ($slug) {
                return $this->getAreaSlug($page) === $slug;
            });
            $ids = array_merge($ids, $areaNews->limit(self::NEWS_LIMIT)->pluck('id'));
        }

        return $news->filter(function ($page) use ($ids) {
            return in_array($page->id(), $ids, true);
        });
    }

    public function formatDate($date) {
        return date('d.m.Y', strtotime($date));
    }

    public function getLatestResults()
    {
        try {
            if (file_exists(self::$latestCacheFile)) {
                if (time() - filemtime(self::$latestCacheFile) >= 1800) {
                    $this->refreshLatestCache();
                }
            } else {
                $this->refreshLatestCache();
            }
            $output = "";
            $output .= $this->appendNextTeamGame('erste') . '; ';
            $output .= $this->appendNextTeamGame('zweite');
            return $output;
        } catch (\Throwable $e) {
            return false;
        }
    }

    private function appendNextTeamGame($identifier = 'erste')
    {
        $completeData = json_decode(file_get_contents(self::$latestCacheFile), true);
        $data = $completeData[$identifier];

        if ($data['next']['home_game']) {
            return sprintf(
                '%s - %s (%s %s)',
                ($identifier == 'erste' ? 'SFB' : 'SFB2'),
                $data['next']['opponent'],
                date('d.m', strtotime($data['next']['date'])),
                $data['next']['time']
            );
        } else {
            return sprintf(
                '%s - %s (%s %s)',
                $data['next']['opponent'],
                ($identifier == 'erste' ? 'SFB' : 'SFB2'),
                date('d.m', strtotime($data['next']['date'])),
                $data['next']['time']
            );
        }
    }

    private function refreshLatestCache()
    {
        $completeData = [];
        $dataErste = json_decode(file_get_contents('https://api.sf-bronnen.de/football/schedule/erste/alexa'), true);
        $dataZweite = json_decode(file_get_contents('https://api.sf-bronnen.de/football/schedule/zweite/alexa'), true);
        $dataZweite['next']['home_game'] = true;
        $completeData['erste'] = $dataErste;
        $completeData['zweite'] = $dataZweite;
        file_put_contents(self::$latestCacheFile, json_encode($completeData));
    }
}
