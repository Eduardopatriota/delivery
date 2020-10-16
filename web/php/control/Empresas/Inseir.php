<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/empresas.php';

$Cupom = new Empresas();

$Valid = true;
$post = $_POST;

$Cupom->setId($post['Id']);
$Cupom->setNome($post['Nome']);

if ($Cupom->getNome() == null || $Cupom->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
    exit();
}

if ($Valid) {
    if ($Cupom->getId() == null || $Cupom->getId() == "") {
        if ($Cupom->InBase('Insert', '')) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else {
        if ($Cupom->InBase('Update', ' id=' . $Cupom->getId())) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}
