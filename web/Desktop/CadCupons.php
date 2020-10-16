<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $DataF = date('d/m/Y');
    $Id = "";
    $Disponivel = "";
    $Percent = "";
    $AbilitarBtExcluir = "disabled";
} else{
    $get = $_GET;
    $id1 = $get['id'];
    
    include_once $_SESSION['_DIR_'] . '/php/model/user.php';

    $User = new User();

    $Result = $User->Execute("SELECT * FROM cupons where id = ". $id1);
    
    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome = $Linhas['Titulo'];
        $Id = $Linhas['id'];
        $DataF = date("d/m/Y", strtotime($Linhas['DataF']));
        $Disponivel = $Linhas['Disponivel'];
        $Percent = $Linhas['Percent'];
        $AbilitarBtExcluir = "";
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de Cupons</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form role="form" action="" method="post" id="Cadastro">
                            <div class="form-group">
                                <label>ID</label>
                                <div>
                                    <input class="form-control" readonly="readonly" name="Id" value="<?php echo $Id; ?>">
                                </div>                                    
                            </div>
                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control" name="Nome" value="<?php echo $Nome; ?>">
                            </div>
                            <div class="form-group">
                                <label>Data de validade</label>
                                <input class="form-control" name="DataF" value="<?php echo $DataF; ?>">
                            </div>
                            <div class="form-group">
                                <label>Disponivel</label>
                                <input class="form-control" name="Disponivel" value="<?php echo $Disponivel; ?>">
                            </div>
                            <div class="form-group">
                                <label>Percentual de desconto</label>
                                <input class="form-control" name="Percent" value="<?php echo $Percent; ?>">
                            </div>
                            <button type="submit" class="btn btn-success" style="width: 100%; margin-top: 0.5em;">Salvar</button>                            
                        </form>
                        <button style="width: 100%; margin-top: 0.5em;" onclick="Excluir('php/control/Cupons/Excluir.php?Id=<?php echo $Id; ?>')" class="btn btn-danger" <?php echo $AbilitarBtExcluir; ?>>Excluir</button>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                        
                    </div>    
                </div>
            </div>    


        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#Cadastro').submit(function () {
                var dados = jQuery(this).serialize();
         
        jQuery.ajax({
                    type: "POST",
                    url: "php/control/Cupons/Insert.php",
                    data: dados,
                    beforeSend: function () {
                        $("#Result").html("Carregando...");
                    },
                    success: function (data) {
                        $("#Result").html(data);
                    }
                });

                return false;
            });
        });
        
        function Excluir(Url) {
            var dados = jQuery(this).serialize();
                jQuery.ajax({
                    type: "POST",
                    url: Url,
                    data: dados,
                    beforeSend: function () {
                        $("#Result").html("Carregando...");
                    },
                    success: function (data) {
                        $("#Result").html(data);
                    }
                });
        }
    </script>


</div>