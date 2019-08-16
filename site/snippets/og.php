<?php
if ($page->template() === 'bericht') {

    foreach (explode(',', $page->textImages()) as $imageUrl):
        $image = $page->images()->find($imageUrl);
        echo sprintf('<meta property="og:image" content="%s"/>', $image->resize(1500)->url());
        echo sprintf('<meta property="og:image:secure_url" content="%s"/>', $image->resize(1500)->url());
    endforeach;

}