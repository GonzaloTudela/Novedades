<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

session_start();

//<editor-fold desc="RECOGIDA VARIABLES DE SESSION TRAS LOGIN">
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
    header("location:login.php");
}
//</editor-fold>

//<editor-fold desc="RECOGIDA VARIABLES ** POST o SESSION ** TRAS ELEGIR NOTICIA">
// SI VENIMOS DE HACER CLICK EN UN TITULO EN NOVEDADES O NORMAS, POST TENDRA ID_NOTICIAS Y LO RECOJO,
// EN CASO CONTRARIO ES QUE VENIMOS DE OTRO LUGAR Y RECUPERAMOS LA ULTIMA NOTICIA VISITADA EN LEER.
if (isset($_POST['id_noticia'])) {
    $id_noticia = $_POST['id_noticia'];
    $_SESSION['id_noticia_ultima'] = $id_noticia;
} elseif (isset($_SESSION['id_noticia_ultima'])) {
    $id_noticia = $_SESSION['id_noticia_ultima'];
}
//</editor-fold>

//<editor-fold desc="RECOGIDA VARIABLES SESSION DE NOVEDADES Y NORMAS.">
// Recuperamos de SESSION las noticias de Novedades y Normas, las unimos en un único array ordenado.
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
//</editor-fold>

// RECORTAMOS EL TIMESTAMP Y GUARDAMOS PARA SU USO EN HTML
$hora = substr($noticias[$id_noticia]['timestamp_not'], -5);
$fecha = substr($noticias[$id_noticia]['timestamp_not'], 1, 7);

// SI VENIMOS DE NOVEDADES O NORMAS PREPARAMOS URL PARA EL DESTINO DEL BOTON "LEER MAS TARDE"
if ($_SESSION['webOrigen'] === 'novedades') {
    $urlTarde = './novedades.php';
} elseif ($_SESSION['webOrigen'] === 'normas') {
    $urlTarde = './normas.php';
}

// PREPARAMOS LA FECHA FIN DEPENDIENDO DE SU VALOR
if (empty($noticias[$id_noticia]['fecha_fin'])) {
    $fechaFin = 'indefinida';
} else {
    $fechaFin = $noticias[$id_noticia]['fecha_fin'];
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
    <link rel="stylesheet" href="../css/leer.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script type="text/javascript" src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">LEER</h1>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <div class="titulo center altura1">
            <p class="txt1 fs1" style="text-align: center"><?= $noticias[$id_noticia]['titulo'] ?></p>
        </div>
        <div class="fechaini center altura1">
            <p class="txt1 fs1"><?= $noticias[$id_noticia]['fecha_inicio'] ?></p>
        </div>
        <div class="fechafin center altura1">
            <p class="txt1 fs1 "><?= $fechaFin ?></p>
        </div>
        <div class="contenido justified altura1">
            <p class="txt2 fs1" style="white-space: pre-wrap"><?= $noticias[$id_noticia]['cuerpo'] ?></p>
        </div>
        <div class="autor center altura1">
            <p class="txt2 fs1"><?= $noticias[$id_noticia]['nombre'] . ' ' . $noticias[$id_noticia]['apellido1'] ?>
                el <?= $fecha ?> a las <?= $hora ?></p>
        </div>
        <form class="noleer webButton" action="<?= $urlTarde ?>" method="post">
            <input type="hidden" name="id_noticia" value="<?=$id_noticia?>">
            <input type="submit" id="mastarde" class="webButton txt-r2 fs1" value="LEER MAS TARDE">
        </form>
        <form class="leer webButton" action="leerLogic.php" method="post">
            <input type="hidden" name="id_noticia" value="<?=$id_noticia?>">
            <input type="submit" id="leer" class="webButton txt-r2 fs1" value="CONFIRMAR LECTURA">
        </form>
        <form class="actualizar webButton" action="actualizarLogic.php" method="post">
            <input type="hidden" name="id_noticia" value="<?=$id_noticia?>">
            <input type="submit" id="actualizar" class="webButton txt-r2 fs1" value="ACTUALIZAR">
        </form>
        <form class="caduca webButton" action="finalizarLogic.php" method="post">
            <input type="hidden" name="id_noticia" value="<?=$id_noticia?>">
            <input type="submit" id="caduca" class="webButton txt-r2 fs1" value="FINALIZAR NOTICIA">
        </form>
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
</script>