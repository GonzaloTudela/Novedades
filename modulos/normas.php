<?php
require_once('../librerias/menu.php');
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

//<editor-fold desc="Variables y lógica de session.">
session_start();
// DATOS RECOGIDOS EN LOGIN.
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
    header("location:../index.php");
}
//</editor-fold>

//<editor-fold desc="Conexion BD y recuperacion">
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');
// Comprobamos si hay error de conexión.
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
if ($nivel >= 0 && $nivel <= 998) {
    if (isset($sql_novedades)) {
        $stmt_normas = $db_operario->prepare($sql_normas);
    }
    $stmt_normas->bind_param('iii', $id_usuario, $id_usuario, $id_usuario);
} elseif ($nivel === 999) {
    if (isset($sql_novedades_admin)) {
        $stmt_normas = $db_operario->prepare($sql_normas_admin);
    }
    $stmt_normas->bind_param('ii', $id_usuario, $id_usuario);
} else {
    die('error nivel usuario');
}
$stmt_normas->execute();
$res_novedades = $stmt_normas->get_result();
$normas = $res_novedades->fetch_all(MYSQLI_ASSOC);
$stmt_normas->close();
$_SESSION['normas'] = $normas;
//</editor-fold>
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
    <link rel="stylesheet" href="../css/novedades.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js" async></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">NORMAS</h1>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <?php
        escribeNovedades($normas);
        ?>
    </div>
    <div id="error_container" class="altura1"></div>
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
</script>