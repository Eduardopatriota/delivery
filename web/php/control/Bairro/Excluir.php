<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

$Bairros = new Bairros();

if($Bairros->Execute("Delete from taxaentrega where id = ".$_GET['Id'])){
   echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>'; 
} else {
   
}