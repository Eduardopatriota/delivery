<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

$Bairros = new Bairros();

$Valid = true;
$post = $_POST;


$Bairros->setId($post['Id']);
$Bairros->setNome($post['Nome']);
$Bairros->setValor($post['Valor']);
$Bairros->setEmpresa($post['Empresa']);


if ($Bairros->getNome() == null || $Bairros->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
}

if ($Bairros->getValor() == null || $Bairros->getValor() == "") {
    $Bairros->setValor(0);
}

if ($Valid) {
    if ($Bairros->getId() == null || $Bairros->getId() == "") {
        if ($Bairros->InBase('Insert', '')) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else{
        if ($Bairros->InBase('Update', ' id='.$Bairros->getId())) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}
