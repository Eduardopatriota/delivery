<?php

$post = $_POST;

if (!isset($_FILES['imagem']['tmp_name'])) {
    
} else {
    $destino =  'gp/'.$_FILES['imagem']['name'];
    $arquivo_tmp = $_FILES['imagem']['tmp_name'];
    move_uploaded_file($arquivo_tmp, $destino);
}