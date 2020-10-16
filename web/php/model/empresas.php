<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Empresas{
    private $id = null;
    private $Nome = null;
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
    }


    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome");
        $Values = array("'".$this->getNome()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("empresa", $Coluns, $Values);
        } else {
            return $Persistence->Update("empresa", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM empresa WHERE " . $Condition;
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