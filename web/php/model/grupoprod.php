<?php
include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class GrupoProd{
    private $id = null;
    private $Nome = null;
    private $Ativo = null;
    private $Imagem = null;
    private $TipoGrupo = null;
    private $Seq = '';
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function getAtivo() {
        return $this->Ativo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
    }

    function setAtivo($Ativo) {
        $this->Ativo = $Ativo;
    }

    function setImagem ($Imagem){
        $this->TipoGrupo = $Imagem;
    }

    function getImagem (){
        return $this->TipoGrupo;
    }

    function setTipoGrupo ($TipoGrupo){
        $this->Imagem = $TipoGrupo;
    }

    function getTipoGrupo (){
        return $this->Imagem;
    }

    function setSeq($Seq){
        $this->Seq = $Seq;        
    }

    function getSeq(){
        return $this->Seq;
    }

    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome", "ativo", "tipogrupo", "imagem", "seq");
        $Values = array("'".$this->getNome()."'", "'" . $this->getAtivo() . "'", "'".$this->getTipoGrupo()."'", "'".$this->getImagem()."'", "'".$this->getSeq()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("grupoprod", $Coluns, $Values);
        } else {
            return $Persistence->Update("grupoprod", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM grupoprod WHERE " . $Condition;
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