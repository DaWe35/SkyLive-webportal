<?php

if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}


require 'model/get_stream.php';
$stream_data = get_stream_data_from_token($_GET['token']);

header('Content-Type: application/json');
echo json_encode($stream_data);