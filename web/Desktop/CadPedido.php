<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Cliente = "";
    $Id = "";
    $Moto = "";
    $AbilitarBtExcluir = "disabled";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

    $Prod = new Pedido();

    $Result = $Prod->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Cliente = $Linhas['Cliente'];
        $Id = $Linhas['id'];
        $Moto = $Linhas['motoboy'];
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pedido</h1>
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
                                <label>Cliente</label>
                                <input class="form-control" readonly="readonly" name="Nome" value="<?php echo $Cliente; ?>">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control"  name="Status">
                                    <option value="Pedido aceito">Pedido aceito</option>
                                    <option value="Em produção">Em produção</option>
                                    <option value="Saiu para entrega">Saiu para entrega</option>
                                    <option value="Aguardando retirada">Aguardando retirada</option>
                                    <option value="Pedido recusado">Pedido recusado</option>
                                    <option value="Pedido entregue">Pedido entregue</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>MotoBoy</label>
                                <select class="form-control"  name="Motoboy">
                                <?php
                                    if (!isset($_SESSION)) {
                                        session_start();
                                    }
                                    include_once $_SESSION['_DIR_'] . '/php/model/motoboy.php';

                                    $GrupoProd = new MotoBoy();

                                    $Result = $GrupoProd->Select(" 1 < 4");
                                    echo '<option value=""></option>';
                                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                                        if ($Linhas['id'] == $Moto) {
                                            echo '<option selected="selected" value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <input type="hidden" name="Clie" value="<?php echo $Cliente; ?>" />

                            <button type="submit" class="btn btn-success">Salvar</button>
                        </form>
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
                    url: "php/control/Pedido/MudarStatus.php",
                    data: dados,
                    beforeSend: function () {
                        $("#Result").html("Carregando...");
                    },
                    success: function (data) {
                        Acessar('Desktop/BuscPedido.php', 'BuscPedido');
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