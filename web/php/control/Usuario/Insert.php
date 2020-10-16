<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/Usuario.php';

$User = new Usuario();

$Valid = true;
$post = $_POST;


$User->setId($post['Id']);
$User->setLogin($post['Login']);
$User->setSenha($post['Senha1']);


if ($User->getLogin() == null || $User->getLogin() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Login</div>';
    $Valid = false;
    exit();
}

if ($User->getSenha() == null || $User->getSenha() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Senha</div>';
    $Valid = false;
    exit();
}

if ($post['Senha2'] == null || $post['Senha2'] == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Senha</div>';
    $Valid = false;
    exit();
}

if ($post['Senha2'] != $User->getSenha()) {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Senhas não conferem</div>';
    $Valid = false;
    exit();
}

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
