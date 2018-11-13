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
        $tags = array_diff($tags, ["gesamtverein", "fussball", "gymnastik"]);
        asort($tags);
        return $tags;
    }
}