<?php

header('Content-type: application/json; charset=utf-8');

// Get all events
$result = [];

foreach ($page->parent()->children()->visible()->sortBy('datum', 'DESC') as $event):

    $eventData = [];
    $eventData['dateStart'] = (string)$event->datum();
    $eventData['dateEnd'] = (string)$event->datumbis();
    $eventData['title'] = (string)$event->title();
    $eventData['text'] = (string)strip_tags($event->text()->markdown());
    $eventData['location'] = (string)$event->ort();

    $result[] = $eventData;

endforeach;

echo json_encode($result);