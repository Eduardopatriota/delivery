<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Motagem{

    private $nome = null;
    private $produto = null;
    private $quantidade = null;
    private $grupo = null;
    private $obriga = null;
    private $tipo_preco = null;

    function setNome ($nome){
        $this->nome = $nome;
    }

    function getNome(){
        return $this->nome;
    }

    function setProduto ($produto){
        $this->produto = $produto;
    }

    function getProduto(){
        return $this->produto;
    }

    function setQuantidade ($quantidade){
        $this->quantidade = $quantidade;
    }

    function getQuantidade(){
        return $this->quantidade;
    }

    function setGrupo ($Grupo){
        $this->grupo = $Grupo;
    }

    function getGrupo(){
        return $this->grupo;
    }

    function setObriga ($Obriga){
        $this->obriga = $Obriga;
    }

    function getObriga(){
        return $this->obriga;
    }

    function setTipo_preco ($Tipo_preco){
        $this->tipo_preco = $Tipo_preco;
    }

    function getTipo_preco(){
        return $this->tipo_preco;
    }


    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome", "produto", "quatidade", "grupo", "obriga", "tipo_preco");
        $Values = array("'".$this->getNome()."'", $this->getProduto(), $this->getQuantidade(), $this->getGrupo(), 
        "'".$this->getObriga()."'", "'".$this->getTipo_preco()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("motagem", $Coluns, $Values);
        } else {
            return $Persistence->Update("motagem", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM motagem WHERE " . $Condition;
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

?>