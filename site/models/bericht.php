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

    public function getTitleImage()
    {
        $images = explode(',', $this->textImages());
        if (count($images) === 0) {
            return false;
        }
        return $this->images()->find($images[0]);
    }

    public function getMoreImages()
    {
        $images = explode(',', $this->textImages());
        if (count($images) <= 1) {
            return false;
        }
        $imagesArray = [];
        foreach ($images as $imageUrl) {
            $imagesArray[] = $this->images()->find($imageUrl);
        }
        return array_slice($imagesArray, 1, 1);
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
}