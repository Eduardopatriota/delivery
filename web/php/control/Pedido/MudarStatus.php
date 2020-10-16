<?php
date_default_timezone_set('America/Fortaleza');
if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

$Prod = new Pedido();

$post = $_POST;



if ($Prod->Execute("UPDATE pedido pp set pp.Status = '" . $post['Status'] . "', pp.motoboy = '" . $post['Motoboy'] . "', pp.visto = 1 WHERE  id=" . $post['Id'])) {
    echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';

    $Result = $Prod->Execute("Select * from user_app where id = " . preg_replace("/[^0-9]/", "", $post["Clie"]));

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $tokenUser = $Linhas['token_firebase'];
    }


    $content      = array(
        "en" => "Status do seu pedido mudou para: ". $post['Status']
    );
    $hashes_array = array();

    $fields = array(
        'app_id' => "7ff4416a-8351-43a5-a275-df7b3f9acddf",
        'include_player_ids' => array($tokenUser),
        'data' => array("foo" => "bar"),
        'contents' => $content
    );

    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic MmY0M2M5ZGYtOTNkMS00MWYyLTg0YjctYjYwZjI2MjAxY2Fj'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);

    //echo $response . ' - ' . $tokenUser;
} else {
}
