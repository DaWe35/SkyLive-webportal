<?php

if (SUBPAGE == '') {
    include 'get/api/index.php';
} else {
    include 'get/api/' . SUBPAGE . '.php';
}