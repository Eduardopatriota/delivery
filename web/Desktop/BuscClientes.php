<div class="container-fluid">
    
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Clientes</h1>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Informações
        </div>
        <div class="panel-body">

            
            <div class="table-responsive" style="float: left; width: 100%; margin-top:  1em">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>TELEFONE</th>
                            <th>CPF</th>
                            <th>AçÃO</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!isset($_SESSION)) {
                            session_start();
                        }
                        include_once $_SESSION['_DIR_'] . '/php/model/pedido.php';

                        $Prod = new Pedido();
                        

                        $Result = $Prod->Execute("SELECT * FROM user_app");

                        while ($Linhas = mysqli_fetch_assoc($Result)) {
                           
                            
                            
                            echo '<tr class="odd gradeX">
                                    <td>' . $Linhas['id'] . '</td>
                                    <td>' . $Linhas['nome'] . '</td>
                                    <td>' . $Linhas['telefone'] . '</td>
                                    <td>' . $Linhas['cpf'] . '</td>
                                    <td><a onclick="Acessar(\'Desktop/CadClient.php?id='.$Linhas['id'].'\', \'CadClient&id='.$Linhas['id'].'\')"><i class="fas fa-edit"></i> Editar</a></td>
                                  </tr>
                                  ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>