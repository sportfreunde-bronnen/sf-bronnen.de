<?php

/**
 * Class BerichtPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class AktuellesPage extends Page
{
    /**
     * Get all used tags for the page
     *
     * @return array
     */
    public function getUsedTags()
    {
        $tags = $this->children()->visible()->pluck('tags', ',', true);
        $tags = array_diff($tags, ["gesamtverein", "fussball", "gymnastik"]);
        asort($tags);
        return $tags;
    }

    /**
     * Returns all visible articles for the article overview
     *
     * @return Collection
     */
    public function getArticles()
    {
        return $this->children()
            ->visible()
            ->sortBy('datum', 'desc');
    }
}