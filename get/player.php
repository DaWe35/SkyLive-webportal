<?php
if (isset($_COOKIE['PHPSESSID'])) {
    session_start();
}

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
    $portal = htmlspecialchars($_GET['portal']);
} else {
    $portal = '';
}

$stmt = $db->prepare("SELECT streamid, userid, title, description, scheule_time, visibility, finished, `format` FROM stream WHERE streamid = ? LIMIT 1");
if (!$stmt->execute([$_GET['s']])) {
    exit('Database error');
}
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

if ($stream['visibility'] == 'private' && (!isset($_SESSION['id']) || $_SESSION['id'] != $stream['userid']) && $_SESSION['id'] != 1) {
    http_response_code(403);
    exit('Access denied, please log in if this is your content');
}

$stmt = $db->prepare("SELECT name, avatar, id FROM users WHERE id = ? LIMIT 1");
if (!$stmt->execute([$stream['userid']])) {
    exit('Database error');
}
$channel = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = null;

$pagetitle = $stream['title'];
$ogimage = image_print($stream['streamid'], 600);
$stream_url = 'stream.m3u8?streamid=' . htmlspecialchars($_GET['s']) . '&portal=' . $portal;


if (isset($_GET['portal'])) {
    $portal = htmlspecialchars($_GET['portal']);
} else {
    $portal = 'https://siasky.net/';
}

if ($stream['format'] == 'video' || $stream['format'] == 'audio') {
    $stmt = $db->prepare("SELECT `skylink` FROM `chunks` WHERE streamid = ? AND resolution = 'original' ORDER BY id ASC");
	if (!$stmt->execute([$stream['streamid']])) {
		exit('Database error');
    }
    $chunk_info = $stmt->fetch(PDO::FETCH_ASSOC);
    $video_src = 'src="' . $portal . $chunk_info['skylink'] . '"';
} else {
    $video_src = '';
}


include 'model/display.php';