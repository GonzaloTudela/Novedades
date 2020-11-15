<?php
require_once('../librerias/menu.php');
require_once ('../librerias/consultas.php');
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

$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');
// Comprobamos si hay error de conexión.
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
if ($nivel>=0 && $nivel<=998){
    $stmt_novedades = $db_operario->prepare($sql_novedades);
    $stmt_novedades->bind_param('iii', $id_usuario, $id_usuario, $id_usuario);
} elseif ($nivel===999){
    $stmt_novedades = $db_operario->prepare($sql_novedades_admin);
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
    <link rel="stylesheet" href="../css/style.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js" async></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0">NOVEDADES</h1>
</header>
<main>
    <div class="card altura1 sombra1">
        <?php
        foreach ($novedades as $keyNovedades) {
            $escritura=substr($keyNovedades['timestamp_not'],0,-3);
            echo '<div class="card altura2 sombra2">';
//            echo '<p class="card txt3">Autor:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['id_usuario'].'</p>';
//            echo '<p class="card txt2">Titulo:</p>';
            echo '<p class="titulo txt2">' .$keyNovedades['titulo'].'</p>';
//            echo '<p class="card txt3">Cuerpo:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['cuerpo'].'</p>';
//            echo '<p class="card txt3">Fecha Inicio:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['fecha_inicio'].'</p>';
//            echo '<p class="card txt3">Fecha Fin:</p>';
//            echo '<p class="card txt3">' .$keyNovedades['fecha_fin'].'</p>';
//            echo '<p class="card txt3">Escrita el:</p>';
            echo '<p class="minifecha txt3">' .$escritura.'</p>';
//            echo '<p class="txt1">Version:</p><br>';
//            printf ($keyNovedades['num_version']);
            echo '</div>';
        }
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