<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include_once 'Inicializar.php';
//include_once '../php/model/bd/Persistence.php';
include_once '../php/model/EnderecoUser.php';
include_once '../php/model/pedido.php';
include_once '../php/model/pedido_item.php';


set_time_limit(1000);

$post = $_POST;
$json = json_decode($post['JSon'], true);
$json2 = json_decode($post['JSon2'], true);

//echo $post['JSon2'];
date_default_timezone_set('America/Manaus');

$id = preg_replace("/[^0-9]/", "", $json[0]["cliente"]) . date('d') . date('i') . date('s');

$Pedido = new Pedido();
$Item = new Pedido_Item();


$Result = $Pedido->Execute(" Select * from taxaentrega where id = " . preg_replace("/[^0-9]/", "", $json[0]["bairro"]));
$Bairro = '1';
while ($Linhas = mysqli_fetch_assoc($Result)) {
  $Bairro = $Linhas['empresa'];
}

$Pedido->setId($id);
$Pedido->setCliente($json[0]["cliente"]);
$Pedido->setData(date('d.m.Y'));
$Pedido->setEndereco($json[0]["endereco"]);
$Pedido->setObs($json[0]["obs"]);
$Pedido->setTelefone($json[0]["telefone"]);
$Pedido->setStatus("Aguardando aprovação");
$Pedido->setValor($json[0]["valor"]);
$Pedido->setPagamento($json[0]["pagamento"]);
$Pedido->setEntrega($json[0]["entrega"]);
$Pedido->setEmpresa($Bairro);
$Pedido->setBairros(preg_replace("/[^0-9]/", "", $json[0]["bairro"]));


if ($Pedido->InBase("Insert", "")) {
    $Valid = false;
    $Pedido->Execute("Update cupons cp set cp.Disponivel = cp.Disponivel - 1 where cp.Titulo = '".$json[0]["cupom"]."'");
    
    for ($i = 0; $i < count($json2); $i++) {
        $Item->setPedido($id);
        $Item->setProduto($json2[$i]["produto"]);
        $Item->setQuantidade($json2[$i]["quantidade"]);
        $Item->setValor($json2[$i]["valor"]);
        $Item->setObservacao($json2[$i]["observacao"]);
        $Item->setAdcionais($json2[$i]["adcionais"]);        
        if ($Item->InBase("Insert", "")) {
          $Valid = true;  
        } 
    }

    if ($json[0]["aplicFidelidade"] == true) {

      $Pedido->Execute("Update pedido cp set cp.fidel = 0 where cp.Cliente = '".$json[0]["cliente"]."'");

    }

    if ($Valid) {
	  ob_clean();
      echo '{"Codigo":"100", "Bairro": "'. preg_replace("/[^0-9]/", "", $json[0]["bairro"]).'"}';      
    } else {
      ob_clean();
      echo 'ERRO iten';
    }
} else {
   ob_clean();
   echo 'ERRO';
}