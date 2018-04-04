<?php

field::$methods['toGermanDate'] = function ($field) {
    $date = strtotime($field);
    if (!$date) {
        return 'TBD';
    }
    if (date('H', $date) == '00') {
        return date('d.m.Y', $date);
    }
    return date('d.m.Y H:i', $date);
};