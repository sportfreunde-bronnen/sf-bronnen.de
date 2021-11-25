<?php

header('Content-type: application/json; charset=utf-8');

$result = [];

foreach ($page->parent()->children()->listed()->sortBy('datum', 'DESC')->limit(5) as $news):

    $newsData = [];
    $newsData['id'] = (string)$news->getId();
    $newsData['date'] = (string)$news->datum();
    $newsData['title'] = (string)$news->title()->text();
    $newsData['text'] = (string)strip_tags($news->text()->markdown());
    $newsData['author'] = (string)$news->author()->toUser()?->alias() ?? 'SFB';

    if ($news->textImages()->isNotEmpty()):
        $newsData['img'] = [];
        foreach (explode(',', $news->textImages()) as $imageUrl):
            $image = $news->images()->find($imageUrl);
            $newsData['img'][] = $image->url();
        endforeach;
    endif;

    $result[] = $newsData;

endforeach;

echo json_encode($result);