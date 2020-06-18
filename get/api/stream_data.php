<?php

if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Access denied');
}
header('Content-Type: application/json');

if ($_GET['token'] == 'test') {
    echo '{"streamid":"6","title":"The Decentralized Financial Crisis: Attacking DeFi","description":"<a href=\"https:\/\/www.meetup.com\/Open-Blockchain-Workshop-Series\/events\/270437669\/\" class=\"card-text\">Event info<\/a><br>\r\n<a href=\"https:\/\/us02web.zoom.us\/j\/87278019230\" class=\"card-text\">Join meeting<\/a>","scheule_time":"1589904000","started":"1","finished":"1","visibility":"public"}';
    exit();
}


require 'model/get_stream.php';
$stream_data = get_stream_data_from_token($_GET['token']);

if (!empty($stream_data)) {
    echo json_encode($stream_data);
} else {
    header("HTTP/1.0 404 Not Found");
    echo json_encode(array("error" => "404 Not Found"));
}