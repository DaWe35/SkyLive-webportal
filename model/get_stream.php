<?php
function get_streamid_from_token($token) {
    global $db;
    $stmt = $db->prepare("SELECT streamid FROM stream WHERE token = ? LIMIT 1");
    if (!$stmt->execute([$token])) {
        exit('Database error');
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;

    if (isset($row['streamid'])) {
        return $row['streamid'];
    } else {
        bruteforce_increase_failed_attempts();
        return false;
    }
}

function get_stream_data_from_token($token) {
    global $db;
    $stmt = $db->prepare("SELECT streamid, title, description, scheule_time, started, finished, visibility FROM stream WHERE token = ? LIMIT 1");
    if (!$stmt->execute([$token])) {
        exit('Database error');
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt = null;

    if (isset($row) && !empty($row)) {
        return $row;
    } else {
        bruteforce_increase_failed_attempts();
        return false;
    }
}