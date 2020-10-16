<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Bairros{
    private $id = null;
    private $Nome = null;
    private $Empresa = null;
    private $Valor = null;
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function getEmpresa() {
        return $this->Empresa;
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

    function setEmpresa($Empresa) {
        $this->Empresa = $Empresa;
    }

    function setValor($Valor) {
        $this->Valor = $Valor;
    }


    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome", "valor", "empresa");
        $Values = array("'".$this->getNome()."'", $this->getValor(), "'".$this->getEmpresa()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("taxaentrega", $Coluns, $Values);
        } else {
            return $Persistence->Update("taxaentrega", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM taxaentrega WHERE " . $Condition;
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