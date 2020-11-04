<?php
include ("librerias/antisqli.php");
// Recupero sesion y datos.
session_start();
$login = antiSQLi($_SESSION["login"]);
$pass = antiSQLi($_POST["pass"]);
$cpass = antiSQLi($_POST["cpass"]);
// Si la pass en los campos es diferente...
if ($pass != $cpass) {
    // Volvemos con error 0
    header("location:datos.php?error=mismatch");
} else { // Si las pass son iguales...
    // Guardamos la conexion en $intranet
    $intranet = new mysqli("localhost", "root", "0703", "intranet");
    // Definicion de las Consultas SQL:
    // Actualiza pass en tabla usuarios
    $up_usuarios = "UPDATE usuarios SET pass = AES_ENCRYPT('$pass','gonzalotudelachavero') WHERE usuarios.login = '$login';";
    // Actualiza pass en tabla historico
    $up_historico = "insert into historico(login,pass) values('$login',AES_ENCRYPT('$pass','gonzalotudelachavero'));";
    // Comprueba si se existe login y pass en historico.
    $test_pass = "select count(*) as npass from historico where login='$login' and pass=AES_ENCRYPT('$pass','gonzalotudelachavero')";

    // Consulto si existe el login y pass...
    $raw_test_pass=$intranet->query($test_pass);
    $r_test_pass=$raw_test_pass->fetch_assoc();
    // ... si existe envio a datos.php con error
    if ($r_test_pass["npass"] == 1) {
        $intranet->close();
        header("location:datos.php?error=rpass");
    } else { // ... si no existe actualizo las tablas.
        //Ejecucion consultas si la pass no estaba en el historico.
        $intranet->query($up_usuarios);
        $intranet->query($up_historico);
        $intranet->close();
        header("location:datos.php?status=ok");
    }
}
