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
    $search = '../img/search.svg';
    $news = '../img/news.svg';
    $rules = '../img/rules.svg';
    $office = '../img/office.svg';
    $account = '../img/account.svg';
    $quit = '../img/exit.svg';
    // Links del boton.
    $webAdd=' ';
    $webSearch=' ';
    $webNews=' ';
    $webRules=' ';
    $webOffice=' ';
    $webAccount=' ';
    $webQuit='./quit.php';
    // Array menu estandard
    $menu = array(
        'add' => array($iconAdd, $webAdd),
        'search' => array($search,$webSearch),
        'news' => array($news,$webNews),
        'rules' => array($rules,$webRules),
        'account' => array($account, $webAccount),
        'quit' => array($quit,$webQuit));
    // Array menu admin
    $menuAdmin = array(
        'add' => array($iconAdd, $webAdd),
        'search' => array($search,$webSearch),
        'news' => array($news,$webNews),
        'rules' => array($rules,$webRules),
        'office' => array($office, $webOffice),
        'account' => array($account, $webAccount),
        'quit' => array($quit,$webQuit));

    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
    } else {
        echo '<script>console.log("Nivel no existe")</script>';
        exit();
    }
    if ($nivel >= 0 && $nivel <= 998) {
        foreach ($menu as $id) {
            $icon=$id[0];
            $web=$id[1];
            echo '<a href="'. $web .'"><button id="' . $id[0] . '" class="button ">';
            echo file_get_contents($icon);
            echo '</button></a>';
        }
    } elseif
    ($nivel === 999) {
        foreach ($menuAdmin as $id) {
            $icon=$id[0];
            $web=$id[1];
            echo '<a href="'. $web .'"><button id="' . $id[0] . '" class="button ">';
            echo file_get_contents($icon);
            echo '</button></a>';
        }
    } else {
        echo '<script>console.log ("El nivel esta fuera de los limites!")</script>';
    }
}