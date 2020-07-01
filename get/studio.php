<?php
session_start();

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    header('Location: /login');
    exit();
}

$pagetitle = 'SkyLive studio';

if (SUBPAGE == '') {
    include 'get/studio/index.php';
} else {
    include 'get/studio/' . SUBPAGE . '.php';
}
