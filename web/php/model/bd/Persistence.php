<?php

if(!isset($_SESSION)){
    session_start();
}

include_once $_SESSION['_DIR_'].'/php/model/bd/ClassConnection.php';

//$Conexao = new ClassConnection;

class Persistece {

    public function Insert($NameTable, $Coluns, $Values) {
        $Sql1 = "INSERT INTO " . $NameTable . " (";
        $Sql = "";
        for ($i = 0; $i < count($Coluns); $i++) {
            $Sql1 = $Sql1 . $Coluns[$i];
            if ($i != count($Coluns) - 1) {
                $Sql1 = $Sql1 . ", ";
            }
        }

        $Sql1 = $Sql1 . ") ";
        $Sql = $Sql . "VALUES ";

        $Sql = $Sql . "(";

        for ($i = 0; $i < count($Values); $i++) {

            $Sql = $Sql . $this->Ver_Valor($Values[$i]);
            if ($i != count($Values) - 1) {
                $Sql = $Sql . ", ";
            }
        }

        $Sql = $Sql . ") ";
        $Sql1 = $Sql1 . $Sql;
        $Conexao = new ClassConnection();
        //echo $Sql1;
        $Exit = $Conexao->exeBD($Sql1);
        return $Exit;
    }

    public function Update($NameTable, $Coluns, $Values, $Condition) {
        $Sql = "UPDATE " . $NameTable . " SET ";

        $Temp = 0;

        for ($i = 0; $i < count($Coluns); $i++) {
            if ($this->Ver_Valor($Values[$i]) == 'NULL') {
                //Não faz nada
            } else {

                if ($Temp == 0) {
                    $Sql = $Sql . $Coluns[$i] . " = ";
                    $Sql = $Sql . $this->Ver_Valor($Values[$i]);
                    $Temp = 1;
                } else {
                    $Sql = $Sql . ", " . $Coluns[$i] . " = ";
                    $Sql = $Sql . $this->Ver_Valor($Values[$i]);
                }
            }
        }

        $Sql = $Sql . " WHERE " . $Condition;
        //echo $Sql;
        $Conexao = new ClassConnection();
        $Exit = $Conexao->exeBD($Sql);
        return $Exit;
    }

    public function Delete($Table, $Condition) {
        $Conexao = new ClassConnection();
        $Sql = "DELETE FROM " . $Table . " WHERE " . $Condition;
        $Exit = $Conexao->exeBD($Sql);
        return $Exit;
    }

    public function Select($Sql) {
        //echo $Sql;
        $Conexao = new ClassConnection();
        $Exit = $Conexao->select($Sql);
        return $Exit;
    }

    public function Execute($Sql) {
        //echo $Sql;
        $Conexao = new ClassConnection();
        $Exit = $Conexao->exeBD($Sql);
        return $Exit;
    }

    private function Ver_Valor($Valor) {
        //echo 'O valor e:'. $Valor;
        if ($Valor == "''" || $Valor == NULL) {
            return "NULL";
        } else {
            return $Valor;
        }
    }

    public function Tratar_Caractere($Valor) {
        return str_replace("'", ' ', $Valor);
    }

}
