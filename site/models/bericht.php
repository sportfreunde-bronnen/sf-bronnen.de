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

    /**
     * Returns the tags of the article which should be shown to the visitor
     *
     * @return string[]
     */
    public function getDisplayTags()
    {
        $hidden = ['gesamtverein', 'fussball', 'gymnastik'];
        $tags = array_map('trim', explode(',', (string)$this->tags()));
        return array_values(array_filter($tags, function ($tag) use ($hidden) {
            return $tag !== '' && !in_array($tag, $hidden, true);
        }));
    }

    /**
     * Returns the name of the author (internal or external)
     *
     * @return string
     */
    public function getAuthorName()
    {
        if ($this->author2()->isNotEmpty()) {
            return (string)$this->author2();
        }
        $user = $this->author()->toUser();
        $alias = $user ? $user->alias() : null;
        return ($alias && $alias->isNotEmpty()) ? (string)$alias : 'SFB';
    }

    /**
     * Returns the date of the article in a readable german format (e.g. 10. März 2025)
     *
     * @return string
     */
    public function getDateLong()
    {
        $months = [
            1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni',
            'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember',
        ];
        $timestamp = strtotime((string)$this->datum());
        if (!$timestamp) {
            return '';
        }
        return sprintf('%d. %s %d', date('j', $timestamp), $months[(int)date('n', $timestamp)], date('Y', $timestamp));
    }

    /**
     * Returns the date of the article in a machine readable format
     *
     * @return string
     */
    public function getDateIso()
    {
        $timestamp = strtotime((string)$this->datum());
        return $timestamp ? date('Y-m-d', $timestamp) : '';
    }

    /**
     * Returns a plain text excerpt of the article text
     *
     * @param int $chars
     * @return string
     */
    public function getExcerpt($chars = 160)
    {
        return (string)$this->text()->excerpt($chars);
    }

    /**
     * Returns the estimated reading time in minutes
     *
     * @return int
     */
    public function getReadingTime()
    {
        $text = trim(strip_tags((string)$this->text()->kirbytext()->value()));
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        return max(1, (int)ceil(count($words ?: []) / 300));
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