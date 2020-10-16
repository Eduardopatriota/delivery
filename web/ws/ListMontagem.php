<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once './vendor/autoload.php';
include_once $_SESSION['_DIR_'] . '/php/model/motagem.php';
include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

set_time_limit(1000);

$post = $_POST;

$Persistece = new Persistece();
$Pro = new Prod();

$Montagem = new Motagem();

$Result = $Montagem->Select(" produto = " . $post["produto"]);

$Exit = "[";
$Prod = '';
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {

    $Result2 = $Pro->Execute("select pp.id, pp.nome, pp.preco, pp.obs, pp.id_grupo, pp.tipoproduto, gp.nome AS grupo from produto pp inner join grupoprod gp on gp.id = pp.id_grupo WHERE pp.isdisp <> 'Nao' and pp.id_grupo = " . $Linhas['grupo']);
    $Prod = '';
    $j = 0;

    while ($Linhas2 = mysqli_fetch_assoc($Result2)) {
        $Prod = $Prod . '{"id":"' . $Linhas2['id'] . '","nome":"' . $Linhas2['nome'] . '","preco":"' . $Linhas2['preco'] . '"'
            . ',"obs":"' . $Linhas2['obs'] . '", 
            "tipoproduto":"' . $Linhas2['tipoproduto'] . '", "grupo":"' . $Linhas2['grupo'] . '", "obs":"' . $Linhas2['obs'] . '"}';

        if ($j < mysqli_num_rows($Result2) - 1) {
            $Prod = $Prod . ',';
        }
        $j++;
    }

    $Exit = $Exit . '{"quantidade":"' . $Linhas['quatidade'] . '", "nome":"' . $Linhas['nome'] . '", "grupo":"' . $Linhas['grupo'] . '", "obriga":"' . $Linhas['obriga'] . '", "itens": ['.$Prod.'], "tipoPreco":"'.$Linhas['tipo_preco'].'"}';

    if ($i < mysqli_num_rows($Result) - 1) {
        $Exit = $Exit . ',';
    }
    $i++;
}

$Exit = $Exit . "]";

ob_clean();
echo $Exit;
