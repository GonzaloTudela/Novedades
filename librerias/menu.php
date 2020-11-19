<?php
// Imprimo el menu utilizando un array con el ID, icono, link y texto descriptivo.
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
    $webAdd = '';
    $webSearch = '';
    $webNews = '../modulos/novedades.php';
    $webRules = '../modulos/normas.php';
    $webOffice = '';
    $webAccount = '';
    $webQuit = '';
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
            $web = $item[2];
//            $texto = $item[3];
            echo '<button id="' . $id . '" class="menuButton ">';
            echo '<a href="' . $web . '">';
//            echo $texto;
            echo file_get_contents($icon);
            echo '</a>';
//            echo '<p class="hint">' . $texto . '</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    }
    if ($nivel === 999) {
        foreach ($menuAdmin as $item) {
            $id = $item[0];
            $icon = $item[1];
            $web = $item[2];
//            $texto = $item[3];
            echo '<button id="' . $id . '" class="menuButton ">';
            echo '<a href="' . $web . '">';
//            echo $texto;
            echo file_get_contents($icon);
            echo '</a>';
//            echo '<p class="hint">' . $texto . '</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    } else {
        echo '<script>console.log ("El nivel esta fuera de los limites!")</script>';
    }
}