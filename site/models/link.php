<?php

/**
 * Class LinkPage
 *
 * Menu entry which opens an external page. The page itself is never shown: its URL is the link target, so the
 * main navigation and the footer link directly to the external page.
 *
 * @author Magnus Buk <MagnusBuk@gmx.de>
 */
class LinkPage extends Page
{
    /**
     * External target of the menu entry (empty if not maintained yet)
     */
    public function target(): string
    {
        return trim((string)$this->content()->get('link')->value());
    }

    /**
     * Whether the link has to be opened in a new tab
     */
    public function opensInNewTab(): bool
    {
        return $this->content()->get('newTab')->toBool(true);
    }

    public function url($options = null): string
    {
        if ($options === null && $this->target() !== '') {
            return $this->target();
        }

        return parent::url($options);
    }
}
