<?php
/**
 * Fallback for the external link pages: the menus link directly to the external target (see LinkPage::url()),
 * so this template is only used if the internal URL of the page is called directly.
 *
 * @var LinkPage $page
 */
if ($page->target() !== '') {
    go($page->target(), 302);
}

go('/', 302);
