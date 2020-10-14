<?php

if (isset($_COOKIE['PHPSESSID'])) {
	session_start();
}

if (isset($_GET['portal']) && !empty($_GET['portal'])) {
	$portal = htmlspecialchars($_GET['portal']);
} else {
	$portal = '';
}

//total number of videos
$stmt = $db->prepare("SELECT COUNT(id) FROM stream");
if (!$stmt->execute()) {
	exit('Database error');
}
$num_videos = $stmt->fetch(PDO::FETCH_NUM)[0];
$stmt = null;

//number of thumbnails per page
$thumbs = array(5, 10, 20);
if (isset($_GET['numperpage']) && !empty($_GET['numperpage'])) {
	$thumbs_per_page = htmlspecialchars($_GET['numperpage']);
	if (!in_array($thumbs_per_page, $thumbs)) {
		$thumbs_per_page = $thumbs[0];
	}
} else {
	$thumbs_per_page = $thumbs[0];
}

//current page
if (isset($_GET['current']) && !empty($_GET['current'])) {
	$current = htmlspecialchars($_GET['current']);
	if (($current < 1) or ($current > ceil($num_videos / $thumbs_per_page))) {
		$current = 1;
	}
} else {
	$current = 1;
}
$offset = $thumbs_per_page * ($current - 1);

//sort by user
if (isset($_GET['user']) && !empty($_GET['user'])) {
	$userid = htmlspecialchars($_GET['user']);
	if ($userid < 1) {
		$userid = 0;
	}
} else {
	$userid = 0;
}

if ($userid == 0) { //select all users
	$stmt = $db->prepare("SELECT streamid, userid, title, description, scheule_time, visibility FROM stream LIMIT $thumbs_per_page OFFSET $offset");
	if (!$stmt->execute()) {
		exit('Database error');
	}
	$stream = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = null;
	$names = array();
	$avatars = array();
	$ids = array();
	$stmt = $db->prepare("SELECT name, avatar FROM users WHERE id = ? LIMIT 1");
	for ($i = 0; $i < count($stream); $i++) {
		if (!array_key_exists($stream[$i]['userid'], $names)) {
			if (!$stmt->execute([$stream[$i]['userid']])) {
				exit('Database error');
			}
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		$names[$i] = $user['name'];
		$ids[$i] = $stream[$i]['userid'];
		if ($user['avatar'] == '') {
			$avatars[$i] = 'assets/logos/skylive round logo dark.png';
		} else {
			$avatars[$i] = $user['avatar'];
		}
	}
	$stmt = null;
} else {
	$stmt = $db->prepare("SELECT name, avatar FROM users WHERE id = $userid LIMIT 1");
	if (!$stmt->execute()) {
		exit('Database error');
	}
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	$stmt = null;
	$names = array();
	$avatars = array();
	$ids = array();
	$stmt = $db->prepare("SELECT streamid, userid, title, description, scheule_time, visibility FROM stream WHERE userid = $userid LIMIT $thumbs_per_page OFFSET $offset");
	if (!$stmt->execute()) {
		exit('Database error');
	}
	$stream = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$stmt = null;
	for ($i = 0; $i < count($stream); $i++) {
		$names[$i] = $user['name'];
		$ids[$i] = $userid;
		if ($user['avatar'] == '') {
			$avatars[$i] = 'assets/logos/skylive round logo dark.png';
		} else {
			$avatars[$i] = $user['avatar'];
		}
	}
}

include 'model/display.php';
