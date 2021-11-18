<?php

/**
 * Class BerichtPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class AktuellesPage extends \Kirby\Cms\Page
{
    /**
     * Get all used tags for the page
     *
     * @return array
     */
    public function getUsedTags()
    {
        $tags = $this->children()->listed()->pluck('tags', ',', true);
        $tags = array_diff($tags, ["gesamtverein", "fussball", "gymnastik"]);
        asort($tags);
        return $tags;
    }

    /**
     * Returns all visible articles for the article overview
     *
     * @return \Kirby\Cms\Collection
     */
    public function getArticles()
    {
        return $this->children()
            ->listed()
            ->sortBy('datum', 'desc');
    }

    public function isInitialFiltering()
    {
        return !is_null(get('tag', null));
    }

    /**
     * Returns the active tag group
     */
    public function getActiveTagGroup()
    {
        if ($this->isInitialFiltering()) {
            return get('tag');
        }
        return 'all';
    }
}