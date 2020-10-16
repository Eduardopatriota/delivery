<?php

if(!isset($_SESSION)){
    session_start();
}
include_once $_SESSION['_DIR_'].'/php/model/GrupoProd.php';

$GrupoProd = new GrupoProd();
