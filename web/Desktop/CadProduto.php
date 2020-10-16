<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $Id = "";
    $Obs = "";
    $Preco = "";
    $imagem = "";
    $Seq = "";
    $IsDisp = "Sim";
    $AbilitarBtExcluir = "disabled";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/prod.php';

    $Prod = new Prod();

    $Result = $Prod->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome = $Linhas['nome'];
        $Id = $Linhas['id'];
        $Preco = str_replace('.', ',', $Linhas['preco']);
        $Obs = $Linhas['obs'];
        $Grupo = $Linhas['id_grupo'];
        $AbilitarBtExcluir = "";
        $TipoProduto = $Linhas['tipoproduto'];
        $imagem = $Linhas['imagem'];
        $Seq = $Linhas['seq'];
        $IsDisp = $Linhas['isdisp'];
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de produtos</h1>
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
                                <label>ID</label>
                                <div>
                                    <input class="form-control" readonly="readonly" name="Id" value="<?php echo $Id; ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Foto (PNG)</label>
                                <input class="file" name="imagem" id="imagem" type="file">
                                <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
                            </div>
                            <div class="form-group">
                                <label>Descrição</label>
                                <input class="form-control" name="Nome" id="Nome" value="<?php echo $Nome; ?>">
                            </div>
                            <div class="form-group">
                                <label>Preço</label>
                                <input class="form-control" name="Preco" value="<?php echo $Preco; ?>">
                            </div>
                            <div class="form-group">
                                <label>Observação</label>
                                <textarea class="form-control" Name="Obs" rows="3"><?php echo $Obs; ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Sequencia</label>
                                <input class="form-control" name="Seq" id="Nome" value="<?php echo $Seq; ?>">
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

                                    $Result = $GrupoProd->Select(" tipogrupo = 'Cardapio'");

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
                                <label>Disponivel?</label>
                                <select class="form-control" name="IsDisp">
                                    <option <?php if ($IsDisp == 'Sim') { echo 'selected="selected"'; } else {echo "";} ?> value="Sim">Sim</option>
                                    <option <?php if ($IsDisp == 'Nao') { echo 'selected="selected"'; } else {echo "";} ?> value="Nao">Nao</option>                                        
                                </select>

                            </div>

                            <div class="form-group">
                                <input type="hidden" name="TipoProduto" id="TipoProduto" value="Cardapio">
                                <input type="hidden" name="NomeFile" id="NomeFile" value="<?php echo $imagem; ?>">
                            </div>

                            <button type="submit" id="Salvar" class="btn btn-success" style="width: 100%; margin-top: 0.5em;">Salvar</button>
                            <button style="width: 100%; margin-top: 0.5em;" onclick="Acessar('Desktop/MotarProd.php?id=<?php echo $Id; ?>', 'MotarProd&id=<?php echo $Id; ?>')" class="btn btn-alert" <?php echo $AbilitarBtExcluir; ?>>Montar produto</button>
                        </form>
                        <button style="width: 100%; margin-top: 0.5em;" id="Excluir" onclick="Excluir('php/control/Prod/Excluir.php?Id=<?php echo $Id; ?>')" class="btn btn-danger" <?php echo $AbilitarBtExcluir; ?>>Excluir</button>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                        <div style="width: 100%; margin-top: 25px" id="Result2"></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script type="text/javascript">
        $('.file').on('change', function() {

            // Pega o Nome do Arquivo
            var fileName = $('input[type="file"]')[0].files[0].name;

            // Coloca num campo Hidden com ID #file-name
            $('#NomeFile').val(fileName);

        });
        
        jQuery(document).ready(function() {
            jQuery('#Cadastro').submit(function() {
                var dados = jQuery(this).serialize();
                jQuery.ajax({
                    type: "POST",
                    url: "php/control/Prod/Insert.php",
                    data: dados,
                    beforeSend: function() {
                        $("#Result").html("Carregando...");
                    },
                    success: function(data) {
                        $("#Result").html(data);
                        $("#Salvar").attr("disabled", true);

                        uploadFile();
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
            ajax.open("POST", "up/UploadImg.php");
            ajax.send(formdata);
        }

        function progressHandler(event) {
            var percent = (event.loaded / event.total) * 100;
            _("progressBar").value = Math.round(percent);
            _("Result2").innerHTML = Math.round(percent) + "% uploaded... please wait";
        }

        function completeHandler(event) {

            //_("Result2").innerHTML = 'Erro';
            _("progressBar").value = 0;
        }

        function errorHandler(event) {
            var formdata = new FormData();
            var file = _("imagem").files[0];
            formdata.append("imagem", file);

            $.ajax({
                url: "up/UploadImg.php",
                type: "POST",
                data: formdata,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#Result2").html('Upload finalizado');
                },
                success: function(data) {
                    $("#Result2").html('Upload finalizado');
                }
            });
            //_("Result2").innerHTML = event.target;
        }

        function abortHandler(event) {
            _("Result2").innerHTML = "Upload Aborted";
        }
    </script>


</div>