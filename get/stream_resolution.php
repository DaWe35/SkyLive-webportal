<?php
if (isset($_COOKIE['PHPSESSID'])) {
    session_start();
}

header("Content-Type: text/plain");
header("Access-Control-Allow-Origin: *");
if (!isset($_GET['streamid']) || strlen($_GET['streamid']) < 1) {
    exit('Empty GET value: streamid');
}

if (!isset($_GET['resolution']) || empty($_GET['resolution'])) {
    exit('Empty GET value: resolution');
}

$stmt = $db->prepare("SELECT `streamid`, `userid`, `title`, `description`, `scheule_time`, `started`, `finished`, visibility FROM `stream` WHERE streamid = ? LIMIT 1");
if (!$stmt->execute([$_GET['streamid']])) {
    exit('Database error');
}
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

if (!isset($stream['streamid'])) {
    http_response_code(404);
    exit('Stream not found');
}

if ($stream['visibility'] == 'deleted') {
    http_response_code(404);
    exit('Stream has removed');
}


// protect private streams
if ($stream['visibility'] == 'private' && (!isset($_SESSION['id']) || $_SESSION['id'] != $stream['userid']) && $_SESSION['id'] != 1) {
    http_response_code(403);
    exit('Access denied, please log in if this is your content');
}

// set portal
if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = filter_var($_GET['portal'], FILTER_SANITIZE_URL);
} else {
    $portal = 'https://siasky.net';
}


include('model/stream.php');


if ($stream['streamid'] == 74) { // 0-24 music live
    $videos = [41, 40, 18];
    print_loop_stream($videos, $stream, $portal);
} else {
    print_stream($stream, $portal);
}


if ($stream['finished'] == 1) {
    echo "#EXT-X-ENDLIST\n";
}