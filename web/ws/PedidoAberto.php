<?php

header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

$Config = new Prod();
$post = $_POST;

$Result = $Config->Execute("select  COUNT(*) AS Qtd  from pedido pp where pp.visto = 1 and pp.Cliente = '".$post["Cliente"]."'");
$Status = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Status = $Linhas['Qtd'];
}
if ($Status == 0) {
    ob_clean();
    echo '{"Codigo":"0","Descricao":"Sem pedido"}';
} else {
    ob_clean();
    echo '{"Codigo":"1","Descricao":"Pedidos em processos"}';
}






