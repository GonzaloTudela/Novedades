<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

session_start();
session_regenerate_id();

//<editor-fold desc="RECOGIDA VARIABLES DE SESSION TRAS LOGIN">
// DATOS RECOGIDOS EN LOGIN - SI NO ESTÁN, ACCESO NO AUTENTIFICADO -> LOGIN.PHP.
if (isset($_SESSION['id_usuario'], $_SESSION['nombre'], $_SESSION['apellido1'], $_SESSION['estado_usu'],
    $_SESSION['$empresas'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nivel = $_SESSION['nivel'];
    $nombre = $_SESSION['nombre'];
    $apellido1 = $_SESSION['apellido1'];
    if (isset($_SESSION['apellido2'])){
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
//</editor-fold>

//<editor-fold desc="CONEXIÓN BD Y CONSULTA DE NOVEDADES">
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');
// Comprobamos si hay error de conexión.
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
if ($nivel >= 0 && $nivel <= 998) {
    if (isset($sql_noticias)) {
        $stmt_novedades = $db_operario->prepare($sql_noticias);
    }
    $stmt_novedades->bind_param('iii', $id_usuario, $id_usuario, $id_usuario);
} elseif ($nivel === 999) {
    if (isset($sql_noticias_admin)) {
        $stmt_novedades = $db_operario->prepare($sql_noticias_admin);
    }
    $stmt_novedades->bind_param('ii', $id_usuario, $id_usuario);
} else {
    die('error nivel usuario');
}
$stmt_novedades->execute();
$res_novedades = $stmt_novedades->get_result();
$novedades = $res_novedades->fetch_all(MYSQLI_ASSOC);
$stmt_novedades->close();
$db_operario->close();
$_SESSION['novedades'] = $novedades;
$_SESSION['webOrigen'] = 'novedades';
//</editor-fold>
// CAMBIO DEL ORDEN DE LAS FECHAS SOLO PARA MOSTRAR EN HTML
$size = count($novedades);
for ($i = 0; $i < $size; $i++) {
    $fi=$novedades[$i]['fecha_inicio'];
    $fi_ok= date("d/m/Y", strtotime($fi));
    $novedades[$i]['fecha_inicio']=$fi_ok;
    if ($novedades[$i]['fecha_fin']!==null){
        $ff=$novedades[$i]['fecha_fin'];
        $ff_ok= date("d/m/Y", strtotime($ff));
        $novedades[$i]['fecha_fin']=$ff_ok;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>Novedades</title>
    <link rel="icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/novedades.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">NOVEDADES</h1>
    <button class="tituloButton"><?php echo file_get_contents('../img/account.svg') ?><span><?=$nombre?></span></button>
    <span class="fecha"><?php echo date('d/m/Y')?></span>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <?php
        escribeNovedades($novedades);
        ?>
    </div>
    <div id="error_container" class="altura1"></div>
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
