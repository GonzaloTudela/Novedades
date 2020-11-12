<?php
require_once("../librerias/db_operario.php");
$captcha = null;
$responseKeys = null;
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
}
if (!$captcha) {
    header("location:../index.php?error=captcha");
}
$secretKey = "6LciBd8ZAAAAAKBw1fbLuK4vV8SkSJxwgMaBSLEJ";
$ip = $_SERVER['REMOTE_ADDR'];

// post request to server
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response=' . urlencode($captcha);
$response = file_get_contents($url);
try {
    $responseKeys = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    header("location:../index.php?error=recaptcha");
}
// Si el captcha responde SUCCESS (correcto)...
if ($responseKeys["success"]) {
    // Si recibimos login y pass...
    if (isset($_POST["login"], $_POST["pass"]) && $_POST["login"] !== "" && !$_POST["pass"] !== "") {
        $raw_login = ($_POST['login']);
        $raw_pass = ($_POST['pass']);
        // Creamos la conexión.
        $db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!', 'gonza_novedades');
        $db_operario->set_charset('utf8mb4');
//        // Comprobamos si hay error de conexión.
        if (mysqli_connect_errno()) {
            header("location:../index.php?error=mysql");
        }

        // LIMPIAMOS POR SI MAS ADELANTE NO UTLIZAMOS SENTENCIAS PREPARADAS CON LA BASE DE DATOS.
        $login = $db_operario->real_escape_string($raw_login);
        $pass = $db_operario->real_escape_string($raw_pass);

        // CONSULTAS SQL
        // Buscamos el password del usuario (hash).
        $sql_login = "select password from usuarios where usuario=?";

        // Datos usuario basicos si se encuentra de alta
        $sql_info = "select u.id_usuario,c.nivel,u.nombre,u.apellido1,u.apellido2,t.estado_usu from usuarios u
                    left join categorias c on u.id_categoria = c.id_categoria
                    left join trabajar t on u.id_usuario = t.id_usuario
                    left join empresas e on t.id_empresa = e.id_empresa
                    where usuario = ? and t.estado_usu=1";

        // Empresas del usuario activas
        $sql_empresas = "select nombre_emp, estado_emp,e.id_empresa from usuarios u
                    left join trabajar t on u.id_usuario = t.id_usuario
                    left join empresas e on t.id_empresa = e.id_empresa
                    where usuario = ? and estado_emp=1";

        // Equipos del usuario
        $sql_equipos = "select e.nombre_equ,e.id_equipo from usuarios u
                    left join formar f on u.id_usuario = f.id_usuario
                    left join equipos e on f.id_equipo = e.id_equipo
                    where usuario = ?";

        // CONSULTA DEL HASH DEL USUARIO
        $stmt_login = $db_operario->prepare($sql_login);
        $stmt_login->bind_param('s', $login);
        $stmt_login->execute();
        $stmt_login->bind_result($hash);
        $stmt_login->fetch();
        $stmt_login->close();

        // Verificación de la pass contra el hash en la BD.
        if (password_verify($pass, $hash)) {

            // CONSULTA DE DATOS DEL USUARIO
            $stmt_info = $db_operario->prepare($sql_info);
            $stmt_info->bind_param('s', $login);
            $stmt_info->execute();
            $stmt_info->bind_result($id_usuario, $nivel, $nombre, $apellido1, $apellido2, $estado_usu);
            $resultado_info = $stmt_info->fetch();
            $stmt_info->close();

            if ($estado_usu = 0) {
                $db_operario->close();
                header("location:../index.php?error=ubaja");
            }

            // EMPRESAS ACTIVAS A LAS QUE PERTENECE EL USUARIO
            // mysqli_prepare() returns a statement object or FALSE if an error occurred.
            $stmt_empresas = $db_operario->prepare($sql_empresas);
            $stmt_empresas->bind_param('s', $login);
            $stmt_empresas->execute();
            $resultado_empresas = $stmt_empresas->get_result();
            $empresas = $resultado_empresas->fetch_all(MYSQLI_ASSOC);
            $stmt_empresas->close();

            if ($empresas = '') {
                $db_operario->close();
                header("location:../index.php?error=ebaja");
            }

            // EQUIPOS A LOS QUE PERTENECE EL USUARIO
            $stmt_equipos = $db_operario->prepare($sql_equipos);
            $stmt_equipos->bind_param('s', $login);
            $stmt_equipos->execute();
            $stmt_equipos->bind_result($nombre_equ, $id_equipo);
            $resultado_equipos = $stmt_equipos->get_result();
            $equipos = $resultado_equipos->fetch_all(MYSQLI_ASSOC);
            $stmt_equipos->close();

            // PRUEBAS VARIABLES
//            print_r('$id_usuario ' . $id_usuario);
//            print_r(' $nivel ' . $nivel);
//            print_r(' $nombre ' . $nombre);
//            print_r(' $apellido1 ' . $apellido1);
//            print_r(' $apellido2 ' . $apellido2);
//            print_r(' $estado_usu ' . $estado_usu);
//            print_r($equipos);
//            print_r($empresas);

            // INICIAMOS LA SESION
            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido1'] = $apellido1;
            $_SESSION['apellido2'] = $apellido2;
            $_SESSION['estado_usu'] = $estado_usu;
            $_SESSION['$empresas'] = $empresas;
            $_SESSION['$equipos'] = $equipos;
            header("location:./novedades.php");

        } else {
            // Usuario o contraseña incorrectos.
            header("location:../index.php?error=login");
        }

    } else {
//         POST login y pass vacios.
        header("location:../index.php?error=datos");
    }
} else {
//     Captcha incorrecto
    header("location:../index.php?error=spam");
}