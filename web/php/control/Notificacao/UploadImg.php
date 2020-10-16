<?php

if (!isset($_SESSION)) {
    session_start();
}
$post = $_POST;


if (!isset($_FILES['imagem']['tmp_name'])) {
    
} else {
    $destino = $_SESSION['_DIR_'] . '/imagensprod/' . $post['Nome'] . ".png";
    $arquivo_tmp = $_FILES['imagem']['tmp_name'];
    move_uploaded_file($arquivo_tmp, $destino);
}