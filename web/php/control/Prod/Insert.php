<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

$Prod = new Prod();

$Valid = true;
$post = $_POST;


$Prod->setId($post['Id']);
$Prod->setNome($post['Nome']);
$Prod->setPreco(str_replace ( "," , "." , $post['Preco']));
$Prod->setObs($post['Obs']);
$Prod->setId_grupo($post['Id_Grupo']);
$Prod->setTipoProduto($post['TipoProduto']);
$Prod->setImagem($post['NomeFile']);
$Prod->setSeq($post['Seq']);
$Prod->setIsdisp($post['IsDisp']);



if ($Prod->getNome() == null || $Prod->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
    exit();
}

if ($Prod->getObs() == null || $Prod->getObs() == "") {
    $Prod->setObs(" ");
}

if ($Prod->getPreco() == null || $Prod->getPreco() == "") {
    if($post['TipoProduto'] != 'Montagem'){
        echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Preço</div>';
        $Valid = false;
        exit();
    } else {
        $Prod->setPreco('0.00'); 
    }
}

if ($Valid) {
    if ($Prod->getId() == null || $Prod->getId() == "") {
        if ($Prod->InBase('Insert', '')) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    } else{
        if ($Prod->InBase('Update', ' id='.$Prod->getId())) {
            
            echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';
        }
    }
}