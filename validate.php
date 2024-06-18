<?php
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
function isPastDate($date) {
    $currentDate = new DateTime(); 
    $inputDate = new DateTime($date);

    return $inputDate < $currentDate;
}