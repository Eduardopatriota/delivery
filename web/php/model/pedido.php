<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';
date_default_timezone_set('America/Manaus');
class Pedido{
    private $id        = null;
    private $Cliente   = null;
    private $Telefone  = null;
    private $Endereco  = null;
    private $Data      = null;
    private $Obs       = null;
    private $Status    = null;
    private $Valor     = null;
    private $Pagamento = null;
    private $Motoboy   = null;
    private $Entrega   = null;
    private $Empresa   = null;
    private $Bairros   = null;
    
    function getId() {
        return $this->id;
    }

    function getCliente() {
        return $this->Cliente;
    }

    function getValor() {
        return $this->Valor;
    }

    function setValor($Valor) {
        $this->Valor = $Valor;
    }
        
    function getTelefone() {
        return $this->Telefone;
    }

    function getEndereco() {
        return $this->Endereco;
    }

    function getData() {
        return $this->Data;
    }

    function getObs() {
        return $this->Obs;
    }

    function getEmpresa() {
        return $this->Empresa;
    }

    function getStatus() {
        return $this->Status;
    }

    function getBairros() {
        return $this->Bairros;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCliente($Cliente) {
        $this->Cliente = $Cliente;
    }

    function setTelefone($Telefone) {
        $this->Telefone = $Telefone;
    }

    function setEndereco($Endereco) {
        $this->Endereco = $Endereco;
    }

    function setData($Data) {
        $this->Data = $Data;
    }

    function setObs($Obs) {
        $this->Obs = $Obs;
    }

    function setStatus($Status) {
        $this->Status = $Status;
    }
    
    function getPagamento() {
        return $this->Pagamento;
    }

    function setPagamento($Pagamento) {
        $this->Pagamento = $Pagamento;
    }

    function getMotoBoy() {
        return $this->Motoboy;
    }

    function setMotoBoy($MotoBoy) {
        $this->Motoboy = $MotoBoy;
    }

    function getEntrega() {
        return $this->Entrega;
    }

    function setEntrega($Entrega) {
        $this->Entrega = $Entrega;
    }

    function setEmpresa($Empresa) {
        $this->Empresa = $Empresa;
    }

    function setBairros($Bairros) {
        $this->Bairros = $Bairros;
    }
    

    
    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("id","Cliente", "Telefone", "Endereco", "Data", "Obs", "Status", "Valor", "TipoPagamento", "motoboy", "entrega", "data_bd", "empresa", "bairro", "hora");
        $Values = array("'".$this->getId()."'", "'".$this->getCliente()."'", "'" . $this->getTelefone() . "'", 
            "'" . $this->getEndereco() . "'"
            , "'" . date('d.m.Y') . "'", "'" . $this->getObs() . "'", "'" . $this->getStatus() . "'", $this->getValor(),
              "'" . $this->getPagamento() . "'", "'" . $this->getMotoBoy() . "'", $this->getEntrega(), "'" . date('Y-m-d H:i:s') . "'", $this->getEmpresa(), $this->getBairros(), "'" . date('Y-m-d H:i:s') . "'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("pedido", $Coluns, $Values);
        } else {
            return $Persistence->Update("pedido", $Coluns, $Values, $Condition);
        }
    }
    
    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM pedido WHERE " . $Condition;
 //       echo $Sql;
        $Exit = $Persistece->Select($Sql);
        return $Exit;
    }
    
    public function Execute($Sql1) {
        $Persistece = new Persistece();
        $Sql = $Sql1;
        $Exit = $Persistece->Execute($Sql);
        //echo $Sql;
        return $Exit;
    }
}