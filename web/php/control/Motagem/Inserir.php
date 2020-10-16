<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/motagem.php';

$Motagem = new Motagem();


$Valid = true;
$post = $_POST;


$Motagem->setNome($post['Nome']);
$Motagem->setQuantidade($post['Qtd']);
$Motagem->setGrupo($post['Id_Grupo']);
$Motagem->setProduto($post['Produto']);
$Motagem->setObriga($post['Obriga']);
$Motagem->setTipo_preco($post['Preco']);



if ($Motagem->getNome() == null || $Motagem->getNome() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Nome</div>';
    $Valid = false;
}

if ($Motagem->getQuantidade() == null || $Motagem->getQuantidade() == "") {
    echo '<div class="alert alert-danger"><strong>Atenção! </strong>Preencha o campo Quantidade</div>';
    $Valid = false;
}

if ($Valid) {
    
    if ($Motagem->InBase('Insert', '')) {
        echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';    
    } 
}

?>

