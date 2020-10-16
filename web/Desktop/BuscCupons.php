<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Cupons de descontos</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            <div>
                <button onclick="Acessar('Desktop/CadCupons.php', 'CadCupons')" type="button" class="btn btn-success">Cadastrar</button>
            </div>


            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>DATA FINAL</th>
                            <th>DISPONIVEIS</th>
                            <th>%</th>
                            <th>AÇÃO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/cupon.php';

                        $Bairros = new Cupom();

                        $Result = $Bairros->Select(' 1 = 1');

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                            echo '<tr class="odd gradeX">
                                    <td>'.$Linhas['id'].'</td>
                                    <td>'.$Linhas['Titulo'].'</td>
                                    <td>'.$Linhas['DataF'].'</td>
                                    <td>'.$Linhas['Disponivel'].'</td>
                                    <td>'.$Linhas['Percent'].'</td>    
                                    <td><a onclick="Acessar(\'Desktop/CadCupons.php?id='.$Linhas['id'].'\', \'CadCupons&id='.$Linhas['id'].'\')"><i class="fas fa-edit"></i> Editar</a></td>
                                  </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>


        </div>
    </div>



</div>