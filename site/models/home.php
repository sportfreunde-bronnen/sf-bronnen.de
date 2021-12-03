<?php

/**
 * Startpage model
 */
class HomePage extends Page
{

    static $latestCacheFile = __DIR__ . '/../cache/latest.json';

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

    public function getLatestNews()
    {
        return $this
            ->site()
            ->index()
            ->listed()
            ->filterBy('template', 'bericht')
            ->sortBy('datum', 'desc')
            ->limit(4);
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
