<?php
include_once "./librerias/db_operario.php";
include_once("./librerias/antisqli.php");
require_once("./librerias/recaptchalib.php");
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

$resp = json_decode($res, true);

//REVISAMOS EL RESULTADO
if ($resp['success']) {
    //Captcha correcto
    if (isset($_POST["login"]) && isset($_POST["pass"])) {
        $login = antiSQLi(htmlspecialchars($_POST['login']));
        $pass = antiSQLi(htmlspecialchars($_POST['pass']));
        //echo "<h2>Conexión con la Base de Datos.</h2>";
        $db_operario = new mysqli("localhost", "gonza_currito","NovedadesCurrito!", "gonza_novedades");
        //Consulta para obtener todos los usuarios
        $test_pass = "select count(*) as num from usuarios where login='$login' and pass=AES_ENCRYPT('$pass','gonzalotudelachavero')";
        //echo $test_pass = "select count(*) as num from usuarios where login='$login' and pass='$pass'";

        //Ejecutamos la consulta SQL
        $raw_test_pass = $db_operario->query($test_pass);
        $r_test_pass = $raw_test_pass->fetch_assoc();

        // Si la columna num es 1 hay un login y pass indicado en alguna fila.
        if ($r_test_pass["num"] == 1) {
            session_start();
            $_SESSION['login'] = $login;
            $raw_test_pass->close(); // Cerramos
            $db_operario->close(); // Cerramos la sesion en la base de datos.
            header("location:datos.php");
        } else {
            header("location:index.php?error=login");
        }
    } else {
        header("location:./index.php");
    }
} else {
    //Captcha incorrecto
    header("location:index.php?error=captcha");
}
?>

