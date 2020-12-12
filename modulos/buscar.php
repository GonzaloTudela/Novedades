<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
require_once('../librerias/errores.php');
//debugFor("79.152.7.228");

session_start();

//region RECOGIDA VARIABLES DE SESSION TRAS LOGIN
// DATOS RECOGIDOS EN LOGIN - SI NO ESTÁN, ACCESO NO AUTENTIFICADO -> LOGIN.PHP.
if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['estado_usu'],
    $_SESSION['$empresas'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel'];
    $nombre = $_SESSION['nombre'];
    $apellido1 = $_SESSION['apellido1'];
    if (isset($_SESSION['apellido2'])) {
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>Novedades</title>
    <link rel="icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/buscar.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">BUSCAR NOTICIAS</h1><br>
    <button class="tituloButton"><?php echo file_get_contents('../img/account.svg') ?><span><?=$nombre?></span></button>
    <span class="fecha"><?php echo date('d/m/Y')?></span>
</header>
<main class="altura0">
    <form id="buscar" method="get" action="resultados.php"></form>
    <div class="mainGrid altura0">
        <div class="ayuda">
            <p class="txt1 fs2">Instrucciones:</p>
            <ul>
                <li class="txt1 fs2">Puede utilizar uno o varios parámetros para la búsqueda,
                    si no introduce nada buscará todas las noticias.</li>
                <li class="txt1 fs2">Recuerda, las noticias solo tienen 1 autor.</li>
                <li class="txt1 fs2">Las palabras o letras que introduzcas deben aparecer en el orden que las has introducido.</li>
            </ul>
        </div>
        <div class="fechaini center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Desde</legend>
                <label for="fecha_ini"></label>
                <input class="center txt1 fs1" type="date" name="fecha_ini" id="fecha_ini" form="buscar">
            </fieldset>
        </div>
        <div class="fechafin center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Hasta</legend>
                <label for="fecha_fin"></label>
                <input class="center txt1 fs1" type="date" name="fecha_fin" id="fecha_fin" form="buscar">
            </fieldset>
        </div>
        <div class="titulo center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Titulo</legend>
                <label for="titulo"></label>
                <input class="center txt1 fs1" type="text" name="titulo" id="titulo" form="buscar"
                       placeholder="Palabras clave que aparezcan en el título.">
            </fieldset>
        </div>
        <div class="contenido center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Cuerpo</legend>
                <label for="cuerpo"></label>
                <input class="center txt1 fs1" type="text" name="cuerpo" id="cuerpo" form="buscar"
                       placeholder="Palabras clave que aparezcan en el contenido.">
            </fieldset>
        </div>
        <div class="autor center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Autor</legend>
                <label for="autor"></label>
                <input class="center txt1 fs1" type="text" name="autor" id="autor" form="buscar"
                       placeholder="Búsqueda por autor.">
            </fieldset>
        </div>
        <div class="actualizar webButton buscar">
            <input type="submit" id="buscar" class="webButton txt-r2 fs1" form="buscar" value="BUSCAR">
        </div>
    </div>
    <div id="error_container" class="altura1"><?php error_get() ?></div>
</main>
<footer id="mainMenu" class="sombra0f">
    <?php
    printMenu();
    ?>
</footer>
<script>
    botonSalir();
</script>
</body>
</html>