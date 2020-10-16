<?php

if (!isset($_SESSION)) {
    session_start();
}
include_once $_SESSION['_DIR_'] . '/php/model/Config.php';

$Config = new Config();

$Result = $Config->Select(" 1 = 1");
$Status = 0;
while ($Linhas = mysqli_fetch_assoc($Result)) {
    $Status = $Linhas['servico_ativo'];
}

if ($Status == 0) {
    $Status = 1;
} else {
    $Status = 0;
}

$Config->setServico($Status);

if ($Config->InBase('Update', ' 1 = 1')) {
    echo '<script type="text/javascript">
window.location.reload()
</script>';
}