<?php

include_once 'Inicializar.php';
include_once '../php/model/bd/Persistence.php';
header('Access-Control-Allow-Origin: *');

//http://www.sms2way.com.br/       biblioteca de envio de SMS

header("Content-Type: text/html; charset=UTF-8", true);
set_time_limit(1000);


$post = $_POST;

$tokenApi   = 'c0478dff382ba71ae8d8';
$NumeroDest = $post["numero"];
$Msg        = 'Codigo: ' . $post["codigo"];
$Msg        = str_replace(' ', '%20', $Msg);
$Msg        = str_replace('-', '', $Msg);
$TipoMsg    = "3";   //6 e pra msg de testes, 3 e pra msg normal. Ent�o utilize 6 somente em ambiente de testes de sua aplica��o

    $rest = file_get_contents("http://172.246.132.11/app/modulo/api/index.php?action=sendSMS&token=".$tokenApi."&tipo=".$TipoMsg."&numbers=".$NumeroDest."&msg=".$Msg."&assunto=");

$status = json_decode("[".$rest."]", true);

if ($status[0]["status"] == 1){
    ob_clean();
    echo '{"Codigo":"1","Descricao":"SMS enviado!"}';
} else {
    ob_clean();
    echo '{"Codigo":"0","Descricao":"Erro ao enviar SMS!"}';
}
