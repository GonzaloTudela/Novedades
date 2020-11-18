<?php
require_once('../librerias/menu.php');
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
debugFor("79.152.7.228");
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
    $_SESSION[]=array();
    header("location:../index.php");
}

$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');
// Comprobamos si hay error de conexión.
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
if ($nivel >= 0 && $nivel <= 998) {
    if (isset($sql_novedades)) {
        $stmt_novedades = $db_operario->prepare($sql_novedades);
    }
    $stmt_novedades->bind_param('iii', $id_usuario, $id_usuario, $id_usuario);
} elseif ($nivel === 999) {
    if (isset($sql_novedades_admin)) {
        $stmt_novedades = $db_operario->prepare($sql_novedades_admin);
    }
    $stmt_novedades->bind_param('ii', $id_usuario, $id_usuario);
} else {
    die('error nivel usuario');
}
$stmt_novedades->execute();
$res_novedades = $stmt_novedades->get_result();
$novedades = $res_novedades->fetch_all(MYSQLI_ASSOC);
$stmt_novedades->close();

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
    <h1 class="txt0">NOVEDADES</h1>
</header>
<main>
    <div class="card altura1 sombra1">
        <?php
        escribeNovedades($novedades);
        ?>
    </div>
    <div class="msg">
        <?php echo file_get_contents("../img/robot.svg") ?>
        <p>SIN NOVEDADES</p>
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
    botonSalir();
</script>