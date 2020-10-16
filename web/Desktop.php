<?php
date_default_timezone_set('America/Manaus');
if (!isset($_SESSION)) {
    session_start();
}

$_SESSION['_DIR_'] = realpath(dirname(__FILE__));

if ($_SESSION['_NOME_USER'] != 'Admin') {
    $Menu = 'disabled';
}


?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Web Delivery - Administrador</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">
    <link href="css/startmin.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/startmin.css" rel="stylesheet">
    <link href="css/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link href="css/dataTables/dataTables.responsive.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>
    <script src="js/startmin.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/startmin.js"></script>
    <script src="js/dataTables/jquery.dataTables.min.js"></script>
    <script src="js/dataTables/dataTables.bootstrap.min.js"></script>
    
    <style type="text/css">
        .disabled {
            pointer-events: none; 
            opacity: 0.6; 
        }

        a:link {
            color: #000000;
        }

        a:visited {
            color: #000000;
        }

        a:hover {
            color: #000000;
            text-decoration: none;
        }

        a:focus {
            outline: 1px dotted #000000;;
        }

        a:active {
            color: #000000;;
        }
    </style>


    <!-- Morris Charts CSS -->
    <link href="css/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    <script type="text/javascript">
        function Acessar(Url, Page) {
            window.history.replaceState('Object', 'Titulo da Página', '?Page=' + Page);
            var dados = jQuery(this).serialize();
            jQuery.ajax({
                type: "POST",
                url: Url,
                data: dados,
                beforeSend: function() {
                    $("#page-wrapper").html(
                        '<div><h1 class="page-header" align="center">CARREGANDO...</h1></div');
                },
                success: function(data) {
                    $("#page-wrapper").html(data);
                },
                error: function(a, b, c) {
                    $("#page-wrapper").html(
                        '<div><h1 class="page-header" align="center">ERRO 404 - PAGINA NÃO ENCONTRADA</h1></div'
                    );
                }
            });
        }

        function MudarStatus() {
            var dados = jQuery(this).serialize();
            jQuery.ajax({
                type: "POST",
                url: "php/control/Config/MudarStatus.php",
                data: dados,
                beforeSend: function() {
                    $("#page-wrapper").html(
                        '<div><h1 class="page-header" align="center">CARREGANDO...</h1></div');
                },
                success: function(data) {
                    $("#page-wrapper").html(data);
                },
                error: function(a, b, c) {
                    $("#page-wrapper").html(
                        '<div><h1 class="page-header" align="center">ERRO 404 - PAGINA NÃO ENCONTRADA</h1></div'
                    );
                }
            });
        }
    </script>
    <style>
        a {
            cursor: hand;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Web Delivery</a>
            </div>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Top Navigation: Left Menu -->
            <ul class="nav navbar-nav navbar-left navbar-top-links">
                <li><a href="#"><i class="fa fa-home fa-fw"></i>Home</a></li>
            </ul>

            <!-- Top Navigation: Right Menu -->
            <ul class="nav navbar-right navbar-top-links">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['_NOME_USER']; ?> <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Sidebar -->
            <div class="navbar-default sidebar" role="navigation" style="overflow-y: scroll; height:700px;">
                <div class="sidebar-nav navbar-collapse">

                    <ul class="nav" id="side-menu" style="height: 100em">
                        <li class="sidebar-search">
                            <img src="img/Logo4k.png" width="100" style="margin-left: 3em">
                        </li>
                        <li>
                            <a onclick="Acessar('Desktop/Home.php', 'Home')"><i class="fas fa-home"></i> Home</a>
                        </li>
                        <li>
                            <a onclick="Acessar('Desktop/BuscPedido.php', 'BuscPedido')" class="active"><i class="fa fa-shopping-cart"></i> Pedidos</a>
                        </li>
                        <li>
                            <a onclick="Acessar('Desktop/BuscPedidoMotoy.php', 'BuscPedidoMotoy')" class="active"><i class="fa fa-shopping-cart"></i> Pedidos por motoby</a>
                        </li>
                        <li>
                            <a onclick="Acessar('Desktop/BuscPedidoResulm.php', 'BuscPedidoResulm')" class="active"><i class="fa fa-shopping-cart"></i> Resumo de pedidos</a>
                        </li>
                        <li>
                            <i style="color: #fff">.</i>
                        </li>

                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscClientes.php', 'BuscClientes')" class="active"><i class="fa fa-receipt"></i> Listar clientes</a>
                        </li>

                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscGrupProduto.php', 'BuscGrupProduto')" class="active"><i class="fa fa-receipt"></i> Cad:. Grupo de produtos</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscProduto.php', 'BuscProduto')" class="active"><i class="fa fa-gifts"></i> Cad:. Produtos</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscProduto2.php', 'BuscProduto2')" class="active"><i class="fa fa-gifts"></i> Cad:. Sub Produtos</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscBairros.php', 'BuscBairros')" class="active"><i class="fas fa-map-marker-alt"></i></i> Cad:. Bairros</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscUser.php', 'BuscUser')" class="active"><i class="fa fa-male"></i></i> Cad:. Usuarios</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscMoto.php', 'BuscMoto')" class="active"><i class="fa fa-motorcycle"></i></i> Cad:. MotoBoy's</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/CadPush.php', 'CadPush')" class="active"><i class="fas fa-paper-plane"></i></i> Push</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscCupons.php', 'BuscCupons')" class="active"><i class="fas fa-paper-plane"></i></i> Cupons</a>
                        </li>
                        <li>
                            <a class="<?php echo $Menu; ?>" onclick="Acessar('Desktop/BuscEmpresas.php', 'BuscEmpresas')" class="active"><i class="fa fa-male"></i></i> Cad:. Empresas</a>
                        </li>

                        </li>
                        <li>
                            <i style="color: #fff">.</i>
                        </li>
                        <li>
                            <a onclick="Acessar('Desktop/Cardapio.php', 'Cardapio')" class="active"><i class="far fa-clipboard"></i> Ver Cardápio</a>
                        </li>
                        <li style="margin-top: 1em">
                            <i style="color: #fff">.</i>
                        </li>
                        <li>
                            <?php

                            include_once $_SESSION['_DIR_'] . '/php/model/Config.php';

                            $Config = new Config();

                            $Result = $Config->Select(" 1 = 1");
                            $Status = 0;
                            while ($Linhas = mysqli_fetch_assoc($Result)) {
                                $Status = $Linhas['servico_ativo'];
                            }

                            if ($Status == 0) {
                                echo '<button  style="width: 100%" onclick="MudarStatus()" type="button" class="btn btn-success">Ativar serviço</button>';
                            } else {
                                echo '<button  style="width: 100%" onclick="MudarStatus()" type="button" class="btn btn-danger">Desativar serviço</button>';
                            }
                            ?>
                        </li>

                    </ul>

                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">

        </div>

    </div>    

    <script type="text/javascript">
        <?php
        if (!isset($_GET)) {
        } else {
            if (!isset($_GET['Page'])) {
            } else {
                $url = $_SERVER['REQUEST_URI'];

                if ($url != "") {
                    $url = "?" . $url;
                }
                if (!isset($_GET['id'])) {
                    echo "Acessar('Desktop/" . $_GET['Page'] . ".php" . $url . "', '" . $_GET['Page'] . "');";
                } else {
                    echo "Acessar('Desktop/" . $_GET['Page'] . ".php" . $url . "', '" . $_GET['Page'] . "&id=" . $_GET['id'] . "');";
                }
            }
        }
        ?>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
        });
    </script>


</body>

</html>