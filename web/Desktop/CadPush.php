<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}


$img = date('d') . date('m') . date('y') . date('H') . date('i') . date('s');

if (!isset($_GET['id'])) {
    $Nome = "";
    $Id = "";
    $AbilitarBtExcluir = "disabled";
} else {
    $get = $_GET;
    $id1 = $get['id'];

    include_once $_SESSION['_DIR_'] . '/php/model/motoboy.php';

    $Moto = new MotoBoy();

    $Result = $Moto->Select(' id = ' . $id1);

    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome = $Linhas['nome'];
        $Id = $Linhas['id'];
        $AbilitarBtExcluir = "";
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Enviar push</h1>
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
                                <label>Titulo</label>
                                <input class="form-control" name="Nome" value="<?php echo $Nome; ?>">
                            </div>  
                            <div class="form-group">
                                <label>Corpo</label>
                                <input class="form-control" name="Corpo" value="<?php echo $Nome; ?>">
                            </div> 
                            <div class="form-group">
                                <label>Foto (PNG)</label>
                                <input class="form-control" name="imagem" id="imagem" type="file">
                                <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
                            </div>
                            <input type="hidden" class="form-control" name="Img" id="Img" value="<?php echo $img; ?>">
                            <button type="submit" id="Salvar" class="btn btn-success">Enviar</button>
                        </form>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                        <div style="width: 100%; margin-top: 25px" id="Result2"></div>
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
                    url: "php/control/Notificacao/Enviar.php",
                    data: dados,
                    beforeSend: function () {
                        $("#Result").html("Carregando...");
                    },
                    success: function (data) {
                        $("#Result").html(data);
                        $("#Salvar").attr("disabled", true);
                        
                        uploadFile();
                    }
                });

                return false;
            });
        });

        function _(el) {
            return document.getElementById(el);
        }

        function uploadFile() {
            var file = _("imagem").files[0];
            //alert(file.name+" | "+file.size+" | "+file.type);
            var formdata = new FormData();
            formdata.append("imagem", file);
            formdata.append("Nome", <?php echo $img?>);
            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "php/control/Notificacao/UploadImg.php");
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