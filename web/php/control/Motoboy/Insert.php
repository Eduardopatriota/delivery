<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/motoboy.php';

$Moto = new MotoBoy();

$Valid = true;
$post = $_POST;


$Moto->setId($post['Id']);
$Moto->setNome($post['Nome']);


if ($Moto->getNome() == null || $Moto->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
    exit();
}

if ($Valid) {
    if ($Moto->getId() == null || $Moto->getId() == "") {
        if ($Moto->InBase('Insert', '')) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else{
        if ($Moto->InBase('Update', ' id='.$Moto->getId())) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}
