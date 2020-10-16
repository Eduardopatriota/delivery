<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/bd/Persistence.php';

class Config {

    private $Servico = null;

    function getServico() {
        return $this->Servico;
    }

    function setServico($Servico) {
        $this->Servico = $Servico;
    }

    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("servico_ativo");
        $Values = array("'" . $this->getServico() . "'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("config", $Coluns, $Values);
        } else {
            return $Persistence->Update("config", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM config WHERE " . $Condition;
        $Exit = $Persistece->Select($Sql);
        return $Exit;
    }

}
