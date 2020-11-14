<?php
require_once('../librerias/menu.php');
session_start();
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
    header("location:../index.php");
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Novedades</title>
    <link rel="icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/general.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js" async></script>
</head>
<body>
<header class="sombra0">
    <h1 class="txt0">NOVEDADES</h1>
</header>
<main>
    <div class="card altura0 sombra0">
        <div class="card altura1 sombra1">
            <h2 class="txt1">Texto 1</h2>
            <p class="txt2">Nombre: <?= $nombre ?></p>
            <p class="txt3">Apellido 1:<?= $apellido1 ?></p>
            <p class="txt-off">Apellido 2:<?= $apellido2 ?></p>
            <p class="txt-r0">Estado: <?= $estado_usu ?></p>
            <p class="txt-r1">Nivel: <?= $nivel ?></p>
            <p class="txt-r2">Resalte2</p>
            <p class="txt-r3">Resalte3</p>
            <p class="txt-r-off">Resalte disabled</p>
        </div>
    </div>
    <div class="card altura0 sombra0">
        <div class="card altura1 sombra1">
            <h2 class="txt1">Texto 1</h2>
            <p class="txt2">Nombre: <?= $nombre ?></p>
            <p class="txt3">Apellido 1:<?= $apellido1 ?></p>
            <p class="txt-off">Apellido 2:<?= $apellido2 ?></p>
            <p class="txt-r0">Estado: <?= $estado_usu ?></p>
            <p class="txt-r1">Nivel: <?= $nivel ?></p>
            <p class="txt-r2">Resalte2</p>
            <p class="txt-r3">Resalte3</p>
            <p class="txt-r-off">Resalte disabled</p>
        </div>
    </div>
    <div class="card altura0 sombra0">
        <div class="card altura1 sombra1">
            <h2 class="txt1">Texto 1</h2>
            <p class="txt2">Nombre: <?= $nombre ?></p>
            <p class="txt3">Apellido 1:<?= $apellido1 ?></p>
            <p class="txt-off">Apellido 2:<?= $apellido2 ?></p>
            <p class="txt-r0">Estado: <?= $estado_usu ?></p>
            <p class="txt-r1">Nivel: <?= $nivel ?></p>
            <p class="txt-r2">Resalte2</p>
            <p class="txt-r3">Resalte3</p>
            <p class="txt-r-off">Resalte disabled</p>
        </div>
    </div>
    <div class="card altura0 sombra0">
        <div class="card altura1 sombra1">
            <h2 class="txt1">Texto 1</h2>
            <p class="txt2">Nombre: <?= $nombre ?></p>
            <p class="txt3">Apellido 1:<?= $apellido1 ?></p>
            <p class="txt-off">Apellido 2:<?= $apellido2 ?></p>
            <p class="txt-r0">Estado: <?= $estado_usu ?></p>
            <p class="txt-r1">Nivel: <?= $nivel ?></p>
            <p class="txt-r2">Resalte2</p>
            <p class="txt-r3">Resalte3</p>
            <p class="txt-r-off">Resalte disabled</p>
        </div>
    </div>
    <div id="error_container altura1"></div>
</main>
<footer id="mainMenu" class="sombra0f">
    <?php
    printMenu();
    ?>
</footer>
</body>
</html>
<script>

/*
    let botones=document.getElementById('mainMenu').getElementsByTagName('button');
    let total_bot=botones.length;
    botones.item(total_bot).innerHTML=('')
    for (let i = 0; i < botones.length; i++) {
        botones.item(6)
    }
*/


</script>