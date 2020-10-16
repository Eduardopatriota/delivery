<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Usuario{
    private $Id = null;
    private $Login = null;
    private $Senha = null;
    
    function getLogin() {
        return $this->Login;
    }

    function getSenha() {
        return $this->Senha;
    }

    function setLogin($Login) {
        $this->Login = $Login;
    }

    function setSenha($Senha) {
        $this->Senha = $Senha;
    }

    function setId($Id) {
        $this->Id = $Id;
    }

    function getId() {
        return $this->Id;
    }

    



    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("Login", "Senha");
        $Values = array("'".$this->getLogin()."'", "'" . $this->getSenha() . "'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("user", $Coluns, $Values);
        } else {
            return $Persistence->Update("user", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM user WHERE " . $Condition;
        $Exit = $Persistece->Select($Sql);
        return $Exit;
    }

    public function Execute($Sql1) {
        $Persistece = new Persistece();
        $Sql = $Sql1;
        $Exit = $Persistece->Select($Sql);
        return $Exit;
    }
}