<?php

/**
 * Startpage model
 */
class VeranstaltungPage extends Page
{

    public function getSubHeadline()
    {
        return $this->shorttitle() ?? null;
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
                ->filterBy('id', '!=', $this->id())
                ->filter(function ($page) {
                    if (strtotime($page->datum()) === -1) {
                        return false;
                    }
                    return (strtotime($page->datum()) > time());
                })
                ->sortBy('datum', 'asc')
                ->limit(3);
    }
}
