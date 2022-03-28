<?php
if ($page->template() == 'bericht' && $page->getMoreImages()) {

    foreach ($page->getMoreImages() as $image):
        echo sprintf('<meta property="og:title" content="%s"/>', $page->title());
        echo sprintf('<meta property="og:type" content="%s"/>', "article");
        echo sprintf('<meta property="og:url" content="%s"/>', $page->url());
        echo sprintf('<meta property="og:description" content="%s"/>', substr($page->text(), 0, 300) . '...');
        if ($image) {
            echo sprintf('<meta property="og:image" content="%s"/>', $image->resize(1500)->url());
            echo sprintf('<meta property="og:image:secure_url" content="%s"/>', $image->resize(1500)->url());
        }
    endforeach;

}
