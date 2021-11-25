<?php

include 'downloads.php';

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
        $tags = array_map('trim', $tags);
        return sprintf('["all","%s"]', implode('","', $tags));
    }

    public function getTitleImage()
    {
        return $this->textImages()->toFiles()->first();
    }

    public function getMoreImages()
    {
        $images = $this->textImages()->toFiles();
        if ($images->count() <= 1) {
            return false;
        }
        return $images->slice(1,1);
    }

    public function getLatestNews()
    {
        return $this
            ->site()
            ->index()
            ->listed()
            ->filterBy('template', 'bericht')
            ->sortBy('datum', 'desc')
            ->limit(6);
    }
}