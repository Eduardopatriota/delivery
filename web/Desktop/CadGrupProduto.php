<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $Id = "";
    $AbilitarBtExcluir = "disabled";
    $imagem = "";
    $Seq = "";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

    $GrupoProd = new GrupoProd();

    $Result = $GrupoProd->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome      = $Linhas['nome'];
        $Id        = $Linhas['id'];
        $imagem    = $Linhas['imagem'];
        $TipoGrupo = $Linhas['tipogrupo'];
        $Seq = $Linhas['seq'];
        $AbilitarBtExcluir = "";
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de grupo de produtos</h1>
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
                                <label>Sequencia</label>
                                <input class="form-control" name="Seq" id="Nome" value="<?php echo $Seq; ?>">
                            </div>

                            <div class="form-group">
                                <label>Tipo produto</label>
                                <select class="form-control" name="TipoGrupo">
                                    <?php
                                    $name_one = array("Cardapio", "Montagem");
                                    for ($i = 0; $i < count($name_one); $i++) {
                                        if ($name_one[$i] == $TipoGrupo) {
                                            echo '<option selected="selected" value="' . $name_one[$i] . '">' . $name_one[$i] . '</option>';
                                        } else {
                                            echo '<option value="' . $name_one[$i] . '">' . $name_one[$i] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="NomeFile" id="NomeFile" value="<?php echo $imagem; ?>">

                            <button type="submit" id="Salvar" class="btn btn-success" style="width: 100%;">Salvar</button>
                        </form>
                        <button style="width: 100%; margin-top: 0.5em;" id="Excluir" onclick="Excluir('php/control/GrupoProd/Excluir.php?Id=<?php echo $Id; ?>')" class="btn btn-danger" <?php echo $AbilitarBtExcluir; ?>>Excluir</button>
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
                    url: "php/control/GrupoProd/Insert.php",
                    data: dados,
                    beforeSend: function() {
                        $("#Result").html("Carregando...");
                    },
                    success: function(data) {
                        $("#Salvar").attr("disabled", true);
                        $("#Result").html(data);
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
            formdata.append("Nome", "Grupo" + document.getElementById("Nome").value);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "up/UploadGp.php");
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
            var formdata = new FormData();
            var file = _("imagem").files[0];
            formdata.append("imagem", file);

            $.ajax({
                url: "up/UploadGp.php",
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