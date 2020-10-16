<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_POST['nome'])) {
    $nome = '';
} else {
    $post   = $_POST;
    $nome   = $post['nome'];
}

?>

<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de motoboys</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <form style="float: left; margin-left: 0em; width: 100%" role="form" action="" method="post" id="Filtro">
                <div style="width: '100%'; flex-direction: 'row'; justify-content: 'space-between';">
                    <label>Buscar:</label>
                    <input name="nome" value="<?php echo $nome ?>">
                    <button type="submit" class="btn btn-success">BUSCaR</button>
                </div>
            </form>

            <div>
                <button onclick="Acessar('Desktop/CadMoto.php', 'CadMoto')" type="button" class="btn btn-success">Cadastrar</button>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>AçÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/motoboy.php';

                        $User = new MotoBoy();

                        $Result = $User->Select("nome like '%" . $nome . "%'");

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td>' . $Linhas['nome'] . '</td>                                    
                                    <td><a onclick="Acessar(\'Desktop/CadMoto.php?id=' . $Linhas['id'] . '\', \'CadMoto&id=' . $Linhas['id'] . '\')"><i class="fas fa-edit"></i> Editar</a></td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#Filtro').submit(function() {
                var dados = jQuery(this).serialize();
                jQuery.ajax({
                    type: "POST",
                    url: "Desktop/BuscMoto.php",
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

</div>