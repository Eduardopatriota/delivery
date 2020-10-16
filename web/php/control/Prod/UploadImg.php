<?php

if (!isset($_SESSION)) {
    session_start();
}

$post = $_POST;

var_dump($_FILES['imagem']['tmp_name']);
if (!isset($_FILES['imagem']['tmp_name'])) {
    
} else {
    
    $destino = $_SESSION['_DIR_'] . '/imagensprod/' . $_FILES['imagem']['name'];
    $arquivo_tmp = $_FILES['imagem']['tmp_name'];
    echo 'sdfsdf';
    
    move_uploaded_file($arquivo_tmp, $destino);
}