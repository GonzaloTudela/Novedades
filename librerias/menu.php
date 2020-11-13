<?php
//session_start();
//if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'],
//    $_SESSION['estado_usu'], $_SESSION['$empresas'])) {
//    $id_usuario = $_SESSION['id_usuario'];
//    $nivel = $_SESSION['nivel'];
//    $nombre = $_SESSION['nombre'];
//    $apellido1 = $_SESSION['apellido1'];
//    $apellido2 = $_SESSION['apellido2'];
//    $estado_usu = $_SESSION['estado_usu'];
//    $empresas = $_SESSION['$empresas'];
//    $equipos = $_SESSION['$equipos'];
//} else {
//    session_destroy();
//    header("location:../index.php");
//}
$nivel='ojete';
if (is_numeric($nivel)){
    switch ($nivel) {
        case $nivel>=0 && $nivel<=499:
            echo 'eres operario';
            break;
        case $nivel>=500 && $nivel<=998:
            echo 'eres responsable';
            break;
        case $nivel===999:
            echo 'eres admin';
            break;
        default:
            echo 'error';
    }
}else {
    echo 'no es numero';
}


