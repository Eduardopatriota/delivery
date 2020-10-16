<?php
header('Access-Control-Allow-Origin: *');


include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';

include_once $_SESSION['_DIR_'] . '/php/model/Config.php';

$Config = new Config();

$Result = $Config->Select(" 1 = 1");
$Status = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Status = $Linhas['servico_ativo'];
}
if ($Status == 0) {
    ob_clean();
    echo '{"Codigo":"4","Descricao":"Servico desativado"}';
} else {
    ob_clean();
    echo '{"Codigo":"5","Descricao":"Servico em operação"}';
}






