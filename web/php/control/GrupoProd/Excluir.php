<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

$GrupoProd = new GrupoProd();

echo "Delete from grupoprod where id = ".$_GET['Id'];

if($GrupoProd->Execute("Delete from grupoprod where id = ".$_GET['Id'])){
   echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>'; 
} else {
   
} 