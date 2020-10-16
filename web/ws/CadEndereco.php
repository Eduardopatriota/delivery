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

if (!isset($json[0]["id"])) {
    $End->setId(null);
} else {
    $End->setId($json[0]["id"]);
}

$End->setNome($post["nome"]);
$End->setRua($post["rua"]);
$End->setNumero($post["numero"]);
$End->setBairro($post["bairro"]);
$End->setComplemento($post["complemento"]);
$End->setIdUser($post["idUser"]);
$End->setId_Bairro(preg_replace("/[^0-9]/", "", $post["bairro"]));

if ($End->getId() != null){
    $Acao = "Update";
    $Where = " id = ".$End->getId();
} else {
    $Acao = "Insert";
    $Where = "";
}

if ($End->InBase($Acao, $Where)) {
    
    $Bairros = new Bairros();
    $Result = $Bairros->Select(" id = " . preg_replace("/[^0-9]/", "", $post["bairro"]));

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $JsonOut = $JsonOut . '{"valor":"' . $Linhas['Valor'] . '"}';
    }

    ob_clean();
    echo $JsonOut;
}

