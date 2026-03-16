<?php
function getDateRange(): array
{
    $mode  = $_GET['mode']  ?? 'week';
    $start = $_GET['start'] ?? date('Y-m-d', strtotime('monday this week'));
    $end   = $_GET['end']   ?? date('Y-m-d', strtotime('sunday this week'));

    return [
        'start' => $start,
        'end'   => $end,
        'mode'  => $mode
    ];
}
