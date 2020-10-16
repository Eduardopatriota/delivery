<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
include_once '../php/model/bd/Persistence.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache

set_time_limit(1000);

$post = $_POST;


$Persistece = new Persistece();

if ($post["acao"] == "cadastrar") {
    $Coluns = array("nome", "telefone", "cpf", "dt_nacimento");
    $Values = array(
        "'" . strtoupper($post["nome"]) . "'", "'" . $post["telefone"] . "'", "'" . $post["CPF"] . "'", "'" . $post["Dt_Nsc"] . "'"
    );

    if ($Persistece->Insert('user_app', $Coluns, $Values)) {
        $Result = $Persistece->Select("select * from user_app upp where upp.telefone = '" . $post["telefone"] . "'");

        $li = mysqli_num_rows($Result);
        if ($li > 0) {
            $JsonOut = "";
            while ($Linhas = mysqli_fetch_assoc($Result)) {
                $JsonOut = ',"id":"' . $Linhas['id'] . '", "Nome":"' . $Linhas['nome'] . '","Telefone":"' . $Linhas['telefone'] . '","CPF":"' . $Linhas['cpf'] . '","Dt_Nsc":"' . $Linhas['dt_nacimento'] . '"';
            }
            ob_clean();
            echo '{"Codigo":"1","Descricao":"Usuario Cadastrado"' . $JsonOut . '}';
        } else {
            ob_clean();
            echo '{"Codigo":"0","Descricao":"Usuario Nao Cadastrado"}';
        }
    }
} else {
    $Result = $Persistece->Select("select * from user_app upp where upp.telefone = '" . $post["numero"] . "'");

    $li = mysqli_num_rows($Result);
    if ($li > 0) {
        $JsonOut = "";
        while ($Linhas = mysqli_fetch_assoc($Result)) {
            $JsonOut = ',"id":"' . $Linhas['id'] . '", "Nome":"' . $Linhas['nome'] . '","Telefone":"' . $Linhas['telefone'] . '","CPF":"' . $Linhas['cpf'] . '","Dt_Nsc":"' . $Linhas['dt_nacimento'] . '"';
        }
        ob_clean();
        echo '{"Codigo":"1","Descricao":"Usuario Cadastrado"' . $JsonOut . '}';
    } else {
        ob_clean();
        echo '{"Codigo":"1","Descricao":"Usuario Nao Cadastrado"}';
    }
}
