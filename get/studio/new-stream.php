<?php
$edit = [];
$edit['title'] = '';
$edit['description'] = '';
$edit['scheule_time'] = '';
$edit['visibility'] = '';

if (isset($_GET['edit']) && intval($_GET['edit']) > 0) {
	$edit_id = intval($_GET['edit']);

	$stmt = $db->prepare("SELECT title, description, scheule_time, visibility FROM stream WHERE streamid = ? LIMIT 1");
	if (!$stmt->execute([$edit_id])) {
		exit('Database error');
	}
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$edit['title'] = $row['title'];
	$edit['description'] = $row['description'];
	$edit['scheule_time'] = $row['scheule_time'];
	$edit['visibility'] = $row['visibility'];
	$stmt = null;

}

include 'model/display_studio.php';