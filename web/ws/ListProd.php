<?php


header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';
include_once $_SESSION['_DIR_'] . '/php/model/motagem.php';

//nexmo.com biblioteca de envio de SMS
//Lembre de colocar o certificado na pasta extras do php e adcionar a linha no php.ini: curl.cainfo = "C:\xampp\php\extras\ssl\cacert.pem"    e depois reniciar o apache
set_time_limit(1000);

$post = $_POST;


$Persistece = new Persistece();

$Prod = new Prod();
$Montagem = new Motagem();

$Result = $Prod->Execute("select pp.id, pp.nome, pp.preco, pp.obs, pp.id_grupo, pp.tipoproduto, gp.nome AS grupo, pp.imagem from produto pp inner join grupoprod gp on gp.id = pp.id_grupo WHERE pp.id_grupo = " . $post["id_grupo"] . " and pp.isdisp = 'Sim' ORDER BY pp.seq ");

$Exit = "[";
$Mot = '';

$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Mot = '';
    $Mot2 = '';
    $j = 0;
    $k = 0;
    $Result2 = $Montagem->Select(" produto = " . $Linhas['id']);

    while ($Linhas2 = mysqli_fetch_assoc($Result2)) {
        $Mot = $Mot . '"' . $Linhas2['nome'] . '": []';
        if ($j < mysqli_num_rows($Result2) - 1) {
            $Mot = $Mot . ', ';
        }
        $j++;
    }

    $Result3 = $Montagem->Select(" produto = " . $Linhas['id']);

    while ($Linhas3 = mysqli_fetch_assoc($Result3)) {
        $Mot2 = $Mot2 . '"' . $Linhas3['nome'] . '"';
        if ($k < mysqli_num_rows($Result2) - 1) {
            $Mot2 = $Mot2 . ', ';
        }
        $k++;
    }

    if($Mot != ''){
        $Mot = ', '. $Mot;
    }

    $Exit = $Exit . '{"id":"' . $Linhas['id'] . '","nome":"' . $Linhas['nome'] . '","preco":"' . $Linhas['preco'] . '"'
        . ',"obs":"' . $Linhas['obs'] . '", "tipoproduto":"' . $Linhas['tipoproduto'] . '", "grupo":"' . $Linhas['grupo'] . '" '.$Mot.', "motagem":['.$Mot2.'], "imagem":"'.$Linhas['imagem'].'"}';

    if ($i < mysqli_num_rows($Result) - 1) {
        $Exit = $Exit . ',';
    }
    $i++;
}

$Exit = $Exit . "]";

ob_clean();
echo $Exit;
