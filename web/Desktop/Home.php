<div class="container-fluid">

    <?php

    $ProdVend = 0;
    $Vendas   = 0;
    $Quant    = 0;

    if (!isset($_SESSION)) {
        session_start();
    }
    include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

    $Prod = new Pedido();

    $Result = $Prod->Execute("SELECT * FROM pedido pd inner join pedido_item pi on pd.id = pi.Pedido where pd.Status = 'Pedido entregue' ");

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $ProdVend = $ProdVend + 1;
    }

    $Result = $Prod->Execute("SELECT * FROM pedido pd where pd.Status = 'Pedido entregue' ");

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Vendas = $Vendas + $Linhas['Valor'];
        $Quant  = $Quant + 1;
    }


    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Inicio</h1>
        </div>
    </div>

    <div style="width: 100%; margin-bottom: 2em; display: flex; justify-content: space-between;">

        <div style="width: 15em; height: 8em; background-color: red;">
            <label style="width: 100%; text-align: center; color: white; font-size: 2em; margin-top: 0.5em;">
                R$ <?php echo round($Vendas, 2);  ?>
            </label>
            <label style="width: 100%; text-align: center; color: white; font-size: 1em;">Em vendas</label>
        </div>

        <div style="width: 15em; height: 8em; background-color: blue;">
            <label style="width: 100%; text-align: center; color: white; font-size: 2em; margin-top: 0.5em;">
                <?php echo round($Quant, 2);  ?>
            </label>
            <label style="width: 100%; text-align: center; color: white; font-size: 1em;">Vendas</label>
        </div>

        <div style="width: 15em; height: 8em; background-color: green;">
            <label style="width: 100%; text-align: center; color: white; font-size: 2em; margin-top: 0.5em;">
                R$ <?php echo $Vendas == 0 ? 0 : round($Vendas / $Quant, 2);  ?>
            </label>
            <label style="width: 100%; text-align: center; color: white; font-size: 1em;">Tiket médio</label>
        </div>

    </div>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Produtos mais vendidos</h1>
        </div>
    </div>

    <div class="table-responsive" style="float: left; width: 100%; margin-top:  1em">
        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
            <thead>
                <tr>
                    <th>PRODUTO</th>
                    <th>QUANTIDADE</th>
                    <!-- <th>VALOR</th> -->
                </tr>
            </thead>
            <tbody>
                <?php

                include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

                $Prod = new Pedido();

                $Result = $Prod->Execute("SELECT pi.Produto, SUM(pi.Quantidade) AS Quantidade, pi.Valor AS Valor FROM pedido pd inner join pedido_item pi on pd.id = pi.Pedido where pd.Status = 'Pedido entregue' group by pi.Produto ORDER BY SUM(pi.Quantidade)  DESC");

                while ($Linhas = mysqli_fetch_assoc($Result)) {


                    echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['Produto'] . '</td>
                                    <td>' . $Linhas['Quantidade'] . '</td>
                                                                     
                                  </tr>
                                  ';
                }

                ?>
            </tbody>
        </table>
    </div>


</div>