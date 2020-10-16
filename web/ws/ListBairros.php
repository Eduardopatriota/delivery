<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache
set_time_limit(1000);


$Persistece = new Persistece();

$Bairros  = new Bairros();

$Result = $Bairros->Select(' 1 = 1 ORDER BY Nome');

$Exit = "[";
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Exit = $Exit.'{"id":"'.$Linhas['id'].'", "nome":"'.$Linhas['id'].' - '.$Linhas['Nome'].'", "valor":"'.$Linhas['Valor'].'"}';
    
    if($i < mysqli_num_rows($Result)-1){
        $Exit = $Exit.',';
    }
    $i++;
}

$Exit = $Exit."]";


ob_clean();
echo $Exit;