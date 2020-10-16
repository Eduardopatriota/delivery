<?php

include_once $_SESSION['_DIR_'].'/php/model/bd/Persistence.php';

class EnderecoUser{
    private $id;
    private $Nome;
    private $Rua;
    private $Numero;
    private $Id_Bairro;
    private $Bairro;
    private $Complemento;
    private $Cep;
    private $idUser;
    
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->Nome;
    }

    function getId_Bairro() {
        return $this->Id_Bairro;
    }

    function setId_Bairro($Id_Bairro) {
        $this->Id_Bairro = $Id_Bairro;
    }
    
    function getRua() {
        return $this->Rua;
    }

    function getNumero() {
        return $this->Numero;
    }

    function getBairro() {
        return $this->Bairro;
    }

    function getComplemento() {
        return $this->Complemento;
    }

    function getCep() {
        return $this->Cep;
    }

    function getIdUser() {
        return $this->idUser;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($Nome) {
        $this->Nome = $Nome;
    }

    function setRua($Rua) {
        $this->Rua = $Rua;
    }

    function setNumero($Numero) {
        $this->Numero = $Numero;
    }

    function setBairro($Bairro) {
        $this->Bairro = $Bairro;
    }

    function setComplemento($Complemento) {
        $this->Complemento = $Complemento;
    }

    function setCep($Cep) {
        $this->Cep = $Cep;
    }

    function setIdUser($idUser) {
        $this->idUser = $idUser;
    }


    public function InBase($Operacao, $Condition) {
        $Persistence = new Persistece();
        $Coluns = array("Nome", "Rua", "Numero", "id_bairro","Bairro", "Complemento", "Cep", "id_user");
        $Values = array("'".$this->getNome()."'", "'" . $this->getRua() . "'", 
            "'".$this->getNumero()."'", "'" . $this->getId_Bairro() . "'", "'" . $this->getBairro() . "'",
            "'".$this->getComplemento()."'", "'" . $this->getCep() . "'",
            "'".$this->getIdUser()."'");

        if ($Operacao == "Insert") {
            return $Persistence->Insert("enderecos", $Coluns, $Values);
        } else {
            return $Persistence->Update("enderecos", $Coluns, $Values, $Condition);
        }
    }

    public function Select($Condition) {
        $Persistece = new Persistece();
        $Sql = "SELECT * FROM enderecos WHERE " . $Condition;
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