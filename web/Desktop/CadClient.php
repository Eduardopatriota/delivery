<?php
$id1;
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_GET['id'])) {
    $Nome = "";
    $Telefone = "";
    $Id = "";
    $Cpf = "";
    $Dt = "";
    $AbilitarBtExcluir = "disabled";
} else{
    $get = $_GET;
    $id1 = $get['id'];
    
    include_once $_SESSION['_DIR_'] . '/php/model/user.php';

    $User = new User();

    $Result = $User->Execute("SELECT * FROM user_app where id = ". $id1);
    
    while ($Linhas = mysqli_fetch_assoc($Result)) {
        $Nome = $Linhas['nome'];
        $Id = $Linhas['id'];
        $Telefone = $Linhas['telefone'];
        $Cpf = $Linhas['cpf'];
        $Dt = $Linhas['dt_nacimento'];
        $AbilitarBtExcluir = "";
    }
}
?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de Clientes</h1>
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
                                <label>Nome</label>
                                <input class="form-control" name="NOme" value="<?php echo $Nome; ?>">
                            </div>
                            <div class="form-group">
                                <label>Telefone</label>
                                <input class="form-control" name="Telefone" value="<?php echo $Telefone; ?>">
                            </div>
                            <div class="form-group">
                                <label>CPF</label>
                                <input class="form-control" name="Cpf" value="<?php echo $Cpf; ?>">
                            </div>
                            <div class="form-group">
                                <label>Data nascimento</label>
                                <input class="form-control" name="Dt" value="<?php echo $Dt; ?>">
                            </div>
                            <button type="submit" id="Salvar" class="btn btn-success">Salvar</button>
                            <button id="Excluir" onclick="Excluir('php/control/Usuario/Excluir.php?Id=<?php echo $Id; ?>')" class="btn btn-danger" <?php echo $AbilitarBtExcluir; ?>>Excluir</button>
                        </form>
                        <div style="width: 100%; margin-top: 25px" id="Result"></div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Endereços
                            </div>
                            <div class="panel-body">

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                        id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Endereço</th>
                                                <th>Numero</th>
                                                <th>Bairro</th>
                                                <th>Complemento</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/motagem.php';

                        $Motagem = new Motagem();

                        $Result = $Motagem->Execute('SELECT * FROM enderecos mm  
                         where mm.id_user = '.$Id);

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                            echo '<tr class="odd gradeX">
                                    <td>'.$Linhas['Nome'].'</td>
                                    <td>'.$Linhas['Rua'].'</td>                                    
                                    <td>'.$Linhas['Numero'].'</td>                                    
                                    <td>'.$Linhas['Bairro'].'</td>   
                                    <td>'.$Linhas['Complemento'].'</td>
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
        jQuery(document).ready(function () {
            jQuery('#Cadastro').submit(function () {
                var dados = jQuery(this).serialize();
         
        jQuery.ajax({
                    type: "POST",
                    url: "php/control/User_App/Insert.php",
                    data: dados,
                    beforeSend: function () {
                        $("#Result").html("Carregando...");
                    },
                    success: function (data) {
                        $("#Salvar").attr("disabled", true);
                        $("#Result").html(data);
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
                        $("#Excluir").attr("disabled", true);
                    }
                });
        }
    </script>


</div>