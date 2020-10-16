<?php

if (!isset($_SESSION)) {
    session_start();
}

$_SESSION['_DIR_'] = '/home1/jarder00/delivery';

//$_SESSION['_DIR_'] = str_replace("\ws", "", realpath(dirname(__FILE__)));

//echo $_SESSION['_DIR_'];