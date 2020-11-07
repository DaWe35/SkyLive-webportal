<?php

$title = htmlspecialchars($_POST['title']);
$description = htmlspecialchars($_POST['description']);
$current_time = time();

if (isset($_POST['edit_id']) && !empty($_POST['edit_id'])) {
    $stmt = $db->prepare("SELECT userid FROM stream WHERE streamid = ? LIMIT 1");
    if (!$stmt->execute([$_POST['edit_id']])) {
        exit('Database error');
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['userid'] != $_SESSION['id']) {
        exit('Access denied');
    }
    $stmt = null;

    $stmt = $db->prepare("UPDATE stream SET title=?, `description`=?, visibility=? WHERE streamid = ? AND userid = ?");

    if (!$stmt->execute([$title, $description, $_POST['visibility'], $_POST['edit_id'], $_SESSION['id']])) {
        // print_r($stmt->errorInfo());
        exit('Database error');
    }
    $streamid = $_POST['edit_id'];
} else {
    $stmt = $db->prepare("INSERT INTO stream (token, userid, title, `description`, scheule_time, visibility, `format`, `started`, finished) VALUES ('', ?, ?, ?, ?, ?, 'video', '1', '1')");

    if (!$stmt->execute([$_SESSION['id'], $title, $description, $current_time, $_POST['visibility']])) {
        // print_r($stmt->errorInfo());
        exit('Database error');
    }
    $streamid = $db->lastInsertId();

    $stmt = $db->prepare("INSERT INTO chunks (`streamid`, `length`, `skylink`, `is_first_chunk`, `resolution`) VALUES (?, '0', ?, '1', 'original')");
    if (!$stmt->execute([$streamid, $_POST['skylink']])) {
        header('HTTP/1.0 500 Internal Server Error');
        exit('Database error');
    }
    $stmt = null;
}

if (isset($_FILES["file"]["name"])) {
    require('model/image_resize.php');
    save_image($streamid, $upload_folder);
}

echo $streamid;