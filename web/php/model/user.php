<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class User{
    private $id = null;
    private $Nome = null;
    private $Valor = null;
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function getValor() {
        return $this->Valor;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
    }

    function setValor($Valor) {
        $this->Valor = $Valor;
    }


    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("Login", "Senha");
        $Values = array("'".$this->getNome()."'", $this->getValor());

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