<?php
//header('Content-Type: text/html; charset=utf-8');
class ClassConnection {

    var $host = "localhost";
    var $user = "jarder00_jarder";
    var $senha = "jarder12";
    var $dbase = "jarder00_delivery";

    

    var $query;
    var $link;
    var $resultado;


    function MySQL(){

    }

    function conecta(){

        $this->link = @mysqli_connect($this->host,$this->user,$this->senha, $this->dbase);

        if(!$this->link){
            // Caso ocorra um erro, exibe uma mensagem com o erro
            print "Ocorreu um Erro na conexão MySQL:";
            print "<b>".mysqli_error()."</b>";
            die();
        }
        mysqli_query($this->link, "SET NAMES 'utf8'");
        mysqli_query($this->link, 'SET character_set_connection=utf8');
        mysqli_query($this->link, 'SET character_set_client=utf8');
        mysqli_query($this->link, 'SET character_set_results=utf8');
    }

    function exeBD($query) {
        $this->conecta();
        $this->query = $query;

        if($this->resultado = mysqli_query($this->link, $this->query)){
            $this->desconnecta();
            return $this->resultado;
        }else{

            print "Ocorreu um erro ao executar a Query MySQL: <b>$query</b>";
            print "<br /><br />";
            print "Erro no MySQL: <b>".mysqli_error($this->link)."</b>";
            die();
            $this->desconnecta();
        }
    }

    function select($query) {
        $this->conecta();
        $this->query = $query;

        if($this->resultado = mysqli_query($this->link, $this->query)){
            $this->desconnecta();
            return $this->resultado;
        }else{

            print "Ocorreu um erro ao executar a Query MySQL: <b>$query</b>";
            print "<br /><br />";
            print "Erro no MySQL: <b>".mysqli_error()."</b>";
            die();
            $this->desconnecta();
        }
    }


    function desconnecta(){
        return mysqli_close($this->link);
    }

}
