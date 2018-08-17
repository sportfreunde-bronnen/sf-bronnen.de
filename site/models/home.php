<?php

/**
 * Startpage model
 */
class HomePage extends Page
{

    /*
     * Selects all future events
     */
    public function getNextEvents()
    {
        return
            $this
                ->site()
                ->index()
                ->visible()
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
            ->visible()
            ->filterBy('template', 'bericht')
            ->sortBy('datum', 'desc')
            ->limit(6);
    }

    public function formatDate($date) {
        return date('d.m.Y', strtotime($date));
    }
}
