<?php
include ("librerias/antisqli.php");
session_start();
$login = antiSQLi($_SESSION['login']);
//print_r($_SESSION);
$intranet = new mysqli("localhost", "root", "0703", "intranet");
//Consulta para obtener todos los usuarios
$test_login = "select nombre,apellido1,apellido2,telf from usuarios where login='$login'";
//echo $consulta = "select nombre,apellido1,apellido2,telf from usuarios where login='$sesion'";

//Ejecutamos la consulta SQL
$raw_test_login = $intranet->query($test_login);
//Convertimos los datos en un array asociativo.
$r_test_login = $raw_test_login->fetch_assoc();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ECORP INTRANET</title>
    <link rel="stylesheet" href="estilos/datos.css">
</head>
<body>
<div id="fondo">
    <div id="caja">
        <figure>
            <img src="imagenes/E.png" alt="EVIL CORP">
            <figcaption><h1>E CORP</h1></figcaption>
        </figure>
        <div id="datos">
            <h2>DATOS DEL USUARIO</h2>
            <table>
                <tr>
                    <td>Nombre:</td>
                    <td><?= $r_test_login["nombre"] ?></td>
                </tr>
                <tr>
                    <td>Apellido:</td>
                    <td><?= $r_test_login["apellido1"] ?></td>
                </tr>
                <tr>
                    <td>Segundo Apellido:</td>
                    <td><?= $r_test_login["apellido2"] ?></td>
                </tr>
                <tr>
                    <td>Telefono:</td>
                    <td><?= $r_test_login["telf"] ?></td>
                </tr>
            </table>
            <a href="logout.php">EXIT</a>
            <a id="botpass" href="#">PASSWORD</a>
            <br>
            <?php
            if (isset($_GET["error"])) {
                $error = antiSQLi($_GET["error"]);
                if ($error == "rpass") {
                    $lista = "select AES_DECRYPT(pass,'gonzalotudelachavero') as lista from historico where login='$login';";
                    $raw_lista = $intranet->query($lista); //res
                    $r_lista = $raw_lista->fetch_assoc();
                    echo '<div id="historico">';
                    echo '<div id="cajahistorico">';
                    echo '<h2 class="lpass">HISTORICO PASSWORDS</h2>';
                    while ($r_lista) {
                        echo "<p class='upass'>${r_lista["lista"]}</p>";
                        $r_lista = $raw_lista->fetch_assoc();
                    }
                    $raw_lista->close();
                    echo '<a id="botclose" href="#">CERRAR</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            if (isset($_GET["error"])) {
                $error = antiSQLi($_GET["error"]);
                if ($error == "mismatch") {
                    echo "<p id='error'>PASSWORD NO COINCIDE</p>";
                }
            }
            if (isset($_GET["status"])) {
                $ok = antiSQLi($_GET["status"]);
                if ($ok == "ok") {
                    echo "<p id='error'>PASSWORD ACTUALIZADO</p>";
                }
            }
            ?>
            <div id="capapass">
                <form method="POST" action="nuevopass.php">
                    <label for="pass"></label>
                    <input type="password" id="pass" name="pass" placeholder="NEW PASSWORD">
                    <label for="confpass"></label>
                    <input type="password" id="confpass" name="cpass" placeholder="CONFIRM"><BR>
                    <button id="update">UPDATE</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    let botclose = document.getElementById("botclose");
    let historico = document.getElementById("historico");
    if (historico != null) {
        botclose.addEventListener("click", function (e) {
            historico.remove();
            if (error != null) {
                error.remove();
            }
        })
    } else {
        historico = document.getElementById("historico");
        botclose = document.getElementById("botclose");
    }
    let error = document.getElementById("error");
    let capapass = document.getElementById("capapass");
    let botpass = document.getElementById("botpass");
    botpass.addEventListener("click", function (e) {
        capapass.style.display = "inline-block";
        if (error != null) {
            error.remove();
        } else {
            error = document.getElementById("error");
        }
    });
</script>
</body>
</html>
