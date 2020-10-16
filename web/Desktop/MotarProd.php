<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $Id = "";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

    $Prod = new Prod();

    $Result = $Prod->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome = $Linhas['nome'];
        $Id = $Linhas['id'];
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Montagem de produtos</h1>
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
                        <form role="form" action="" method="post" enctype="multipart/form-data" id="Cadastro">
                            <div class="form-group">
                                <label>Produto</label>
                                <div>
                                    <input class="form-control" readonly="readonly" name="Id" value="<?php echo $Id . ' - ' . $Nome; ?>">
                                </div>
                            </div>


                            <div class="form-group">
                                <label>Nome</label>
                                <input class="form-control" name="Nome" id="Nome" value="">
                            </div>
                            <div class="form-group">
                                <label>Quantidade maxima</label>
                                <input class="form-control" name="Qtd" id="Qtd" value="">
                            </div>

                            <div class="form-group">
                                <label>Grupo</label>
                                <select class="form-control" name="Id_Grupo">
                                    <?php
                                    if (!isset($_SESSION)) {
                                        session_start();
                                    }
                                    include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

                                    $GrupoProd = new GrupoProd();

                                    $Result = $GrupoProd->Select(" tipogrupo = 'Montagem'");

                                    while ($Linhas = mysqli_fetch_assoc($Result)) {
                                        if ($Linhas['id'] == $Grupo) {
                                            echo '<option selected="selected" value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        } else {
                                            echo '<option value="' . $Linhas['id'] . '">' . $Linhas['nome'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>

                            </div>

                            <div class="form-group">
                                <label>Tipo de preço</label>
                                <select class="form-control" name="Preco">
                                    <option value="Normal">Normal</option>
                                    <option value="Maior">Maior</option>
                                    <option value="Divisão">Divisão</option>
                                </select>

                            </div>

                            <div class="form-group">
                                <label>Obrigatório?</label>
                                <select class="form-control" name="Obriga">
                                    <option value="Nao">Não</option>
                                    <option value="Sim">Sim</option>
                                </select>

                            </div>

                            <div class="form-group">
                                <input type="hidden" name="Produto" id="Produto" value="<?php echo $Id ?>">
                            </div>

                            <button type="submit" class="btn btn-success">Salvar</button>
                        </form>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                        <div style="width: 100%; margin-top: 25px" id="Result2"></div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Informações
                            </div>
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Qtd</th>
                                                <th>Grupo</th>
                                                <th>Obrigatório?</th>
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!isset($_SESSION)) {
                                                session_start();
                                            }
                                            include_once $_SESSION['_DIR_'] . '/php/model/motagem.php';

                                            $Motagem = new Motagem();

                                            $Result = $Motagem->Execute('SELECT mm.nome, mm.quatidade, gp.nome as grupo FROM motagem mm  
                        inner join grupoprod gp On mm.grupo = gp.id where mm.produto = ' . $Id);

                                            while ($Linhas = mysqli_fetch_assoc($Result)) {
                                                echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['nome'] . '</td>
                                    <td>' . $Linhas['quatidade'] . '</td>                                    
                                    <td>' . $Linhas['grupo'] . '</td>                                    
                                    <td></td>   
                                    <td><a onclick="Excluir(\'php/control/Motagem/Excluir.php?Id=' . $Id . '&Nome=' . $Linhas['nome'] . '\')"><i class="fas fa-trash"></i> Excluir</a></td>
                                  </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>


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
                    url: "php/control/Motagem/Inserir.php",
                    data: dados,
                    beforeSend: function() {
                        $("#Result").html("Carregando...");
                    },
                    success: function(data) {
                        $("#Result").html(data);
                        location.reload();
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
                    location.reload();
                }
            });
        }

        function _(el) {
            return document.getElementById(el);
        }

        function uploadFile() {
            var file = _("imagem").files[0];
            // alert(file.name+" | "+file.size+" | "+file.type);
            var formdata = new FormData();
            formdata.append("imagem", file);
            formdata.append("Nome", document.getElementById("Nome").value);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "php/control/Prod/UploadImg.php");
            ajax.send(formdata);
        }

        function progressHandler(event) {
            var percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
            _("Result2").innerHTML = Math.round(percent) + "% uploaded... please wait";
        }

        function completeHandler(event) {
            _("Result2").innerHTML = event.target.responseText;
            _("progressBar").value = 0;
        }

        function errorHandler(event) {
            _("Result2").innerHTML = "Upload Failed";
        }

        function abortHandler(event) {
            _("Result2").innerHTML = "Upload Aborted";
        }
    </script>


</div>