<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

session_start();

// DATOS RECOGIDOS EN LOGIN.
if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['apellido2'],
    $_SESSION['estado_usu'], $_SESSION['$empresas'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel'];
    $nombre = $_SESSION['nombre'];
    $apellido1 = $_SESSION['apellido1'];
    $apellido2 = $_SESSION['apellido2'];
    $estado_usu = $_SESSION['estado_usu'];
    $empresas = $_SESSION['$empresas'];
    $equipos = $_SESSION['$equipos'];
} else {
    session_destroy();
    $_SESSION[] = array();
    header("location:login.php?error=indefinido");
}
// RECUPERO EL ULTIMO ESTADO DE WEBORIGEN EN SESSION, SI NO ES POSIBLE DETERMINO QUE VOLVAMOS A NOVEDADES.
if (isset($_SESSION['webOrigen'])) {
    $origen = $_SESSION['webOrigen'];
} else {
    $origen = 'novedades';
}

// RECUPERO EL ID DE LA NOTICIA POR POST.
if (isset($_POST['id_noticia'])) {
    $id_noticia = $_POST['id_noticia'];
} else {
    header("location:login.php?error=indefinido");
}

//<editor-fold desc="CONEXIÓN BD E INSERT EN TABLA LEER">
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}
if (isset($sql_leer)) {
    $stmt_leer = $db_operario->prepare($sql_leer);
    $stmt_leer->bind_param('ii', $id_usuario, $id_noticia);
    $stmt_leer->execute();
    if ($stmt_leer->affected_rows === 1) {
        $stmt_leer->close();
        $db_operario->close();
        if ($origen === 'novedades') {
            header("location:novedades.php");
        } elseif ($origen === 'activas') {
            header("location:activas.php");
        } else {
            echo 'boom! no tengo ni idea de donde vengo!';
        }
    }
}
//</editor-fold>