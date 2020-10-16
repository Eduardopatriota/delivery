<?php

include_once $_SESSION['_DIR_'] . '/php/model/bd/Persistence.php';

class Cupom {

    private $id = null;
    private $Titulo = null;
    private $DataF = null;
    private $Disponivel = 0;
    private $Percent = 0;

    function getId() {
        return $this->id;
    }

    function getTitulo() {
        return $this->Titulo;
    }

    function getDataF() {
        return $this->DataF;
    }

    function getDisponivel() {
        return $this->Disponivel;
    }

    function getPercent() {
        return $this->Percent;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setTitulo($Titulo) {
        $this->Titulo = $Titulo;
    }

    function setDataF($DataF) {
        $this->DataF = $DataF;
    }

    function setDisponivel($Disponivel) {
        $this->Disponivel = $Disponivel;
    }

    function setPercent($Percent) {
        $this->Percent = $Percent;
    }

        
    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("Titulo", "DataF", "Disponivel", "Percent");
        $Values = array("'" . $this->getTitulo() . "'", "'" . $this->getDataF() . "'", $this->getDisponivel(), 
            $this->getPercent());

        if ($Operacao == "Insert") {
            return $Persistence->Insert("cupons", $Coluns, $Values);
        } else {
            return $Persistence->Update("cupons", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM cupons WHERE " . $Condition;
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
