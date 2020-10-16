<div class="container-fluid">

    <?php
    date_default_timezone_set('America/Manaus');
    
    $tot_venda   = 0.00;
    $tot_entrega = 0.00;
    $qtd         = 0;
    if (!isset($_POST['data1']) && !isset($_POST['data2'])) {
        $data1 = date('d/m/Y');
        $data2 = date('d/m/Y');
        $status = 'Pedido entregue';
        $bairro = 'Todos';
    } else {
        $post    = $_POST;
        $data1   = $post['data1'];
        $data2   = $post['data2'];
        $status  = $post['status'];
        $bairro  = $post['bairro'];
    }
    
    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Resumo de vendas</h1>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <form style="float: left; margin-left: 1em; width: 100%" role="form" action="" method="post" id="Filtro">
                <label style="float: left; margin: 0.5em; margin-left: -1em;">PERIODO:</label>
                <div style="float: left; width: 7em; margin-left: 1em">
                    <input class="form-control" name="data1" value="<?php echo $data1 ?>">
                </div>

                <label style="float: left; margin: 0.5em">a</label>

                <div style="float: left; width: 7em">
                    <input class="form-control" name="data2" value="<?php echo $data2 ?>">
                </div>

                <label style="float: left; margin: 0.5em; margin-left: 2em;">STATUS:</label>

                <select class="form-control" name="status" style="float: left; width: 10em">
                    <option value="Aberto">Aberto</option>
                    <option value="Em produção">Em produção</option>
                    <option value="Saiu para entrega">Saiu para entrega</option>
                    <option value="Aguardando retirada">Aguardando retirada</option>
                    <option value="Pedido recusado">Pedido recusado</option>
                    <option value="Pedido entregue">Pedido entregue</option>
                </select>

                <label style="float: left; margin: 0.5em; margin-left: 2em;">BAIRRO:</label>

                <select class="form-control" name="bairro" style="float: left; width: 10em">
                    <?php
                    if (!isset($_SESSION)) {
                        session_start();
                    }

                    include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

                    $GrupoProd = new Bairros();

                    $Result = $GrupoProd->Select(" 1 < 4");
                    echo '<option value="Todos">Todos</option>';
                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                        if ($Linhas['id'] == $bairro) {
                            echo '<option selected="selected" value="' . $Linhas['id'] . '">' . $Linhas['Nome'] . '</option>';
                        } else {
                            echo '<option value="' . $Linhas['id'] . '">' . $Linhas['Nome'] . '</option>';
                        }
                    }
                    ?>
                </select>

                <button style="float: left; margin-left: 2em;" type="submit" class="btn btn-success">BUSCaR</button>
            </form>

            <div class="table-responsive" style="float: left; width: 100%; margin-top:  1em">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>CLIENTE</th>
                            <th>TELEFONE</th>
                            <th>STATUS</th>
                            <th>VALOR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

                        $Prod = new Pedido();

                        if ($status == 'Aberto') {
                            $Cod = " and pd.Status <> 'Pedido entregue'";
                        } else {
                            $Cod = " and pd.Status = '" . $status . "'";
                        }

                        if ($bairro == 'Todos') {
                            $Cod = $Cod .  " ";
                        } else {
                            $Cod = $Cod . " and bairro = ". $bairro;
                        }

                        $dat2 = implode("-",array_reverse(explode("/",$data1)));
                        $dat3 = implode("-",array_reverse(explode("/",$data2)));

                        $dat2 = $dat2 . ' 00:00:00';
                        $dat3 = $dat3 . ' 23:59:59';
                        
                        //echo "select * from pedido pd where pd.data_bd BETWEEN '" . $dat2 . "' and '" . $dat3 . "' " . $Cod;

                        $Result = $Prod->Execute("select * from pedido pd where pd.data_bd BETWEEN '" . $dat2 . "' and '" . $dat3 . "' " . $Cod);

                        while ($Linhas = mysqli_fetch_assoc($Result)) {

                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td>' . $Linhas['Cliente'] . '</td>
                                    <td>' . $Linhas['Telefone'] . '</td>
                                    <td>' . $Linhas['Status'] . '</td>
                                    <td>' . 'R$ ' . $Linhas['Valor'] . '</td>  
                                  </tr>
                                  ';
                            $tot_entrega = $tot_entrega + $Linhas['entrega'];
                            $tot_venda   = $tot_venda + $Linhas['Valor'];
                            $qtd         = $qtd + 1;
                        }
                        ?>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="float: right;">
                    <thead>
                        <tr>
                            <th>Total de vendas: R$ <?php echo $tot_venda ?></th>
                            <th>Tiket medio: R$ <?php echo $tot_venda == 0 ? 0 : $tot_venda / $qtd  ?> </th>
                            <th>Quantidade: <?php echo $qtd   ?> </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#Filtro').submit(function() {
            var dados = jQuery(this).serialize();
            jQuery.ajax({
                type: "POST",
                url: "Desktop/BuscPedidoResulm.php",
                data: dados,
                beforeSend: function() {
                    $("#page-wrapper").html("Carregando...");
                },
                success: function(data) {
                    $("#page-wrapper").html(data);
                }
            });

            return false;
        });
    });
</script>