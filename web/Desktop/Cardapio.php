<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cardápio</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <div class="table-responsive">
                <?php
                if (!isset($_SESSION)) {
                    session_start();
                }
                include_once $_SESSION['_DIR_'] . '/php/model/bd/Persistence.php';

                $Persistence = new Persistece();
                $Quebra = '';
                $Result = $Persistence->Select('select pr.id AS id_prod, pr.nome AS produto, pr.obs, pr.preco, gp.nome AS nome_grupo, gp.id, pr.tipoproduto from produto pr
                                                        inner join grupoprod gp on gp.id = pr.id_grupo
                                                        where pr.id > 0
                                                        ORDER BY gp.id');

                while ($Linhas = mysqli_fetch_assoc($Result)) {
                    if (($Linhas['id'] != $Quebra) && ($Quebra != '')) {

                        echo '</tbody>
                                      </table>';
                    }
                    if ($Linhas['id'] != $Quebra) {
                        echo '
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th width="350">' . $Linhas['nome_grupo'] . '</th>
                                        </tr>
                                    </thead>';
                        $Quebra = $Linhas['id'];
                    }
                    if ($Linhas['tipoproduto'] == 'Montagem') {

                        echo '
                                    <tbody>
                                        <tr class="odd gradeX">
                                            <td width="350">' . $Linhas['produto'] . '</td>
                                            <td>R$ ' . $Linhas['preco'] . '</td>
                                            <td><a onclick="Acessar(\'Desktop/CadProduto2.php?id=' . $Linhas['id_prod'] . '\', \'CadProduto&id=' . $Linhas['id_prod'] . '\')"><i class="fas fa-edit"></i> Editar</a></td>
                                        </tr>';

                    } else {
                        echo '
                                    <tbody>

                                        <tr class="odd gradeX">
                                            <td width="350">' . $Linhas['produto'] . '</td>
                                            <td>R$ ' . $Linhas['preco'] . '</td>
                                            <td><a onclick="Acessar(\'Desktop/CadProduto.php?id=' . $Linhas['id_prod'] . '\', \'CadProduto&id=' . $Linhas['id_prod'] . '\')"><i class="fas fa-edit"></i> Editar</a></td>
                                        </tr>';
                    }
                }
                ?>
            </div>


        </div>
    </div>



</div>