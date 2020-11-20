<?php
require_once "../librerias/funcionesPHP.php";
require_once "../librerias/consultas.php";
//debugFor("79.152.7.228");
$cheat = 1;
$reset = 0;
//region PREPARACIÓN reCAPTCHAv2
$captcha = null;
$responseKeys = null;
if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
} else {
    header("location:../index.php?error=recaptcha");
    die();
}
// Clave correspondiente a la configuración de reCaptcha en google.
$secretKey = "6LciBd8ZAAAAAKBw1fbLuK4vV8SkSJxwgMaBSLEJ";
// Ip de la máquina que realiza la petición.
$ip = $_SERVER['REMOTE_ADDR'];

// Creamos la url con la clave y la ip
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) . '&response='
    . urlencode($captcha);
// Recogemos en response el resultado de consultar la URL compuesta.
$response = file_get_contents($url);
// Promesa que recupera el contenido de la respuesta JSON de la API reCAPTCHA.
try {
    $responseKeys = json_decode($response, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    header("location:../index.php?error=recaptcha");
    die();
}
//endregion

// Si la decodificación de la respuesta JSON del api fue SUCCESS...
// todo ELIMINAR LA CONDICION CHEAT PARA SALTARSE EL VERIFY DE CAPTCHA ANTES DEL DEPLOY FINAL, OJO CON RESET=1.
if ($reset === 1) {
    resetAllSessions();
}

// SI RECAPTCHA ES SUCCESS O CHEAT === 1 ENTRAMOS.
if ($responseKeys["success"] || $cheat === 1) {
//if ($cheat === 1) {
    // COMPROBACIÓN DE POST LOGIN Y PASS -- MEJOR EMPEZAR SI NO SE CUMPLE (MEJORA FUTURA).
    if (isset($_POST["login"], $_POST["pass"]) && ($_POST["login"] !== "" && $_POST["pass"] !== "")) {
        $raw_login = ($_POST['login']);
        $raw_pass = ($_POST['pass']);

        // CONFIGURACIÓN MYSQLi
        $db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
            'gonza_novedades');
        $db_operario->set_charset('utf8mb4');

        // SI HUBO ERROR EN LA CONEXIÓN SALIMOS
        if (mysqli_connect_errno()) {
            header("location:../index.php?error=mysql");
            exit();
        }

        // LIMPIAMOS LOGIN Y PASS.
        $login = $db_operario->real_escape_string($raw_login);
        $pass = $db_operario->real_escape_string($raw_pass);

        // CONSULTA DEL PASSWORD HASHEADO DEL USUARIO
        if (isset($sql_login)){
            $stmt_login = $db_operario->prepare($sql_login);
            // SI HAY ERRORES EN SENTENCIA SQL
            if ($stmt_login===false) {
                header("location:../index.php?error=login");
                exit();
            }
            $stmt_login->bind_param('s', $login);
            $stmt_login->execute();
            $stmt_login->bind_result($hash);
            $resultado_login = $stmt_login->fetch();
            if ($resultado_login === null) {
                $stmt_login->close();
                $db_operario->close();
                header("location:../index.php?error=login");
                exit();
            }
            $stmt_login->close();
        }

        // VERIFICACION DE LA PASS CONTRA EL HASH ALMACENADO (SOLO ARGON2I O ARGON2ID)
        if (password_verify($pass, $hash)) {

            // CONSULTA DE DATOS DEL USUARIO
            if (isset($sql_info)) {
                $stmt_info = $db_operario->prepare($sql_info);
                // SI HAY ERRORES EN SENTENCIA SQL
                if ($stmt_info === false) {
                    $db_operario->close();
                    header("location:../index.php?error=ubaja");
                    exit();
                }
                // NO ELSE POR EL EXIT ANTERIOR.
                $stmt_info->bind_param('s', $login);
                $stmt_info->execute();
                $stmt_info->bind_result($id_usuario, $nivel, $nombre, $apellido1, $apellido2, $estado_usu);
                $resultado_info = $stmt_info->fetch();
                // SI EL USUARIO YA NO EXISTE (RESULTADO NULL) -> SALIR
                if ($resultado_info === null) {
                    $stmt_info->close();
                    $db_operario->close();
                    header("location:../index.php?error=ubaja");
                    exit();
                }
                $stmt_info->close();
            }

            // EMPRESAS ACTIVAS A LAS QUE PERTENECE EL USUARIO
            // mysqli_prepare() returns a statement object or FALSE if an error occurred.
            if (isset($sql_empresas)) {
                $stmt_empresas = $db_operario->prepare($sql_empresas);
                if ($stmt_empresas === false) {
                    $db_operario->close();
                    header("location:../index.php?error=ebaja");
                    exit();
                }
                // NO ELSE POR EL EXIT ANTERIOR.
                $stmt_empresas->bind_param('s', $login);
                $stmt_empresas->execute();
                $resultado_empresas = $stmt_empresas->get_result();
                // SI NO PERTENECE A NINGUNA EMPRESA ACTIVA -> SALIR
                if ($resultado_empresas->num_rows===0){
                    $stmt_empresas->close();
                    $db_operario->close();
                    header("location:../index.php?error=ebaja");
                    exit();
                }
                $empresas = $resultado_empresas->fetch_all(MYSQLI_ASSOC);
                $stmt_empresas->close();
            }

            // EQUIPOS A LOS QUE PERTENECE EL USUARIO
            if (isset($sql_equipos)) {
                $stmt_equipos = $db_operario->prepare($sql_equipos);
                if ($stmt_equipos === false) {
                    $db_operario->close();
                    header("location:../index.php?error=equipo");
                    exit();
                }
                // NO ELSE POR EL EXIT ANTERIOR.
                $stmt_equipos->bind_param('s', $login);
                $stmt_equipos->execute();
                $stmt_equipos->bind_result($nombre_equ, $id_equipo);
                $resultado_equipos = $stmt_equipos->get_result();
                // SI NO PERTENECE A NINGUN EQUIPO -> SALIR
                if ($resultado_equipos->num_rows===0){
                    $stmt_equipos->close();
                    $db_operario->close();
                    header("location:../index.php?error=equipo");
                    exit();
                }
                $equipos = $resultado_equipos->fetch_all(MYSQLI_ASSOC);
                $stmt_equipos->close();
                $db_operario->close();
            }

            //region TESTS RESULTADOS DE LAS CONSULTAS
//            echo '<pre>';
//            print('$id_usuario: ' . $id_usuario);
//            print(' $nivel: ' . $nivel);
//            print(' $nombre: ' . $nombre);
//            print(' $apellido1: ' . $apellido1);
//            print(' $apellido2: ' . $apellido2);
//            print(' $estado_usu: ' . $estado_usu);
//            echo('<br>');
//            echo('<br>');
//            print 'Numero de empresas del usuario: ' . count($empresas) . '<br>';
//            foreach ($empresas as $keyEmpresa) {
//                foreach ($keyEmpresa as $nomEmp => $valNomEmp) {
//                    echo "$nomEmp: $valNomEmp\t<br>";
//                }
//            }
//            echo '<br>';
//            echo '<br>';
//            print 'Numero de equipos del usuario: ' . count($equipos) . '<br>';
//            foreach ($equipos as $keyEquipo) {
//                foreach ($keyEquipo as $nomEquipo => $valNomEquipo) {
//                    echo "$nomEquipo: $valNomEquipo\t<br>";
//                }
//            }
//            echo '</pre>';
//            echo 'ok';
            //endregion

            // INICIAMOS LA SESION
            session_start();
            $_SESSION['id_usuario'] = $id_usuario;
            $_SESSION['nivel'] = $nivel;
            $_SESSION['nombre'] = $nombre;
            $_SESSION['apellido1'] = $apellido1;
            $_SESSION['apellido2'] = $apellido2;
            $_SESSION['estado_usu'] = $estado_usu;
            $_SESSION['$empresas'] = $empresas;
            $_SESSION['$equipos'] = $equipos;
            header("location:./novedades.php");
            exit();
        }
        // LOS HASH NO COINCIDEN -> ERROR GENERICO. (no else por el exit)
        header("location:../index.php?error=login");
        exit();
    }
    // NO SE INTRODUJO LOGIN Y/O PASS (no else por el exit)
    header("location:../index.php?error=datos");
    exit();
}
// reCAPTCHA NO SUPERADO (no else por el exit)
header("location:../index.php?error=spam");
exit();