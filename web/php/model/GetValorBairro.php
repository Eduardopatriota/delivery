<?php

include_once 'Inicializar.php';
include_once '../php/model/bd/Persistence.php';
include_once '../php/model/bairros.php';


header("Content-Type: text/html; charset=UTF-8", true);
set_time_limit(1000);

$post = $_POST;

$json = json_decode($post['JSon'], true);

$Bairros = new Bairros();

$Result = $Bairros->Select(" id = ".$json[0]["bairro"]);
$JsonOut = "[";

while ($Linhas = mysql_fetch_assoc($Result)){
    $JsonOut = $JsonOut . '{"valor":"'.$Linhas['Valor'].'"}';
}

$JsonOut = $JsonOut."]";


echo $JsonOut;