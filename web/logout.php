<?php
if (!isset($_SESSION)) {
    session_start();
} else {
    session_destroy();
}
header('Location:index.php');