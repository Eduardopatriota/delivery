<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class Pedido_Item{
    
    private $Pedido;
    private $Produto;
    private $Quantidade;
    private $Valor;
    private $Adcionais;
    private $Observacao;
    
    function getPedido() {
        return $this->Pedido;
    }

    function getProduto() {
        return $this->Produto;
    }

    function getQuantidade() {
        return $this->Quantidade;
    }

    function getValor() {
        return $this->Valor;
    }

    function getAdcionais() {
        return $this->Adcionais;
    }

    function getObservacao() {
        return $this->Observacao;
    }

    function setPedido($Pedido) {
        $this->Pedido = $Pedido;
    }

    function setProduto($Produto) {
        $this->Produto = $Produto;
    }

    function setQuantidade($Quantidade) {
        $this->Quantidade = $Quantidade;
    }

    function setValor($Valor) {
        $this->Valor = $Valor;
    }

    function setAdcionais($Adcionais) {
        $this->Adcionais = $Adcionais;
    }

    function setObservacao($Observacao) {
        $this->Observacao = $Observacao;
    }
    
    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("Pedido", "Produto", "Quantidade", "Valor", "Adcionais", "Observacao");
        $Values = array("'".$this->getPedido()."'", "'" . $this->getProduto() . "'", $this->getQuantidade()
            , $this->getValor(), "'" . $this->getAdcionais() . "'", "'" . $this->getObservacao() . "'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("pedido_item", $Coluns, $Values);
        } else {
            return $Persistence->Update("pedido_item", $Coluns, $Values, $Condition);
        }
    }
    
    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM pedido_item WHERE " . $Condition;
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