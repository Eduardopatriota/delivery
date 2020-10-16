<div class="container-fluid">

    <?php
    date_default_timezone_set('America/Manaus');
        $tot_venda   = 0.00;
        $tot_entrega = 0.00;
        if (!isset($_POST['data1']) && !isset($_POST['data2'])) {
            $data1 = date('d/m/Y');
            $data2 = date('d/m/Y');
            $status = 'Aberto';
            $motoboy = 'Todos';
        } else {
            $post    = $_POST;
            $data1   = $post['data1'];
            $data2   = $post['data2'];
            $status  = $post['status'];
            $motoboy = $post['motoboy'];
        }
    
    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pedidos por motoboy</h1>
            
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

                <label style="float: left; margin: 0.5em; margin-left: 2em;">MOTOBOY:</label>

                <select class="form-control" name="motoboy" style="float: left; width: 10em">
                    <?php
                                    if (!isset($_SESSION)) {
                                        session_start();
                                    }
                                    include_once $_SESSION['_DIR_'] . '/php/model/motoboy.php';

                                    $GrupoProd = new MotoBoy();

                                    $Result = $GrupoProd->Select(" 1 < 4");
                                    echo '<option value="Todos">Todos</option>';
                                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                                        if ($Linhas['id'] == $motoboy) {
                                            echo '<option selected="selected" value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
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
                            <th>MOTOBOY</th>
                            <th>TELEFONE</th>
                            <th>STATUS</th>
                            <th>VALOR</th>
                            <th>VLR ENTREGA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

                        $Prod = new Pedido();
                        
                        if($status == 'Aberto'){
                            $Cod = " and pd.Status <> 'Pedido entregue'";
                        } else{ 
                            $Cod = " and pd.Status = '".$status."'";
                        }

                        if($motoboy == 'Todos'){
                            $Cod = $Cod .  " ";
                        } else{ 
                            $Cod = $Cod." and pd.motoboy = '".$motoboy."'";
                        }



                        $Result = $Prod->Execute("select * from pedido pd inner join motoboy mm on mm.id = pd.motoboy where pd.Data BETWEEN '".str_replace('/', '.', $data1)."' and '".str_replace('/', '.', $data2)."' ".$Cod);

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                                                        
                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td>' . $Linhas['Cliente'] . '</td>
                                    <td>' . $Linhas['nome'] . '</td>
                                    <td>' . $Linhas['Telefone'] . '</td>
                                    <td>' . $Linhas['Status'] . '</td>
                                    <td>' . 'R$ ' . $Linhas['Valor'] . '</td>                                    
                                    <td>' . 'R$ ' . $Linhas['entrega'] . '</td>
                                  </tr>
                                  ';
                                  $tot_entrega = $tot_entrega + $Linhas['entrega'];
                                  $tot_venda   = $tot_venda + $Linhas['Valor'];
                        }
                        ?>
                    </tbody>
                </table>
                <table class="table table-striped table-bordered table-hover" id="dataTables-example" style="float: right;">
                    <thead>
                        <tr>                            
                            <th>Total de vendas: R$ <?php echo $tot_venda?></th>
                            <th>Total taxas de entregas: R$ <?php echo $tot_entrega?> </th>
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
            url: "Desktop/BuscPedidoMotoy.php",
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