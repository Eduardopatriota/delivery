<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/bd/Persistence.php';


$Valid = true;
$post = $_POST;


$Persistence = new Persistece();
$Coluns = array("Titulo", "Texto", "Imagem");
$Values = array("'" . $post['Nome'] . "'", "'" . $post['Corpo'] . "'", "'" . $post['Img'] . "'");

if ($Persistence->Insert("notificacoes", $Coluns, $Values)) {
    echo '<div class="alert alert-success">Tarefa realizada com <strong>sucesso!</strong></div>';


    $content      = array(
        "en" => $post['Corpo']
    );
    $hashes_array = array();
    
    $fields = array(
        'app_id' => "7ff4416a-8351-43a5-a275-df7b3f9acddf",
        'included_segments' => array(
            'All'
        ),
        'data' => array(
            "foo" => "bar"
        ),
        'contents' => $content,
        'web_buttons' => $hashes_array
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

} else {
    echo '';
}
