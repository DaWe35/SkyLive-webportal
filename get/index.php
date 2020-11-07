<?php

$current_time_plus_10_min = time() - 1800;

$streams_stmt = $db->prepare("SELECT `streamid`, `title`, `description`, `scheule_time`, `started`, `finished` FROM `stream` WHERE visibility = 'public' AND (started = 1 OR scheule_time > ?) ORDER BY scheule_time DESC, streamid DESC LIMIT 100");
if (!$streams_stmt->execute([$current_time_plus_10_min])) {
    exit('Database error');
}

include 'model/display.php';

