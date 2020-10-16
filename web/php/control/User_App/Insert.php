<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/user_app.php';

$User = new User();

$Valid = true;
$post = $_POST;


$User->setId($post['Id']);
$User->setNome($post['NOme']);
$User->setTelefone($post['Telefone']);
$User->setCpf($post['Cpf']);


if ($Valid) {
    if ($User->getId() == null || $User->getId() == "") {
        if ($User->InBase('Insert', '')) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else{
        if ($User->InBase('Update', ' id='.$User->getId())) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}
