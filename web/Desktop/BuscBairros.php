<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cadastro de bairros / taxas de entregas</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <div>
                <button onclick="Acessar('Desktop/CadBairros.php', 'CadBairros')" type="button" class="btn btn-success">Cadastrar</button>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>VALOR</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/bairros.php';

                        $Bairros = new Bairros();

                        $Result = $Bairros->Select(' 1 = 1');

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                            echo '<tr class="odd gradeX">
                                    <td>'.$Linhas['id'].'</td>
                                    <td>'.$Linhas['Nome'].'</td>
                                    <td>'.$Linhas['Valor'].'</td>
                                    <td><a onclick="Acessar(\'Desktop/CadBairros.php?id='.$Linhas['id'].'\', \'CadBairros&id='.$Linhas['id'].'\')"><i class="fas fa-edit"></i> Editar</a></td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>



</div>