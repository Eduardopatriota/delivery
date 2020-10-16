<?php

if(!isset($_SESSION)){
    session_start();
}
include_once $_SESSION['_DIR_'].'/php/model/motoboy.php';

$GrupoProd = new MotoBoy();
