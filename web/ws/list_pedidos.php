<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
include_once '../php/model/bd/Persistence.php';
include_once '../php/model/pedido.php';

set_time_limit(1000);

$post = $_POST;

$Pedido = new Pedido();

$Result = $Pedido->Execute("Select pd.id, pd.Cliente, pd.Telefone, pd.TipoPagamento, pd.Data, pd.Obs, pd.Status, pd.Valor, mm.nome from pedido pd left join motoboy mm on mm.id = pd.motoboy where pd.Cliente = '" . $post["cliente"] . "' ORDER BY pd.data_bd DESC");

$Exit = "[";
$i = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    if ($Linhas['Status'] == 'Saiu para entrega') {
        $Itens = "";
        $j = 0;
        $Result2 = $Pedido->Execute("Select * from pedido_item pd where pd.Pedido = '" . $Linhas['id'] . "'");

        while ($Linhas2 = mysqli_fetch_assoc($Result2)) {
            $Itens = $Itens . '{"Produto":"' . $Linhas2['Produto'] . '", "Quantidade":"' . $Linhas2['Quantidade'] . '", "Valor":"' . $Linhas2['Valor'] . '"}';
            if ($j < mysqli_num_rows($Result2) - 1) {
                $Itens = $Itens . ',';
            }
            $j++;
        }

        $Exit = $Exit . '{"Data":"' . $Linhas['Data'] . '", "Valor":"' . $Linhas['Valor'] . '", "Status":"' . $Linhas['Status'] . ', MotoBoy: ' . $Linhas['nome'] . '", "Itens": ['.$Itens.']}';
    } else {

        $Itens = "";
        $j = 0;
        $Result2 = $Pedido->Execute("Select * from pedido_item pd where pd.Pedido = '" . $Linhas['id'] . "'");

        while ($Linhas2 = mysqli_fetch_assoc($Result2)) {
            $Itens = $Itens . '{"Produto":"' . $Linhas2['Produto'] . '", "Quantidade":"' . $Linhas2['Quantidade'] . '", "Valor":"' . $Linhas2['Valor'] . '"}';
            if ($j < mysqli_num_rows($Result2) - 1) {
                $Itens = $Itens . ',';
            }
            $j++;
        }

        $Exit = $Exit . '{"Data":"' . $Linhas['Data'] . '", "Valor":"' . $Linhas['Valor'] . '", "Status":"' . $Linhas['Status'] . '", "Itens": ['.$Itens.']}';
    }
    if ($i < mysqli_num_rows($Result) - 1) {
        $Exit = $Exit . ',';
    }
    $i++;
}


$Exit = $Exit . "]";

ob_clean();
echo $Exit;
