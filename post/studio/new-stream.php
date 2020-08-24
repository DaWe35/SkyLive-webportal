<?php

function random_str(int $length = 64, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

$token = random_str();

$title = htmlspecialchars($_POST['title']);
$description = htmlspecialchars($_POST['description']);

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

    $stmt = $db->prepare("UPDATE stream SET title=?, `description`=?, scheule_time=?, visibility=? WHERE streamid = ? AND userid = ?");

    if (!$stmt->execute([$title, $description, $_POST['scheule_time'], $_POST['visibility'], $_POST['edit_id'], $_SESSION['id']])) {
        // print_r($stmt->errorInfo());
        exit('Database error');
    }
    $streamid = $_POST['edit_id'];
} else {
    $stmt = $db->prepare("INSERT INTO stream (token, userid, title, `description`, scheule_time, visibility) VALUES (?, ?, ?, ?, ?, ?)");

    if (!$stmt->execute([$token, $_SESSION['id'], $title, $description, $_POST['scheule_time'], $_POST['visibility']])) {
        // print_r($stmt->errorInfo());
        exit('Database error');
    }
    $streamid = $db->lastInsertId();
}


require('model/image_resize.php');
save_image($streamid, $upload_folder);
header('Location: /studio');