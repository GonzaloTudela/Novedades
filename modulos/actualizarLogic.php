<?php
session_start();
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

//region RECOGIDA VARIABLES DE SESSION TRAS LOGIN
if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['estado_usu'],
    $_SESSION['$empresas'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel'];
    $nombre = $_SESSION['nombre'];
    $apellido1 = $_SESSION['apellido1'];
    if (isset($_SESSION['apellido2'])) {
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
//endregion

// SI LLEGA POST CANCELAR VOLVEMOS A LA PAGINA DE ORIGEN.
if (isset($_POST['cancelar'])) {
    $url_origen_cancelar = $_POST['cancelar'];
    header("location:" . $url_origen_cancelar);
}
// SI LLEGA POST ACTUALIZAR RECOGEMOS VALOR ID_NOTICIA Y SEGUIMOS.
if (isset($_POST['id_noticia'], $_POST['origen'])) {
    $id_noticia = $_POST['id_noticia'];
    $url_origen_actualizar = $_POST['origen'];
} else {
    $url_origen_actualizar = '../index.php?error=indefinido';
}

if (isset($_POST['titulo'], $_POST['cuerpo'], $_POST['fecha_ini'], $_POST['fecha_fin'])) {
    $titulo = $_POST['titulo'];
    $cuerpo = $_POST['cuerpo'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
} else {
    exit("No llegó POST datos actualizacion");
}

// CONEXIÓN CON LA BASE DE DATOS
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}
$db_operario->set_charset('utf8mb4');

// CONSULTA NUMERO VERSIÓN DE LA NOTICIA EDITADA PARA SUMAR 1 EN LA NUEVA.
if (isset($sql_version)) {
    $stmt_version = $db_operario->prepare($sql_version);
    if ($sql_version === false) {
        $db_operario->close();
        exit("No se pudo preparar SQL version");
    }
    $stmt_version->bind_param('i', $id_noticia);
    $stmt_version->execute();
    $stmt_version->bind_result($version);
    $res_version = $stmt_version->fetch();
    if ($res_version === null) {
        $stmt_version->close();
        $db_operario->close();
        exit("Error en fetch de la versión");
    }
    $stmt_version->close();
    $version++;
}

// BUSCAR EL NIVEL DEL AUTOR DE LA NOTICIA QUE VAMOS A FINALIZAR
if (isset($sql_nivel_autor)) {
    $stmt_nivelAutor = $db_operario->prepare($sql_nivel_autor);
    $stmt_nivelAutor->bind_param('i', $id_noticia);
    $stmt_nivelAutor->execute();
    $stmt_nivelAutor->bind_result($nivelAutor);
    $stmt_nivelAutor->fetch();
    $stmt_nivelAutor->close();
}
if ($nivel >= $nivelAutor) {
    // SQL INSERTA LA NOTICIA EN NOTICIAS
    if (isset($sql_actualizar)) {
        $stmt_actualizar = $db_operario->prepare($sql_actualizar);
        if ($stmt_actualizar === false) {
            $db_operario->close();
            exit("No se pudo preparar SQL actualizar.");
        }
        if (empty($fecha_fin)) {
            $tipo = 1;
            $fecha_fin = null;
        } else {
            $tipo = 0;
        }
        $stmt_actualizar->bind_param('issssiii', $id_usuario, $titulo, $cuerpo, $fecha_ini, $fecha_fin,
            $id_noticia, $version, $tipo);
        $stmt_actualizar->execute();
        if ($stmt_actualizar->affected_rows === 1) {
            $id_nuevo = $stmt_actualizar->insert_id;
            $stmt_actualizar->close();
            $insertOK = true;
        }
    }
    // SQL ESCRIBIMOS LA NUEVA NOTICIA EN LA TABLA AFECTAR CON LOS EQUIPOS DE LA VIEJA.
    if (isset($insertOK, $sql_afectar) && $insertOK === true) {
        $stmt_afectar = $db_operario->prepare($sql_afectar);
        if ($stmt_afectar === false) {
            $db_operario->close();
            exit("Error en prepare SQL afectar");
        }
        foreach ($equipos as $equipo) {
            $id_equipo = $equipo['id_equipo'];
            $stmt_afectar->bind_param('ii', $id_equipo, $id_nuevo);
            $stmt_afectar->execute();
            if ($stmt_afectar->affected_rows === 1) {
                continue;
            }
            exit("Fallo en la insercion recursiva de AFECTAR.");
        }
        $stmt_afectar->close();
    }
} else {
    $db_operario->close();
    header('location:leer.php?error=actualizar');
    exit();
}
$db_operario->close();
header("location:" . $url_origen_actualizar);
exit();