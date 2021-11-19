<?php

/**
 * Class PressPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class PressPage extends Page
{
    /**
     * Get all used tags for the page
     *
     * @return array
     */
    public function getUsedTags()
    {
        $tags = $this->articles()->toStructure()->pluck('tags', ',', true);
        asort($tags);
        return $tags;
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

    public function isInitialFiltering()
    {
        return !is_null(get('tag', null));
    }

    public function getNewsGroupsAsShuffleArray($structure)
    {
        $tags = $structure->tags();
        $tags = explode(',', $tags);
        return sprintf('["all","%s"]', implode('","', $tags));
    }
}