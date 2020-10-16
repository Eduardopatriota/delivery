<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

$Prod = new Prod();

if($Prod->Execute("Delete from motagem where produto = ".$_GET['Id']." and nome = '".$_GET['Nome']."'")){
   echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>'; 
} else {
   
}