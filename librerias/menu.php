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
    // Iconos para el boton.
    $iconAdd = '../img/add.svg';
    $iconSearch = '../img/search.svg';
    $iconNews = '../img/news.svg';
    $iconRules = '../img/rules.svg';
    $iconOffice = '../img/office.svg';
    $iconAccount = '../img/account.svg';
    $iconQuit = '../img/exit.svg';
    // Links del boton.
    $webAdd = ' ';
    $webSearch = ' ';
    $webNews = ' ';
    $webRules = ' ';
    $webOffice = ' ';
    $webAccount = ' ';
    $webQuit = ' ';
    // Array menu estandard, id, icono, link, texto.
    $menu = array(
        0 => array('add', $iconAdd, $webAdd, 'Nueva'),
        1 => array('iconSearch', $iconSearch, $webSearch, 'Buscar'),
        2 => array('news', $iconNews, $webNews, 'Novedades'),
        3 => array('rules', $iconRules, $webRules, 'Normas'),
        4 => array('account', $iconAccount, $webAccount, 'Cuenta'),
        5 => array('quit', $iconQuit, $webQuit, 'EXIT'));
    // Array menu admin
    $menuAdmin = array(
        0 => array('add', $iconAdd, $webAdd, 'Nueva'),
        1 => array('iconSearch', $iconSearch, $webSearch, 'Buscar'),
        2 => array('news', $iconNews, $webNews, 'Novedades'),
        3 => array('rules', $iconRules, $webRules, 'Normas'),
        4 => array('office', $iconOffice, $webOffice, 'Empresas'),
        5 => array('account', $iconAccount, $webAccount, 'Cuenta'),
        6 => array('quit', $iconQuit, $webQuit, 'EXIT'));

    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
    } else {
        echo '<script>console.log("Nivel no existe")</script>';
        exit();
    }
    if ($nivel >= 0 && $nivel <= 998) {
        foreach ($menu as $item) {
            $id = $item[0];
            $icon = $item[1];
//            $web=$item[2];
            echo '<button id="' . $id . '" class="menuButton ">';
//            echo '<a href="'. $web .'"><button id="' . $id . '" class="button ">';
            echo file_get_contents($icon);
//            echo '<p class="hint">$id</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    } elseif
    ($nivel === 999) {
        foreach ($menuAdmin as $item) {
            $id = $item[0];
            $icon = $item[1];
//            $texto=$item[3];
//            $web=$item[2];
            echo '<button id="' . $id . '" class="menuButton ">';
//            echo '<a href="'. $web .'"><button id="' . $id . '" class="button ">';
            echo file_get_contents($icon);
//            echo '<p class="hint">' . $texto . '</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    } else {
        echo '<script>console.log ("El nivel esta fuera de los limites!")</script>';
    }
}