<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class User{
    private $id = null;
    private $Nome = null;
    private $Cpf = null;
	private $Telefone = null;
	
	function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
	}
	
	function getCpf() {
        return $this->Cpf;
	}
	
	function getTelefone() {
        return $this->Telefone;
    }
  
	function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
	}
	
	function setCpf($Cpf) {
        $this->Cpf = $Cpf;
    }

    function setTelefone($Telefone) {
        $this->Telefone = $Telefone;
    }

    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome", "telefone", "cpf");
        $Values = array("'".$this->getNome()."'", "'".$this->getTelefone()."'", "'".$this->getCpf()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("user_app", $Coluns, $Values);
        } else {
            return $Persistence->Update("user_app", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM user_app WHERE " . $Condition;
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