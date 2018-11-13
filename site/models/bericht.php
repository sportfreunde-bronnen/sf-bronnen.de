<?php

/**
 * Class BerichtPage
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class BerichtPage extends DownloadsPage
{
    public function getId()
    {
        return md5($this->id());
    }

    public function getNewsGroupsAsShuffleArray()
    {
        $tags = $this->tags();
        $tags = explode(',', $tags);
        return sprintf('["all","%s"]', implode('","', $tags));
    }
}