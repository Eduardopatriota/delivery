<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Access-Control-Allow-Origin: *');


include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/cupon.php';

set_time_limit(1000);

$post = $_POST;

//$json = json_decode($post['JSon'], true);

$Persistece = new Persistece();

$Cupom = new Cupom();

$Result = $Cupom->Execute("Update user_app uu set uu.token_firebase = '".$post['token']. "' where uu.id = ".$post['usuario']);
$Exit = "";

