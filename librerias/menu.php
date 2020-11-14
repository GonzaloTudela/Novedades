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
function printMenu()
{
    $add='../img/add.svg';
    $search='../img/search.svg';
    $news='../img/news.svg';
    $rules='../img/rules.svg';
    $office='../img/office.svg';
    $account='../img/account.svg';
    $menu=array($add,$search,$news,$rules,$account);
    $menuAdmin=array($add,$search,$news,$rules,$office,$account);
    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
    } else {
        echo '<p>ERROR IDENTIFICACION PERMISOS USUARIO</p>';
        exit();
    }
    if ($nivel >= 0 && $nivel <= 998) {
        foreach ($menu as $icon){
            echo '<button id="search" class="button ">';
            echo file_get_contents($icon);
            echo '</button>';
        }
    } elseif ($nivel === 999) {
        foreach ($menuAdmin as $icon){
            echo '<button id="search" class="button ">';
            echo file_get_contents($icon);
            echo '</button>';
        }
    } else {
        echo '<script>alert ("Algo ocurrió con el valor del nivel de usuario.")</script>';
    }
}