<?php

/**
 * Class BerichtPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class AktuellesPage extends Page
{
    public function getUsedTags()
    {
        $tags = $this->children()->visible()->pluck('tags', ',', true);
        asort($tags);
        return $tags;
    }
}