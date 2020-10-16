<div class="container-fluid">

    <?php
    date_default_timezone_set('America/Manaus');
    if (!isset($_SESSION)) {
        session_start();
    }

    $_SESSION['_DT_HR_PD_'] = date('Y-m-d H:i:s');
    $_SESSION['_DT_SOM_'] = "N";

    if (!isset($_SESSION['_DT_DT1_PD_'])) {
        
    }

    if (!isset($_POST['data1']) && !isset($_POST['data2'])) {
        $data1                       = '01/01/1900';
        $data2                       = '01/01/2500';
        $status                      = 'Aberto';
        $Empresa                     = 0;


        $_SESSION['_DT_EMPRESA_PD_'] = !isset($_SESSION['_DT_EMPRESA_PD_']) ? 0 : $_SESSION['_DT_EMPRESA_PD_'];
        $_SESSION['_DT_DATA1_PD_']   = !isset($_SESSION['_DT_DATA1_PD_']) ? '01/01/1900' : $_SESSION['_DT_DATA1_PD_'];
        $_SESSION['_DT_DATA2_PD_']   = !isset($_SESSION['_DT_DATA2_PD_']) ? '01/01/1900' : $_SESSION['_DT_DATA2_PD_'];
        $_SESSION['_DT_STATUS_PD_']  = !isset($_SESSION['_DT_STATUS_PD_']) ? 'Aberto' : $_SESSION['_DT_STATUS_PD_'];
    } else {
        $post                        = $_POST;
        $data1                       = '01/01/1900';
        $data2                       = '01/01/1900';
        $status                      = $post['status'];
        $Empresa                     = $post['Empresa'];
        $_SESSION['_DT_EMPRESA_PD_'] = $post['Empresa'];
        $_SESSION['_DT_DATA1_PD_']   = $post['data1'];
        $_SESSION['_DT_DATA2_PD_']   = $post['data2'];
        $_SESSION['_DT_STATUS_PD_']  = $post['status'];
    }

    ?>

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Pedidos</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading" id="notf">
            <div style=" 
                        margin-top: -0.7em; 
                        margin-bottom:-0.7em; 
                        margin-left: -1em; 
                        padding-top: 0.7em;
                        padding-left: 2em;
                        height: 2.8em">Informações</div>
        </div>
        <div class="panel-body">

            <form style="float: left; margin-left: 1em; width: 100%" role="form" action="" method="post" id="Filtro">                
                
                <input class="form-control" name="data1" type="hidden" value="<?php echo $_SESSION['_DT_DATA1_PD_'] ?>">        

                <input class="form-control" name="data2" type="hidden"  value="<?php echo $_SESSION['_DT_DATA2_PD_'] ?>">
                
                <label style="float: left; margin: 0.5em;">STATUS:</label>

                <select class="form-control" name="status" style="float: left; width: 10em">
                    <option value="<?php echo $_SESSION['_DT_STATUS_PD_'];?>"><?php echo $_SESSION['_DT_STATUS_PD_'];?></option>
                    <option value="Aberto">Aberto</option>
                    <option value="Pedido aceito">Pedido aceito</option>
                    <option value="Em produção">Em produção</option>
                    <option value="Saiu para entrega">Saiu para entrega</option>
                    <option value="Aguardando retirada">Aguardando retirada</option>
                    <option value="Pedido recusado">Pedido recusado</option>
                    <option value="Pedido entregue">Pedido entregue</option>
                </select>


                <label style="float: left; margin: 0.5em; margin-left: 2em;">FILIAL :</label>
                <select class="form-control" name="Empresa" style="float: left; width: 10em">
                    <?php
                    echo $_SESSION['_DT_EMPRESA_PD_'];
                    if (!isset($_SESSION)) {
                        session_start();
                    }
                    include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

                    $GrupoProd = new GrupoProd();

                    $Result = $GrupoProd->Execute("Select * from empresa ");

                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                        if ($Linhas['id'] == $_SESSION['_DT_EMPRESA_PD_']) {
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
                            <th>TELEFONE</th>
                            <th>ENDEREÇO</th>
                            <th>TEMPO DE ESPERA (Hrs)</th>
                            <th>STATUS</th>
                            <th>VALOR</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';
                        include_once $_SESSION['_DIR_'] . '/php/model/pedido_item.php';

                        $Prod = new Pedido();
                        $Item = new Pedido_Item();

                        if ($_SESSION['_DT_STATUS_PD_'] == 'Aberto') {
                            $Cod = " and Status <> 'Pedido entregue' and Status <> 'Pedido resusado' and Status <> 'Pedido recusado'";
                        } else {
                            $Cod = " and Status = '" . $_SESSION['_DT_STATUS_PD_'] . "'";
                        }

                        $Result = $Prod->Execute("SELECT id, Cliente, Telefone, Endereco, TipoPagamento, Data, Obs, Status, Valor, motoboy, entrega, data_bd, hora, TIMEDIFF('".date('Y-m-d H:i:s')."', `pedido`.`data_bd`) AS Espera FROM `pedido` where Data > '" . str_replace('/', '.', $_SESSION['_DT_DATA1_PD_']) . "' " . $Cod . ' and empresa = ' . $_SESSION['_DT_EMPRESA_PD_']. ' ORDER BY `pedido`.`data_bd` DESC ');

                        while ($Linhas = mysqli_fetch_assoc($Result)) {

                            $Result2 = $Item->Select(" Pedido = '" . $Linhas['id'] . "'");
                            $Str = "";
                            while ($Linhas2 = mysqli_fetch_assoc($Result2)) {
                                $Str = $Str . '<div>------------------------------------------------</div>' .
                                    ' <div>Produto: ' . $Linhas2['Produto'] . ' -->  Qtd: ' . $Linhas2['Quantidade'] . ' Valor: R$' . $Linhas2['Valor'] . '</div>' .
                                    ' <div></div>' .
                                    '<div>Adicionais: ' . $Linhas2['Adcionais'] . '</div>' .
                                    '<div>Observação: ' . $Linhas2['Observacao'] . '</div>';
                            }

                            $cor = '#35bb00';
                            if ($Linhas['Status'] == 'Em produção') {
                                $cor = '#2b02f9';
                            } else if ($Linhas['Status'] == 'Saiu para entrega') {
                                $cor = '#02bec0';
                            } else if ($Linhas['Status'] == 'Aguardando retirada') {
                                $cor = '#ac00bb';
                            } else if ($Linhas['Status'] == 'Pedido recusado') {
                                $cor = '#f90202';
                            } else if ($Linhas['Status'] == 'Pedido entregue') {
                                $cor = '#000000';
                            }


                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td><a style="color: #000" target=”_blank” href="https://api.whatsapp.com/send?phone=55'. $Linhas['Telefone'] .'&data=AbvJe2XDlwgR_gYeZ6tt2N-Mh5uAwwMnX8TWOpYC3JDNvvFBjsRZESxXui6at63zzkcD2LkGU21SUWfqeEAD_Owv6YoJwui0vnA3HDoO4GyFo-7C-nyJR4TcjhiUwbRBCVE&source=FB_Ads&fbclid=IwAR3Iho4uch2202Ori14BDnOqmiftVzcUPCJ6zRd2cO5oXaqBARzLnoh0k5Q">' . $Linhas['Cliente'] . '</a></td>
                                    <td>' . $Linhas['Telefone'] . '</td>
                                    <td>' . $Linhas['Endereco'] . '</td>
                                    <td>' . $Linhas['Espera'] . '</td>
                                    <td><font color="' . $cor . '">' . $Linhas['Status'] . '</font></td>
                                    <td>' . 'R$ ' . $Linhas['Valor'] . '</td>
                                    <td>
                                        <a data-toggle="modal" data-target="#' . $Linhas['id'] . '"><i class="fas fa-edit"></i> Ver</a>                                    
                                    </td>
                                    <td>
                                        <a onclick="Acessar(\'Desktop/CadPedido.php?id=' . $Linhas['id'] . '\', \'CadPedido&id=' . $Linhas['id'] . '\')"></i> Editar Status</a>
                                    </td>
                                  </tr>
                                  
                                  <!-- Modal -->
                                    <div class="modal fade" id="' . $Linhas['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document"    style="width: 9cm;">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Visualização</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body' . $Linhas['id'] . '" id="modal-body' . $Linhas['id'] . '" >
                                            <div style="width: 7cm;" font-size: 16px"> 
                                                <div>------------------------------------------------</div>
                                                <div><h4>Sheik Burgs</h4></div>
                                                <div>Pedido Nº' . $Linhas['id'] . '</div>
                                                <div>Cliente: ' . $Linhas['Cliente'] . '</div>
                                                <div>Endereço: ' . $Linhas['Endereco'] . '<div>
                                                <i>Telefone: ' . $Linhas['Telefone'] . '</i>
                                                <div>Pagamento: ' . $Linhas['TipoPagamento'] . '<div>
                                                </br>
                                                <div>' . $Str . '</div>
                                                <div>------------------------------------------------</div>
                                                <div>Observação geral:' . $Linhas['Obs'] . '</div>
                                                <div>Total: R$ ' . $Linhas['Valor'] . '</div>
                                                <div>------------------------------------------------</div>                                                    
                                            </div>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" id="fx' . $Linhas['id'] . '" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="Im' . $Linhas['id'] . '" class="btn btn-primary" onclick="Imp' . $Linhas['id'] . '()">Imprimir</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <!-- Modal -->
                                    <div class="modal fade" id="st' . $Linhas['id'] . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Visualização</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body" id="modal-body" >
                                               <label style="float: left; margin: 0.5em; margin-left: 2em;">STATUS:</label>
                
                                                <select class="form-control"  name="status" style="float: left; width: 10em">
                                                    <option value="Aberto">Aberto</option>
                                                    <option value="Em produção">Em produção</option>
                                                    <option value="Saiu para entrega">Saiu para entrega</option>
                                                    <option value="Aguardando retirada">Aguardando retirada</option>
                                                    <option value="Pedido recusado">Pedido recusado</option>
                                                    <option value="Pedido entregue">Pedido entregue</option>
                                                </select>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button"  class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                            <button type="button"  class="btn btn-primary" onclick="Imp' . $Linhas['id'] . '()">Imprimir</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    
                                    <script type="text/javascript">
                                    
                                        function Imp' . $Linhas['id'] . '() {
                                           $("#fx' . $Linhas['id'] . '").hide();
                                           $("#Im' . $Linhas['id'] . '").hide();
                                           var valorDaDiv = $("#modal-body' . $Linhas['id'] . '").html();
                                           $("#page-wrapper").html(valorDaDiv); 
                                           window.print() ; 
                                           window.location.reload();                                           
                                        }

                                    </script>


                                  ';
                        }
                        ?>
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
                url: "Desktop/BuscPedido.php",
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

<script type="text/javascript">
    function atualiza() {
        $('#notf').load('php/control/Pedido/Notificacao.php');
    }

    setInterval(function() {
        atualiza($('#notf').text());
    }, 6000);
</script>
<script type="text/javascript">
    function carregar(pagina) {
        $("#Notificacoes2").load(pagina);
    }
</script>
<script type="text/javascript">
    function atz() {

        // $_SESSION['_DT_EMPRESA_PD_'] = $post['Empresa'];
        // $_SESSION['_DT_DATA1_PD_']   = $post['data1'];
        // $_SESSION['_DT_DATA2_PD_']   = $post['data2'];
        // $_SESSION['_DT_STATUS_PD_']  = $post['status'];

        jQuery.ajax({
            type: "POST",
            url: "Desktop/BuscPedido.php",
            data: {
                data1: <?php echo "'" . $_SESSION['_DT_DATA1_PD_'] . "'" ?>,
                data2: <?php echo "'" . $_SESSION['_DT_DATA2_PD_'] . "'" ?>,
                status: <?php echo "'" . $_SESSION['_DT_STATUS_PD_'] . "'" ?>,
                Empresa: <?php echo "'" . $_SESSION['_DT_EMPRESA_PD_'] . "'" ?>
            },
            beforeSend: function() {
                $("#page-wrapper").html("Carregando...");
            },
            success: function(data) {
                $("#page-wrapper").html(data);
            }
        });
        // window.location.reload();
    }
</script>

<audio id="audio">
    <source src="songs/alert.mp3" type="audio/mp3" />
</audio>

<script type="text/javascript">
    audio = document.getElementById('audio');

    function play() {
        audio.play();
    }
</script>