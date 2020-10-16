<?php
header("Content-Type: application/json; charset=UTF-8", true);
header('Access-Control-Allow-Origin: *');

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

$Result = $GrupoProd->Select(" tipogrupo = 'Cardapio' ORDER BY seq");

$Exit = "[";
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Exit = $Exit.'{"id":"'.$Linhas['id'].'","nome":"'.$Linhas['nome'].'", "imagem":"'.$Linhas['imagem'].'"}';
    
    if($i < mysqli_num_rows($Result)-1){
        $Exit = $Exit.',';
    }
    $i++;
}

$Exit = $Exit."]";

ob_clean();
echo $Exit;