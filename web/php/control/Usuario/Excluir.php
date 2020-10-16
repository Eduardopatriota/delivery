<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/user.php';

$Moto = new User();

if($Moto->Execute("Delete from user where id = ".$_GET['Id'])){
   echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>'; 
} else {
   
}