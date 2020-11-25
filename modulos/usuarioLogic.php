<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

session_start();

//region RECOGIDA VARIABLES DE SESSION TRAS LOGIN
// DATOS RECOGIDOS EN LOGIN - SI NO ESTÁN, ACCESO NO AUTENTIFICADO -> LOGIN.PHP.
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
    header("location:../index.php?error=login");
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
if (isset($_POST['usuario'], $_POST['email'], $_POST['pass'], $_POST['vpass'], $_POST['enviar'])) {
    $post_usuario = $_POST['usuario'];
    $post_email = $_POST['email'];
    $post_pass = $_POST['pass'];
    $post_vpass = $_POST['vpass'];
    $url_origen_enviar = $_POST['enviar'];
} else {
    header("location:novedades.php");
    exit();
}

// VERIFICACIÓN DEL ESTADO DE LOS CAMPOS
empty($post_usuario) ? $err_usuario = true : $err_usuario = false;
empty($post_email) ? $err_email = true : $err_email = false;
empty($post_pass) ? $err_pass = true : $err_pass = false;
empty($post_vpass) ? $err_vpass = true : $err_vpass = false;
($post_pass !== $post_vpass) ? $err_diff_pass = true : $err_diff_pass = false;

// SI LAS CONTRASEÑAS NO COINCIDEN VOLVEMOS AL MENÚ.
if ($err_diff_pass) {
    header("location:usuario.php?error=passDiff");
    exit();
}

// CONECTAMOS CON LA BD.
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}
$db_operario->set_charset('utf8mb4');

// CONSULTAMOS DATOS DEL USUARIO PARA VERIFICAR Y SUSTITUIR EN CADA CASO.
if (isset($sql_test_usuario)) {
    $stmt_test_usuario = $db_operario->prepare($sql_test_usuario);
    if ($stmt_test_usuario === false) {
        $db_operario->close();
        exit("No se pudo preparar SQL test_usuario");
    }
    // LEER DATOS USUARIO
    $stmt_test_usuario->bind_param('i', $id_usuario);
    $stmt_test_usuario->execute();
    $stmt_test_usuario->bind_result($vUsuario, $vEmail, $vPass);
    $res_test_usuario = $stmt_test_usuario->fetch();
    if ($res_test_usuario === null) {
        $stmt_test_usuario->close();
        exit("Error en la consulta, no se recuperaron datos");
    }
    $stmt_test_usuario->close();
}

// CAMBIAMOS LOS DATOS DE USUARIO SOLICITADOS Y DEJAMOS EL RESTO CON LAS VERSIONES VERIFICADAS.
if (isset($sql_usuario)) {
    $stmt_usuario = $db_operario->prepare($sql_usuario);
    if ($stmt_usuario === false) {
        $db_operario->close();
        exit("No se pudo preparar SQL usuario");
    }
    //TESTS Y SUSTITUCIONES
    $err_usuario ? $usuario = $vUsuario : $usuario = $post_usuario;
    $err_email ? $email = $vEmail : $email = $post_email;
    if ($err_pass){
        $pass=$vPass;
    } else {
//        $pass=password_hash($post_pass,PASSWORD_ARGON2I);
        $pass=password_hash('1234',PASSWORD_ARGON2I);
    }

    // CAMBIO DATOS
    $stmt_usuario->bind_param('sssi', $usuario, $email, $pass,$id_usuario);
    $stmt_usuario->execute();
    if ($stmt_usuario->affected_rows === 1) {
        $id_nuevo = $stmt_usuario->insert_id;
        $insertOK = true;
        $stmt_usuario->close();
    } else {
        exit("Error al insertar la noticia");
    }
}
$db_operario->close();
header("location:usuario.php?error=datPersOk");
exit();
