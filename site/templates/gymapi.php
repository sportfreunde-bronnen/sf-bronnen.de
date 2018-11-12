<?php

header('Content-type: application/json; charset=utf-8');

$result = [];

foreach ($site->index()->visible()->filterBy('template', 'kurs') as $course):

    $courseData = [];
    $courseData['id'] = (string)($course->alexaId()->isEmpty() ? $course->title()->text() : $course->alexaId());
    $courseData['title'] = (string)$course->title()->text();
    $courseData['text'] = (string)strip_tags($course->text()->markdown());
    $courseData['times'] = (string)trim($course->zeiten());
    $courseData['place'] = (string)$course->ort();
    $courseData['type'] = (string)$course->parent()->title();
    $courseData['images'] = [];

    if ($course->images()->count() > 0):
        foreach ($course->images()->limit(2) as $image):
            $courseData['images'][] = $image->url();
        endforeach;
    endif;

    $result[] = $courseData;

endforeach;

echo json_encode($result);