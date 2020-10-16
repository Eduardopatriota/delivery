<?php

header("Content-Type: text/html; charset=UTF-8", true);
header('Access-Control-Allow-Origin: *');

set_time_limit(1000);
$post = $_POST;

$curl = curl_init();

$key = '';
$number = '55'.$post["numero"];
$code   = $post["codigo"];

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.smstoken.com.br/token/v1/verify?key=".$key."&number=".$number."&code=".$code."&expire=120",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "Cookie: PHPSESSID=brhqc5hjptddqqpn2rhe9ph69f"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

ob_clean();    
echo '{"Codigo":"1","Descricao":"SMS enviado!"}';