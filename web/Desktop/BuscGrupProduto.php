<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_POST['nome'])) {
    $nome = '';
    $tipo = '';
} else {
    $post   = $_POST;
    $nome   = $post['nome'];
    $tipo   = $post['tipo'];
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

            <form style="float: left; margin-left: 0em; width: 100%" role="form" action="" method="post" id="Filtro">
                <div style="width: '100%'; flex-direction: 'row'; justify-content: 'space-between';">
                    <label style="margin-left: 1em;">Buscar:</label>
                    <input name="nome" value="<?php echo $nome ?>">

                    <label style="float: left; margin: 0.5em; margin-bottom: 2em;">Mostar apenas:</label>

                    <select class="form-control" name="tipo" style="float: left; width: 20em">
                        <option value="">Todos</option>
                        <option value="cardapio">Cardapio</option>
                        <option value="montagem">Montagem</option>
                    </select>
                    <button type="submit" class="btn btn-success">BUSCaR</button>
                </div>
            </form>

            <div>
                <button onclick="Acessar('Desktop/CadGrupProduto.php', 'CadGrupProduto')" type="button" class="btn btn-success">Cadastrar</button>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>AÇÂO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/grupoprod.php';

                        $GrupoProd = new GrupoProd();

                        $Result = $GrupoProd->Select(" nome like '%" . $nome . "%' and tipogrupo like '%" . $tipo . "%'");

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td>' . $Linhas['nome'] . '</td>
                                    <td><a onclick="Acessar(\'Desktop/CadGrupProduto.php?id=' . $Linhas['id'] . '\', \'CadGrupProduto&id=' . $Linhas['id'] . '\')"><i class="fas fa-edit"></i> Editar</a></td>
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
                    url: "Desktop/BuscGrupProduto.php",
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