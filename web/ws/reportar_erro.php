<?php

header("Content-Type: text/html; charset=UTF-8", true);

ini_set('display_errors', 1);

$post  = $_POST;
$json  = json_decode(utf8_decode($post['JSon']), true);

$from = "mail@frango.es";

$to = "jarder7@gmail.com";

$subject = "Erro reportado";

$message = $json[0]["erro"];

$headers = "De:". $from;

mail($to, $subject, $message, $headers);

ob_clean();
echo '[{"Codigo":"100"}]';

?>