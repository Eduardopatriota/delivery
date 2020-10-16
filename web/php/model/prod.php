<?php
include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Prod{
    private $id = null;
    private $Nome = null;
    private $TipoProduto = null;
    private $preco = 0;
    private $obs = null;
    private $id_grupo = 0;
    private $imagem = '';
    private $Seq = '';
    private $Isdisp = '';
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function getPreco() {
        return $this->preco;
    }

    function getObs() {
        return $this->obs;
    }

    function getId_grupo() {
        return $this->id_grupo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
    }

    function setPreco($preco) {
        $this->preco = $preco;
    }

    function setObs($obs) {
        $this->obs = $obs;
    }

    function setId_grupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    function setTipoProduto($TipoProduto){
        $this->TipoProduto = $TipoProduto;        
    }

    function getTipoProduto(){
        return $this->TipoProduto;
    }

    function setImagem($Imagem){
        $this->imagem = $Imagem;        
    }

    function getImagem(){
        return $this->imagem;
    }

    function setSeq($Seq){
        $this->Seq = $Seq;        
    }

    function getSeq(){
        return $this->Seq;
    }

    function setIsdisp($Isdisp){
        $this->Isdisp = $Isdisp;        
    }

    function getIsdisp(){
        return $this->Isdisp;
    }

    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("nome", "preco", "obs", "id_grupo", "tipoproduto", "imagem", "seq", "isdisp");
        $Values = array("'".$this->getNome()."'", $this->getPreco(), "'".$this->getObs()."'", $this->getId_grupo(), 
                        "'".$this->TipoProduto."'", "'".$this->getImagem()."'" , "'".$this->getSeq()."'", "'".$this->getIsdisp()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("produto", $Coluns, $Values);
        } else {
            return $Persistence->Update("produto", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM produto WHERE " . $Condition;
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