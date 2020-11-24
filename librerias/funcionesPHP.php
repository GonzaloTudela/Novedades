<?php
//debugFor("79.152.7.228");
// Emito errores dependiendo de la IP que me indican "STRING".
function debugFor($ip)
{
    if ($_SERVER['REMOTE_ADDR'] === $ip) {
        error_reporting(E_ALL);
    } else {
        error_reporting(0);
    }
}

function debugConsole($datos)
{
    echo "<script>console.log(" . json_encode($datos) . ")</script>";
}

// Recorro el array $keyNovedades y escribo el contenido en la pagina.
function escribeNovedades(array $array)
{
    if (empty($array)) {
        echo '<div class="msg">';
        echo file_get_contents("../img/robot.svg");
        echo '<p>SIN NOTICIAS</p>';
        echo '</div>';
    } else {
        foreach ($array as $keyNovedades) {
            $escritura = $keyNovedades['timestamp_not'];
            // ICONO DE LA NOTICIA
            echo '<div class="noticiaIcono altura2 sombra2">';
            if ($keyNovedades['fecha_fin'] == '') {
                echo file_get_contents("../img/warning-24px.svg");
            } else {
                echo file_get_contents("../img/timer-sand.svg");
            }
            echo '</div>';
            // TITULO Y FECHAS
            echo '<div class="noticiaTitulo altura2 sombra2">';
            echo '<form method="post" action="../modulos/leer.php">';
//            echo '<label for="id_noticia">';
            echo '<input type="hidden" name="id_noticia" value="' . htmlentities($keyNovedades['id_noticia']) . '">';
            echo '<button class="txt-r1 fs2">' . '<p>' . htmlentities($keyNovedades['titulo']) . '</p>';
//        echo '<p class="txt1 fs2">' . $keyNovedades['titulo'] . '</p>';
            if ($keyNovedades['fecha_fin'] == '') {
                echo '<p class="txt3 fs3">' . 'Norma desde: ' . htmlentities($keyNovedades['fecha_inicio']) . '</p>';
            } else {
                echo '<p class="txt3 fs3">' . htmlentities($keyNovedades['fecha_inicio']) . ' hasta ' . htmlentities($keyNovedades['fecha_fin']) . '</p>';
            }
            echo '</button>';
            echo '</form>';
            echo '</div>';
            // AUTOR Y TIMESTAMP
            echo '<div class="noticiaAutor altura2 sombra2">';
            echo '<p class="txt-r1 fs3">' . htmlentities($keyNovedades['nombre']) . ' ' . htmlentities($keyNovedades['apellido1']) . '</p>';
            echo '<p class="txt3 fs4">' . htmlentities($escritura) . '</p>';
            echo '</div>';
        }
    }

}

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
    $webAdd = '../modulos/introducir.php';
    $webSearch = '';
    $webNews = '../modulos/novedades.php';
    $webRules = '../modulos/activas.php';
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
    // RECUPERO EL NIVEL DEL USUARIO PARA COMPROBAR MAS ADELANTE
    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
    } else {
        echo '<script>console.log("Nivel no existe")</script>';
        exit();
    }
    // SI EL USUARIO ES NORMAL
    if ($nivel >= 0 && $nivel <= 998) {
        foreach ($menu as $item) {
            $id = $item[0];
            $icon = $item[1];
            $web = $item[2];
//            $texto = $item[3];
            // EN CASO DE QUE EL DATO SEA EL DEL BOTON QUIT NO ESCRIBIMOS EL TAG <A> PARA EVITAR SALIR SIN MOSTRAR
            // EL MENSAJE DE CONFIRMACION.
            echo '<button id="' . $id . '" class="menuButton ">';
            if ($item[0] !== 'quit') {
                echo '<a href="' . $web . '">';
            }
//            echo $texto;
            // EN CASO DE QUE EL DATO SEA EL DEL BOTON QUIT NO ESCRIBIMOS EL TAG <A> PARA EVITAR SALIR SIN MOSTRAR
            // EL MENSAJE DE CONFIRMACION.
            echo file_get_contents($icon);
            if ($item[0] !== 'quit') {
                echo '</a>';
            }
//            echo '<p class="hint">' . $texto . '</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    } elseif ($nivel === 999) {
        foreach ($menuAdmin as $item) {
            $id = $item[0];
            $icon = $item[1];
            $web = $item[2];
//            $texto = $item[3];
            echo '<button id="' . $id . '" class="menuButton ">';
            if ($item[0] !== 'quit') {
                echo '<a href="' . $web . '">';
            }
//            echo $texto;
            echo file_get_contents($icon);
            if ($item[0] !== 'quit') {
                echo '</a>';
            }
//            echo '<p class="hint">' . $texto . '</p>';
            echo '</button>';
//            echo '</button></a>';
        }
    } else {
        echo '<script>console.log ("El nivel esta fuera de los limites!")</script>';
    }
}

function resetAllSessions()
{
// Finds all server sessions
    session_start();
// Stores in Array
    $_SESSION = array();
// Swipe via memory
    if (ini_get("session.use_cookies")) {
        // Prepare and swipe cookies
        $params = session_get_cookie_params();
        // clear cookies and sessions
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
// Just in case.. swipe these values too
    ini_set('session.gc_max_lifetime', 0);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 1);
// Completely destroy our server sessions..
    session_destroy();

}

/*
    MODIFIED VERSION FOR LEARNING PURPOSES BY Gonzalo Tudela 2020.
    --------------------------------------------------------------------------------------------------------------------
    Paul's Simple Diff Algorithm v 0.1
    (C) Paul Butler 2007 <http://www.paulbutler.org/>
    May be used and distributed under the zlib/libpng license.

    This code is intended for learning purposes; it was written with short
    code taking priority over performance. It could be used in a practical
    application, but there are a few ways it could be optimized.

    Given two arrays, the function diff will return an array of the changes.
    I won't describe the format of the array, but it will be obvious
    if you use print_r() on the result of a diff on some test data.

    htmlDiff is a wrapper for the diff command, it takes two strings and
    returns the differences in HTML. The tags used are <ins> and <del>,
    which can easily be styled with CSS.
*/
function diff($old, $new)
{
    $matrix = array();
    $maxlen = 0;
    foreach ($old as $oindex => $ovalue) {
        $nkeys = array_keys($new, $ovalue);
        foreach ($nkeys as $nindex) {
            $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
            if ($matrix[$oindex][$nindex] > $maxlen) {
                $maxlen = $matrix[$oindex][$nindex];
                $omax = $oindex + 1 - $maxlen;
                $nmax = $nindex + 1 - $maxlen;
            }
        }
    }
    if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));
    return array_merge(
        diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
        array_slice($new, $nmax, $maxlen),
        diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

function htmlDiff($old, $new)
{
    $ret = '';
    $diff = diff(preg_split("/[\s]+/", $old), preg_split("/[\s]+/", $new));
    foreach ($diff as $k) {
        if (is_array($k))
            // ORIGINAL
//            $ret .= (!empty($k['d'])?"<del>".implode(' ',$k['d'])."</del> ":'').
//                (!empty($k['i'])?"<ins>".implode(' ',$k['i'])."</ins> ":'');
            // SOLO CAMBIOS
            $ret .= (!empty($k['i']) ? "<ins>" . implode(' ', $k['i']) . "</ins> " : '');
        else $ret .= $k . ' ';
    }
    return $ret;
}