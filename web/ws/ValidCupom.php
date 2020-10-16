<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/cupon.php';

set_time_limit(1000);

$post = $_POST;

//$json = json_decode($post['JSon'], true);

$Persistece = new Persistece();

$Cupom = new Cupom();

$Result = $Cupom->Select(" Titulo = '".$post["titulo"]. "' and DataF >=  '". date('Y-m-d')."' and Disponivel > 0");
$Exit = "";
while ($Linhas = mysqli_fetch_assoc($Result)) {
    
    $Exit = $Exit.'{"percent":"'.$Linhas['Percent'].'", "Descricao":"Cupom valido"}';
    
}

if($Exit == ""){
    ob_clean();
    echo '{"Descricao":"Cupom invalido"}';
} else {
    ob_clean();
    echo $Exit;
}

