<?php
ob_start();
if (!isset($_SESSION)) {
    session_start();
}

$_SESSION['_DIR_'] = realpath(dirname(__FILE__));
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Inicio</title>


        <link href="css/bootstrap.min.css" rel="stylesheet">


        <link href="css/metisMenu.min.css" rel="stylesheet">


        <link href="css/startmin.css" rel="stylesheet">


        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        
                <!-- jQuery -->
        <script src="js/jquery.js"></script>

        <!-- Bootstrap Core JavaScript -->
        <script src="js/bootstrap.min.js"></script>

        <!-- Metis Menu Plugin JavaScript -->
        <script src="js/metisMenu.min.js"></script>

        <!-- Custom Theme JavaScript -->
        <script src="js/startmin.js"></script>
        <script src="js/Ajax.js"></script>
        
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#Form_Login').submit(function () {
                    var dados = jQuery(this).serialize();
                    jQuery.ajax({
                        type: "POST",
                        url: "php/control/Usuario/Logar.php",
                        data: dados,
                        beforeSend: function () {
                            $("#checkbox").html("Carregando...");
                        },
                        success: function (data) {
                            $("#checkbox").html(data);
                        }
                    });

                    return false;
                });
            });
        </script>

    </head>
    <body>
		<?php
	
		//echo realpath(dirname(__FILE__));
	?>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Login</h3>
                        </div>
                        <div class="panel-body">
                            <form action="" method="post" id="Form_Login">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Usuário" name="Login" autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Senha" name="Senha" type="password" value="">
                                    </div>
                                    <div class="checkbox" id="checkbox">
                                        
                                    </div>                                    

                                    <button type="submit" class="btn btn-lg btn-success btn-block">Entrar</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
              
    </body>
</html>
