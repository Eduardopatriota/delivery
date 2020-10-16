<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once $_SESSION['_DIR_'] . '/php/model/EnderecoUser.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache
set_time_limit(1000);

$post = $_POST;


$Endereco = new Persistece();


$Result = $Endereco->Select("select en.id, en.Nome, en.Rua, en.Numero, en.id_bairro, en.Bairro, en.Complemento, en.Cep, en.id_user, tx.Valor from enderecos en left join taxaentrega tx on tx.id = en.id_bairro "
        . " where "
        . " en.id_user = '".$post["id_user"]."'");

$Exit = "[";
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Exit = $Exit.'{"id":"'.$Linhas['id'].'", "Nome":"'.$Linhas['Nome'].'", "Rua":"'.$Linhas['Rua'].'", "Numero":"'.$Linhas['Numero'].'", '
            . '"Bairro":"'.$Linhas['Bairro'].'", "Complemento":"'.$Linhas['Complemento'].'", '
            . '"Valor":"'.$Linhas['Valor'].'"}';
    
    if($i < mysqli_num_rows($Result)-1){
        $Exit = $Exit.',';
    }
    $i++;
}


$Exit = $Exit."]";

ob_clean();
echo $Exit;