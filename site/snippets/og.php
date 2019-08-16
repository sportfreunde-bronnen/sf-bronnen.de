<?php
if ($page->template() === 'bericht') {

    foreach (explode(',', $page->textImages()) as $imageUrl):
        $image = $page->images()->find($imageUrl);
        echo sprintf('<meta property="og:type" content="%s"/>', "article");
        echo sprintf('<meta property="og:url" content="%s"/>', $page->url());
        echo sprintf('<meta property="og:url" content="%s"/>', $page->url());
        echo sprintf('<meta property="og:description" content="%s"/>', substr($page->text(), 0, 300) . '...');
        echo sprintf('<meta property="og:image" content="%s"/>', $image->resize(1500)->url());
        echo sprintf('<meta property="og:image:secure_url" content="%s"/>', $image->resize(1500)->url());
    endforeach;

}