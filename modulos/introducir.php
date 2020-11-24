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

//region LOGICA PARA SABER CUAL ES LA PAGINA DE ORIGEN.
// SI VENIMOS DE NOVEDADES O ACTIVAS PREPARAMOS URL PARA EL DESTINO DEL BOTON "LEER MAS TARDE"
if ($_SESSION['webOrigen'] === 'novedades') {
    $urlOrigen = './novedades.php';
} elseif ($_SESSION['webOrigen'] === 'activas') {
    $urlOrigen = './activas.php';
} else {
    $urlOrigen = './novedades.php';
}
//endregion

//region CONEXIÓN CON LA BASE DE DATOS.
// CONFIGURACIÓN MYSQLi
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');

// SI HUBO ERROR EN LA CONEXIÓN SALIMOS
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
    exit();
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
    <link rel="stylesheet" href="../css/introducir.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">INTRODUCIR NOTICIA</h1>
</header>
<main class="altura0">
    <form id="introducir" method="post" action="introducirLogic.php"></form>
    <form id="cancelar" method="post" action="introducirLogic.php"></form>
    <div class="mainGrid altura0">
        <div class="titulo center altura1">
            <label for="titulo"></label>
            <input class="txt1 fs1" type="text" name="titulo" id="titulo" form="introducir"
                   placeholder="Introduce un título. (obligatorio)" maxlength="64" required>
        </div>
        <div class="fechaini center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Fecha Inicio</legend>
                <label class="txt1 fs1" for="fecha_ini"></label>
                <input class="center txt1 fs2" type="date" name="fecha_ini" id="fecha_ini" form="introducir"
                       placeholder="Desde: (obligatorio)">
            </fieldset>
        </div>
        <div class="fechafin center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Fecha Fin</legend>
                <label class="txt1 fs1" for="fecha_fin"></label>
                <input class="center txt1 fs2" type="date" name="fecha_fin" id="fecha_fin" form="introducir"
                       placeholder="Hasta:">
            </fieldset>

        </div>
        <div class="contenido altura1">
            <label for="cuerpo"></label>
            <textarea class="txt2 fs1" name="cuerpo" id="cuerpo" maxlength="4096" form="introducir"
                      placeholder="Texto de la noticia. (obligatorio)" required></textarea>
        </div>
        <div class="equipo center altura1">
            <fieldset class="center txt3 fs2">
                <legend>Equipos de trabajo</legend>
            <label for="equipos" class="txt1 fs1"></label>
            <select class="altura1 txt2 fs1" name="equipos" id="equipos" form="introducir" required>
                <?php
                foreach ($equipos as $equipo) {
                    echo '<option class="txt1 fs1"
                    value="' . htmlentities($equipo['id_equipo']) . '">' . htmlentities($equipo['nombre_equ']) . '
                    </option>';
                }
                ?>
                <option value="todos">Todos</option>
            </select>
            </fieldset>
        </div>
        <div>
            <input type="hidden" name="cancelar" form="cancelar" value="<?= $urlOrigen ?>">
            <input type="submit" id="btn_cancelar" class="webButton txt-r2 fs1" form="cancelar" value="CANCELAR">
        </div>
        <div class="introducir webButton">
            <input type="hidden" name="enviar" form="introducir" value="<?= $urlOrigen ?>">
            <input type="submit" id="btn_enviar" class="webButton txt-r2 fs1" form="introducir" value="ENVIAR">
        </div>
    </div>
    <!--        <div id="error_container" class="altura1"></div>-->
</main>
<footer id="mainMenu" class="sombra0f">
    <?php
    printMenu();
    ?>
</footer>
</body>
</html>
<script>
    botonSalir();
    validarIntroducir();
</script>