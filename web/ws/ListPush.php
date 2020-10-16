<?php
header("Content-Type: text/html; charset=UTF-8", true);
include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
//include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache

set_time_limit(1000);

$post = $_POST;

//$json = json_decode($post['JSon'], true);

//$Persistece = new Persistece();

$GrupoProd = new GrupoProd();

$Result = $GrupoProd->Execute(" SELECT * FROM notificacoes");

$Exit = "[";
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Exit = '{"nome":"'.$Linhas['Titulo'].'","legend":"'.$Linhas['Texto'].'", "img":"'.$Linhas['imagem'].'"}';
   
}

$Exit = "[".$Exit."]";

ob_clean();
echo $Exit;