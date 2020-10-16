<?php
header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once '../php/model/EnderecoUser.php';
include_once '../php/model/bairros.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache

set_time_limit(1000);

$post = $_POST;

$json = json_decode(utf8_decode($post['JSon']), true);

$End = new EnderecoUser();


$End->Execute("Delete from enderecos where id = ". $post['id']);
    