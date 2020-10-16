<?php
date_default_timezone_set('America/Fortaleza');
if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

$Prod = new Pedido();
$data1                       = date('d/m/Y');

$Result = $Prod->Select(" data_bd > '".$_SESSION['_DT_HR_PD_']."' and empresa = ". $_SESSION['_DT_EMPRESA_PD_']);

$Temp = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Temp = 1;
}

if ($Temp == 1){

    echo '<div style="background:  #8AFF00;
            margin-top: -2em; 
            margin-bottom:-0.7em; 
            margin-left: -1em; 
            padding-top: 0.7em;
            padding-left: 2em;
            height: 2.8em">Pedidos a serem processados! <a onclick="return atz();">Atualize aqui</a></div></div>

            
        ';
    if($_SESSION['_DT_SOM_'] == "N")        {
        echo '<script> play();</script>';
        $_SESSION['_DT_SOM_'] = "N";
    }

} else {
    echo '<div style=" 
                margin-top: -2em; 
                margin-bottom:-0.7em; 
                margin-left: -1em; 
                padding-top: 0.7em;
                padding-left: 2em;
                height: 2.8em">Informações</div></div>';
}




?>