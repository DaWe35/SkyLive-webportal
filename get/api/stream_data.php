<?php

if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}


require 'model/get_stream.php';
$stream_data = get_stream_data_from_token($_GET['token']);

header('Content-Type: application/json');
if (!empty($stream_data)) {
    echo json_encode($stream_data);
} else {
    header("HTTP/1.0 404 Not Found");
    echo json_encode(array("error" => "404 :ot found"));
}