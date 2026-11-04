<?php
/**
 * The availability calendar is part of the SVZ page (tab "Verfügbarkeit").
 * Old links to this page are forwarded, a requested month (calMonth / calYear) is kept.
 */

$query = array_filter([
    'calMonth' => get('calMonth'),
    'calYear' => get('calYear'),
]);

go($page->parent()->url() . ($query ? '?' . http_build_query($query) : '') . '#verfuegbarkeit');
