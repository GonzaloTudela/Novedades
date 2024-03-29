<?php
session_start();
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

// DATOS RECOGIDOS EN LOGIN.
if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['estado_usu'],
    $_SESSION['$empresas'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel'];
    $nombre = $_SESSION['nombre'];
    $apellido1 = $_SESSION['apellido1'];
    if (isset($_SESSION['apellido2'])){
        $apellido2 = $_SESSION['apellido2'];
    } else {
        $apellido2 = null;
    }
    $estado_usu = $_SESSION['estado_usu'];
    $empresas = $_SESSION['$empresas'];
    $equipos = $_SESSION['$equipos'];
} else {
    session_destroy();
    $_SESSION[] = array();
    header("location:../index.php?error=sesion");
    exit();
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
    header("location:login.php");
}


//CONEXIÓN BD
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}

// BUSCAR EL NIVEL DEL AUTOR DE LA NOTICIA QUE VAMOS A FINALIZAR
if (isset($sql_nivel_autor)){
    $stmt_nivelAutor=$db_operario->prepare($sql_nivel_autor);
    $stmt_nivelAutor->bind_param('i', $id_noticia);
    $stmt_nivelAutor->execute();
    $stmt_nivelAutor->bind_result($nivelAutor);
    $stmt_nivelAutor->fetch();
    $stmt_nivelAutor->close();
}
if ($nivel>=$nivelAutor) {
    // FINALIZAR LA NOTICIA
    if (isset($sql_leer, $sql_finalizar)) {
        $stmt_leer = $db_operario->prepare($sql_leer);
        $stmt_leer->bind_param('ii', $id_usuario, $id_noticia);
        $stmt_leer->execute();
        $stmt_finalizar = $db_operario->prepare($sql_finalizar);
        $stmt_finalizar->bind_param('i', $id_noticia);
        $stmt_finalizar->execute();
        if ($stmt_finalizar->affected_rows === 1) {
            $stmt_leer->close();
            $stmt_finalizar->close();
            $db_operario->close();
            if ($origen === 'novedades') {
                header("location:novedades.php");
            } elseif ($origen === 'activas') {
                header("location:activas.php");
            } else {
                header("location:novedades.php");
            }
        }
    }
} else {
    header('location:leer.php?error=finalizar');
}