<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $Valor = "";
    $Id = "";
    $Empresa = '';
    $AbilitarBtExcluir = "disabled";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

    $Bairros = new Bairros();

    $Result = $Bairros->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome               = $Linhas['Nome'];
        $Id                 = $Linhas['id'];
        $Valor              = $Linhas['Valor'];
        $Empresa            = $Linhas['empresa'];
        $AbilitarBtExcluir  = "";
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de bairros / taxas de entregas</h1>
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
                                <label>Descrição</label>
                                <input class="form-control" name="Nome" value="<?php echo $Nome; ?>">
                            </div>
                            <div class="form-group">
                                <label>Valor</label>
                                <input class="form-control" name="Valor" value="<?php echo $Valor; ?>">
                            </div>
                            <div class="form-group">
                                <label>Empresa responsável</label>
                                <select class="form-control" name="Empresa">
                                    <?php
                                    echo $Empresa;
                                    if (!isset($_SESSION)) {
                                        session_start();
                                    }
                                    include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

                                    $GrupoProd = new GrupoProd();

                                    $Result = $GrupoProd->Execute("Select * from empresa ");

                                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                                        if ($Linhas['id'] == $Empresa) {
                                            echo '<option selected="selected" value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" id="Salvar" class="btn btn-success" style="width: 100%;">Salvar</button>                            
                        </form>
                        <button style="width: 100%; margin-top: 0.5em;" id="Excluir" onclick="Excluir('php/control/Bairro/Excluir.php?Id=<?php echo $Id; ?>')" class="btn btn-danger" <?php echo $AbilitarBtExcluir; ?>>Excluir</button>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#Cadastro').submit(function() {
                var dados = jQuery(this).serialize();

                jQuery.ajax({
                    type: "POST",
                    url: "php/control/Bairro/Inseir.php",
                    data: dados,
                    beforeSend: function() {
                        $("#Result").html("Carregando...");
                    },
                    success: function(data) {
                        $("#Result").html(data);
                        $("#Salvar").attr("disabled", true);
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
                beforeSend: function() {
                    $("#Result").html("Carregando...");
                },
                success: function(data) {
                    $("#Result").html(data);
                    $("#Excluir").attr("disabled", true);
                }
            });
        }
    </script>


</div>