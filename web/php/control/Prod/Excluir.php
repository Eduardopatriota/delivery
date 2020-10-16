<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

$Prod = new Prod();

if($Prod->Execute("Delete from produto where id = ".$_GET['Id'])){
   echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>'; 
} else {
   
}