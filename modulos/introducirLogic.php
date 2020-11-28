<?php
session_start();

require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

//region RECOGIDA VARIABLES DE SESSION TRAS LOGIN
// DATOS RECOGIDOS EN LOGIN - SI NO ESTÁN, ACCESO NO AUTENTIFICADO -> LOGIN.PHP.
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
//endregion

// SI LLEGA POST CANCELAR VOLVEMOS A LA PAGINA DE ORIGEN.
if (isset($_POST['cancelar'])) {
    $url_origen_cancelar = $_POST['cancelar'];
    header("location:" . $url_origen_cancelar);
    exit();
}
// SI LLEGA POST ENVIAR RECOGEMOS DATOS Y SEGUIMOS.
if (isset($_POST['titulo'], $_POST['fecha_ini'], $_POST['fecha_fin'], $_POST['cuerpo'], $_POST['enviar'],
    $_POST['equipos'])) {
    $titulo = $_POST['titulo'];
    $fecha_ini = $_POST['fecha_ini'];
    $fecha_fin = $_POST['fecha_fin'];
    $cuerpo = $_POST['cuerpo'];
    $selectEquipo = $_POST['equipos'];
    $url_origen_enviar = $_POST['enviar'];
}

// CONECTAMOS CON LA BD.
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}
$db_operario->set_charset('utf8mb4');

// INSERTAMOS LOS DATOS.
if (isset($sql_insertar)) {
    $stmt_insertar = $db_operario->prepare($sql_insertar);
    if ($stmt_insertar === false) {
        $db_operario->close();
        exit("No se pudo preparar SQL version");
    }
    // SI LA FECHA FIN ESTA VACÍA LE DAMOS VALOR NULL Y TIPO VALE 1 (NORMA).
    if (empty($fecha_fin)) {
        $tipo = 1;
        $fecha_fin = null;
    } else {
        $tipo = 0;
    }

    // SI LA FECHA INICIO ESTA VACIA, LE DAMOS LA FECHA DE HOY.
    if (empty($fecha_ini)) {
        $fecha_ini = date("Y-m-d");
    }

    // CREACION DE LA NOTICIA
    $stmt_insertar->bind_param('issssi', $id_usuario, $titulo, $cuerpo, $fecha_ini, $fecha_fin, $tipo);
    $stmt_insertar->execute();
    if ($stmt_insertar->affected_rows === 1) {
        $id_nuevo = $stmt_insertar->insert_id;
        $insertOK = true;
        $stmt_insertar->close();
    } else {
        exit("Error al insertar la noticia");
    }
}

// SQL ESCRIBIMOS LA NUEVA NOTICIA EN LA TABLA AFECTAR CON LOS EQUIPOS DE LA VIEJA.
if (isset($insertOK, $sql_afectar, $sql_afectar_admin) && $insertOK === true) {
    $stmt_afectar = $db_operario->prepare($sql_afectar);
    $stmt_afectar_admin = $db_operario->prepare($sql_afectar_admin);
    if ($stmt_afectar === false) {
        $db_operario->close();
        exit("Error en prepare SQL afectar");
    }
    if ($stmt_afectar_admin === false) {
        $db_operario->close();
        exit("Error en prepare SQL afectar");
    }
    if ($selectEquipo === 'todos') {
        if ($nivel===999){
            $stmt_afectar_admin->bind_param('i',$id_nuevo);
            $stmt_afectar_admin->execute();
            if ($stmt_afectar->affected_rows >= 1) {
                $stmt_afectar_admin->close();
            }
        }
        if ($nivel!==999){
            foreach ($equipos as $equipo) {
                $id_equipo = $equipo['id_equipo'];
                $stmt_afectar->bind_param('ii', $id_equipo, $id_nuevo);
                $stmt_afectar->execute();
                if ($stmt_afectar->affected_rows === 1) {
                    continue;
                }
                exit("Fallo en la insercion recursiva de AFECTAR.");
            }
        }
    }

    if ($selectEquipo !== 'todos') {
        $id_equipo = $selectEquipo;
        $stmt_afectar->bind_param('ii', $id_equipo, $id_nuevo);
        $stmt_afectar->execute();
        if ($stmt_afectar->affected_rows !== 1) {
            exit("Fallo en la insercion en AFECTAR.");
        }
    }
    $stmt_afectar->close();
}
// SQL ESRIBIMOS LA NUEVA NOTICIA COMO LEIDA POR EL USUARIO QUE LA HA INTRODUCIDO.
if (isset($insertOK,$id_nuevo,$sql_leer,$id_usuario)){
    $stmt_leer=$db_operario->prepare($sql_leer);
    $stmt_leer->bind_param('ii', $id_usuario, $id_nuevo);
    $stmt_leer->execute();
    if ($stmt_leer->affected_rows === 1) {
        $stmt_leer->close();
    }
}
$db_operario->close();
header("location:" . $url_origen_enviar);
exit();
