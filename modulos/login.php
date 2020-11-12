<?php
require_once("../librerias/db_operario.php");
$captcha=null;
$responseKeys=null;
if(isset($_POST['g-recaptcha-response'])){
    $captcha=$_POST['g-recaptcha-response'];
}
if(!$captcha){
    header("location:../index.php?error=captcha");
}
$secretKey = "6LciBd8ZAAAAAKBw1fbLuK4vV8SkSJxwgMaBSLEJ";
$ip = $_SERVER['REMOTE_ADDR'];

// post request to server
$url =  'https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha);
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
//        // Comprobamos si hay error de conexión.
        if (mysqli_connect_errno()) {
            header("location:../index.php?error=mysql");
        } else {
            //todo eliminar el escapado de login y pass tras comprobar que prepare evita SQLi.
            //!Ojo a los nombres de las variables recogidas y preparadas!
            $login = $db_operario->real_escape_string($raw_login);
            $pass = $db_operario->real_escape_string($raw_pass);
            //Consulta para obtener todos los usuarios
            $sql_login = "select password from usuarios where usuario=?";
            $test_login = $db_operario->prepare($sql_login);
            $test_login->bind_param('s', $login);
            $test_login->execute();
            $test_login->bind_result($resultado);
            $test_login->fetch();
            // Verificación de la pass contra el hash en la BD.
            if (password_verify($pass, $resultado)) {
                $test_login->close();
                $db_operario->close();
//                session_start();
                header("location:./novedades.php");
            } else {
                // Usuario o contraseña incorrectos.
                header("location:../index.php?error=login");
            }
        }
    } else {
//         POST login y pass vacios.
        header("location:../index.php?error=datos");
    }
} else {
//     Captcha incorrecto
    header("location:../index.php?error=spam");
}


