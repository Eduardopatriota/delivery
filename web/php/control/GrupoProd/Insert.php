<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

$GrupoProd = new GrupoProd();

$Valid = true;
$post = $_POST;


$GrupoProd->setId($post['Id']);
$GrupoProd->setNome($post['Nome']);
$GrupoProd->setTipoGrupo($post['TipoGrupo']);
$GrupoProd->setImagem($post['NomeFile']);
$GrupoProd->setSeq($post['Seq']);
$GrupoProd->setAtivo(1);


if ($GrupoProd->getNome() == null || $GrupoProd->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
}

if ($Valid) {
    if ($GrupoProd->getId() == null || $GrupoProd->getId() == "") {
        if ($GrupoProd->InBase('Insert', '')) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else{
        if ($GrupoProd->InBase('Update', ' id='.$GrupoProd->getId())) {
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}
