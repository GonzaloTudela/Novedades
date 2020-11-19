<?php
require_once('../librerias/menu.php');
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

//<editor-fold desc="Variables y lógica de session.">
session_start();
if (isset($_POST['id_noticia'])) {
    $id_noticia = $_POST['id_noticia'];
} else {
    header("location:../noticias.php");
}
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

// CONEXIÓN CON LA BASE DE DATOS
//<editor-fold desc="Conexion BD y recuperacion">
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
//$db_operario->set_charset('utf8mb4');
// COMPROBACIÓN ERROR CONEXION BD
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
}
// COMPROBACIÓN NIVEL DEL USUARIO
//if ($nivel >= 0 && $nivel <= 998) {
//    if (isset($sql_novedades)) {
//        $stmt_novedades = $db_operario->prepare($sql_novedades);
//        $stmt_novedades->bind_param('iii', $id_usuario, $id_usuario, $id_usuario);
//    }
//} elseif ($nivel === 999) {
//    if (isset($sql_novedades_admin)) {
//        $stmt_novedades = $db_operario->prepare($sql_novedades_admin);
//        $stmt_novedades->bind_param('ii', $id_usuario, $id_usuario);
//    }
//} else {
//    die('error nivel usuario');
//}

//$stmt_novedades->execute();
//$res_novedades = $stmt_novedades->get_result();
//$novedades = $res_novedades->fetch_all(MYSQLI_ASSOC);
//$stmt_novedades->close();
//</editor-fold>
// Recuperamos las noticias de Novedades y Normas, las unimos en un único array ordenado.
if (isset($_SESSION['novedades'])) {
    $novedades = $_SESSION['novedades'];
    foreach ($novedades as $item) {
        $noticias[$item['id_noticia']] = $item;
    }
}
if (isset($_SESSION['normas'])) {
    $normas = $_SESSION['normas'];
    foreach ($normas as $item) {
        $noticias[$item['id_noticia']] = $item;
    }
}
ksort($noticias);
$hora=substr($noticias[$id_noticia]['timestamp_not'],-5);
$fecha=substr($noticias[$id_noticia]['timestamp_not'],1,7);
debugConsole($hora);
debugConsole($fecha);
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
    <link rel="stylesheet" href="../css/leer.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js" async></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">LEER</h1>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <div class="titulo center altura1">
            <p class="txt1 fs1"><?= $noticias[$id_noticia]['titulo'] ?></p>
        </div>
        <div class="fechaini center altura1">
            <p class="txt1 fs1"><?= $noticias[$id_noticia]['fecha_inicio'] ?></p>
        </div>
        <div class="fechafin center altura1">
            <p class="txt1 fs1 ">
                <?php
                if (empty($noticias[$id_noticia]['fecha_fin'])) {
                    echo 'indefinida';
                } else {
                    echo $noticias[$id_noticia]['fecha_fin'];
                }
                ?>
            </p>
        </div>
        <div class="contenido justified altura1">
            <p class="txt2 fs1"><?= $noticias[$id_noticia]['cuerpo'] ?></p>
        </div>
        <div class="autor center altura1">
                <p class="txt2 fs1"><?= $noticias[$id_noticia]['nombre'] . ' ' . $noticias[$id_noticia]['apellido1'] ?>
                    el <?= $fecha ?> a las <?=$hora?></p>
        </div>
        <div class="noleer center altura1">
            <button><a href="">LEER MAS TARDE</a></button>
        </div>
        <div class="leer center altura1">
            <button><a href="">CONFIRMAR LECTURA</a></button>
        </div>
        <div class="actualizar center altura1">
            <button><a href="">ACTUALIZAR</a></button>
        </div>
        <div class="caduca center altura1">
            <button><a href="">ELIMINAR</a></button>
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
    //let contenedor = document.getElementById('error_container');
    //// Access the array elements
    //let id_noticia = <?//=$id?>//;
    //let noticias = JSON.parse('<?php //echo json_encode($noticias); ?>//');
    //// Display the array elements
    //
    //for (let i = 0; i < noticias.length; i++) {
    //    if (noticias[i]['id_noticia'] === id_noticia) {
    //        contenedor.insert
    //        console.log(noticias[i])
    //    }
    //}
</script>