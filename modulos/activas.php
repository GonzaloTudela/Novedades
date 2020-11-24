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

//region CONEXIÓN BD Y CONSULTA DE NORMAS
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');
// Comprobamos si hay error de conexión.
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
// PREPARA SQL SI USUARIO NORMAL
if ($nivel >= 0 && $nivel <= 998) {
    if (isset($sql_activas)) {
        $stmt_activas = $db_operario->prepare($sql_activas);
        if ($stmt_activas === false) {
            $db_operario->close();
            exit('Error sentencia SQL');
        }
        $stmt_activas->bind_param('i', $id_usuario);
        $stmt_activas->execute();
        $res_activas = $stmt_activas->get_result();
        $activas = $res_activas->fetch_all(MYSQLI_ASSOC);
        $stmt_activas->close();
        $db_operario->close();
    }
} // PREPARA SQL SI USUARIO ADMIN
elseif ($nivel === 999) {
    if (isset($sql_activas_admin)) {
        $stmt_activas = $db_operario->prepare($sql_activas_admin);
        if ($stmt_activas === false) {
            $db_operario->close();
            exit('Error sentencia SQL');
        }
        $stmt_activas->execute();
        $res_activas = $stmt_activas->get_result();
        $activas = $res_activas->fetch_all(MYSQLI_ASSOC);
        $stmt_activas->close();
        $db_operario->close();
    }
} else {
    die('error nivel usuario');
}
//endregion

// AÑADIRMOS A SESSION LAS NORMAS --- ANTES DE CAMBIAR EL ORDEN DE LAS FECHAS ---;
$_SESSION['activas'] = $activas;
$_SESSION['webOrigen'] = 'activas';

// CAMBIO DEL ORDEN DE LAS FECHAS SOLO PARA MOSTRAR EN HTML
$size = count($activas);
for ($i = 0; $i < $size; $i++) {
    $fi = $activas[$i]['fecha_inicio'];
    $fi_ok = date("d/m/Y", strtotime($fi));
    $activas[$i]['fecha_inicio'] = $fi_ok;
    if ($activas[$i]['fecha_fin'] !== null) {
        $ff = $activas[$i]['fecha_fin'];
        $ff_ok = date("d/m/Y", strtotime($ff));
        $activas[$i]['fecha_fin'] = $ff_ok;
    }
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
    <link rel="stylesheet" href="../css/novedades.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">NOTICIAS ACTIVAS</h1>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <?php
        escribeNovedades($activas);
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