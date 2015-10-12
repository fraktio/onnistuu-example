<?php

if ($_SERVER['REMOTE_ADDR'] !== '185.20.137.231') {
    die();
}

$id = $_GET['id'];

if (!preg_match('/^[0-9a-f\-]+$/', $id)) {
    die();
}

echo file_get_contents('../data/' . $id . '.pdf');

