<?php

$stmt_streams = $db->prepare("SELECT `streamid`, `token`, `title`, `description`, `scheule_time`, `started`, `finished`, `visibility` FROM `stream` WHERE userid = ? AND visibility != 'deleted' ORDER BY streamid DESC");
if (!$stmt_streams->execute([$_SESSION['id']])) {
    exit('Database error');
}


$displayPage = 'index';
include 'model/display_studio.php';