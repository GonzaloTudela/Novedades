<?php
include_once "../librerias/db_operario.php";
//include_once("../librerias/antisqli.php");
require_once("../librerias/recaptchalib.php");
$google_url = "https://www.google.com/recaptcha/api/siteverify";
$str = $_POST['g-recaptcha-response'];
$secret = '6LciBd8ZAAAAAKBw1fbLuK4vV8SkSJxwgMaBSLEJ';
$ip = $_SERVER['REMOTE_ADDR'];
$url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;

//INICIAMOS cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);
curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
$res = curl_exec($curl);
curl_close($curl);
$cheat = 1;
$resp = json_decode($res, true);

//REVISAMOS EL RESULTADO
// Si el captcha responde SUCCESS (correcto)...
if ($resp['success'] || $cheat=1) {
//if ($cheat = 1) {
    // Si recibimos login y pass...
    if (isset($_POST["login"], $_POST["pass"]) && $_POST["login"]!=="" && !$_POST["pass"]!=="") {
        $raw_login = ($_POST['login']);
        $raw_pass = ($_POST['pass']);
        // Creamos la conexión.
        $db_operario = new mysqli($hn, $un, $pw, $db);
        // Comprobamos si hay error de conexión.
        if (mysqli_connect_errno()) {
            header('location:../index.php?error=mysql');
        } else {
            /*          todo eliminar el escapado de login y pass tras comprobar que prepare evita SQLi.
                        todo !Ojo a los nombres de las variables recogidas y preparadas!*/
            $login = $db_operario->real_escape_string($raw_login);
            $pass = $db_operario->real_escape_string($raw_pass);
            //Consulta para obtener todos los usuarios
            $sql_login = "select password from usuarios where usuario=?";
            $test_login = $db_operario->prepare($sql_login);
            $test_login->bind_param('s', $login);
            $test_login->execute();
            $test_login->bind_result($resultado);
            $test_login->fetch();
            if (password_verify($pass, $resultado)) {
                // Comprobación en desarrollo.
                //printf("El usuario $login y la contraseña $pass coinciden con el hash:\n%s", $resultado);
                $test_login->close();
                $db_operario->close();
                header("location:novedades.php");
            } else {
                header("location:../index.php?error=login");
            }
        }
    } else {
        header("location:../index.php?error=datos");
    }
} else {
    //Captcha incorrecto
    header("location:../index.php?error=captcha");
}
?>

