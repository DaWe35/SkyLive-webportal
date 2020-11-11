<?php
session_start();
session_unset();
session_destroy();

if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', null, -1, '/'); 
}

header('Location: ' . URL);
exit();