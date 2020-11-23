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

//region RECOGIDA VARIABLES ** POST o SESSION ** TRAS ELEGIR NOTICIA
// SI VENIMOS DE HACER CLICK EN UN TITULO EN NOVEDADES O NORMAS, POST TENDRA ID_NOTICIAS Y LO RECOJO,
// EN CASO CONTRARIO ES QUE VENIMOS DE OTRO LUGAR Y RECUPERAMOS LA ULTIMA NOTICIA VISITADA EN LEER.
if (isset($_POST['id_noticia'])) {
    $id_noticia = $_POST['id_noticia'];
    $_SESSION['id_noticia_ultima'] = $id_noticia;
} elseif (isset($_SESSION['id_noticia_ultima'])) {
    $id_noticia = $_SESSION['id_noticia_ultima'];
}
//endregion

//region RECOGIDA VARIABLES SESSION DE NOVEDADES Y NORMAS
// Recuperamos de SESSION las noticias de Novedades y Normas, las unimos en un único array ordenado.
if (isset($_SESSION['novedades'])) {
    $novedades = $_SESSION['novedades'];
    foreach ($novedades as $item) {
        $noticias[$item['id_noticia']] = $item;
    }
}
if (isset($_SESSION['activas'])) {
    $normas = $_SESSION['activas'];
    foreach ($normas as $item) {
        $noticias[$item['id_noticia']] = $item;
    }
}
ksort($noticias);
//endregion
// CAMBIO DEL ORDEN DE LAS FECHAS SOLO PARA MOSTRAR EN HTML
$noticias_R = $noticias;
$fi = $noticias_R[$id_noticia]['fecha_inicio'];
$fi_ok = date("d/m/Y", strtotime($fi));
$noticias_R[$id_noticia]['fecha_inicio'] = $fi_ok;
if ($noticias_R[$id_noticia]['fecha_fin'] !== null) {
    $ff = $noticias_R[$id_noticia]['fecha_fin'];
    $ff_ok = date("d/m/Y", strtotime($ff));
    $noticias_R[$id_noticia]['fecha_fin'] = $ff_ok;
}


//region FORMATEO DE LOS DATOS PARA HTML
// RECORTAMOS EL TIMESTAMP Y GUARDAMOS PARA SU USO EN HTML
$hora = substr($noticias[$id_noticia]['timestamp_not'], -5);
$fecha = substr($noticias[$id_noticia]['timestamp_not'], 0, 10);

// PREPARAMOS LA FECHA FIN DEPENDIENDO DE SU VALOR DE NOTICIAS_R (fechas en formato españa).
if (empty($noticias_R[$id_noticia]['fecha_fin'])) {
    $fechaFin = 'indefinida';
} else {
    $fechaFin = $noticias_R[$id_noticia]['fecha_fin'];
}
//endregion

//region LOGICA PARA SABER CUAL ES LA PAGINA DE ORIGEN.
// SI VENIMOS DE NOVEDADES O ACTIVAS PREPARAMOS URL PARA EL DESTINO DEL BOTON "LEER MAS TARDE"
if ($_SESSION['webOrigen'] === 'novedades') {
    $urlOrigen = './novedades.php';
} elseif ($_SESSION['webOrigen'] === 'activas') {
    $urlOrigen = './activas.php';
} else {
    $urlOrigen = './novedades.php'; // POR DEFECTO VOLVEREMOS A NOVEDADES.
}
//endregion

//region CONSULTA MSQLi SI HEMOS LEIDO LA NOTICIA QUE ESTAMOS LEYENDO.
// CONFIGURACIÓN MYSQLi
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
$db_operario->set_charset('utf8mb4');

// SI HUBO ERROR EN LA CONEXIÓN SALIMOS
if (mysqli_connect_errno()) {
    header("location:../index.php?error=mysql");
    exit();
}

// CONSULTA SI HE LEIDO LA NOTICIA
if (isset($sql_leida)) {
    $stmt_leida = $db_operario->prepare($sql_leida);
    // SI HAY ERRORES EN SENTENCIA SQL
    if ($stmt_leida === false) {
        $db_operario->close();
        header("location:../index.php?error=indefinido");
        exit();
    }
    // NO ELSE POR EL EXIT ANTERIOR.
    $stmt_leida->bind_param('ii', $id_noticia, $id_usuario);
    $stmt_leida->execute();
    $stmt_leida->bind_result($id_leida, $id_lector);
    $resultado_leida = $stmt_leida->fetch();
    // SI LA NOTICIA SE HA LEIDO EL RESULTADO SERA NULL -> SALIR
    if ($resultado_leida === null) {
        $leida = false;
    } else {
        $leida = true;
    }
    $stmt_leida->close();
    $db_operario->close();
}
//endregion

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
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">DATOS DE LA NOTICIA</h1>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <div class="titulo center altura1">
            <p class="txt1 fs1" style="text-align: center"><?= $noticias[$id_noticia]['titulo'] ?></p>
        </div>
        <div class="fechaini center altura1">
            <p class="txt1 fs1">Desde: <?= $noticias_R[$id_noticia]['fecha_inicio'] ?>
            </p>
        </div>
        <div class="fechafin center altura1">
            <p class="txt1 fs1 ">Hasta: <?= $fechaFin ?></p>
        </div>
        <div class="contenido justified altura1">
            <p class="txt2 fs1" style="white-space: pre-wrap"><?= $noticias[$id_noticia]['cuerpo'] ?></p>
        </div>
        <div class="autor center altura1">
            <p class="txt2 fs1"><?= $noticias[$id_noticia]['nombre'] . ' ' . $noticias[$id_noticia]['apellido1'] ?>
                el <?= $fecha ?> a las <?= $hora ?></p>
        </div>
        <?PHP
        //region SI LA NOTICIA ESTA LEIDA MOSTRAR VOLVER
        if ($leida === true) {
            echo <<< _HTML
        <form class="noleer webButton" action="$urlOrigen" method="post">
            <input type="hidden" name="id_noticia" value="$id_noticia">
            <input type="submit" id="mastarde" class="webButton txt-r2 fs1" value="VOLVER">
        </form>
        _HTML;
        } elseif ($leida === false) {
            echo <<< _HTML
        <form class="noleer webButton" action="$urlOrigen" method="post">
            <input type="hidden" name="id_noticia" value="$id_noticia">
            <input type="submit" id="mastarde" class="webButton txt-r2 fs1" value="LEER MAS TARDE">
        </form>
        _HTML;
        } else {
            exit('No supe si la noticia estaba leida!');
        }
        //endregion

        //region SI LA NOTICIA ESTA LEIDA MOSTRAR YA LEIDA
        if ($leida === true) {
            echo <<< _HTML
        <form class="leer webButton">
            <input type="submit" id="leer" class="webButton txt-r-off fs1" value="YA LEÍDA" disabled>
        </form>
        _HTML;
        } elseif ($leida === false) {
            echo <<< _HTML
        <form class="leer webButton" action="leerLogic.php" method="post">
            <input type="hidden" name="id_noticia" value="$id_noticia">
            <input type="submit" id="leer" class="webButton txt-r2 fs1" value="CONFIRMAR LECTURA">
        </form>
        _HTML;
        } else {
            exit('No supe si la noticia estaba leida!');
        }
        //endregion
        ?>
        <form class="actualizar webButton" action="actualizar.php" method="post">
            <input type="hidden" name="id_noticia" value="<?= $id_noticia ?>">
            <input type="submit" id="actualizar" class="webButton txt-r2 fs1" value="ACTUALIZAR">
        </form>
        <form class="caduca webButton" action="finalizarLogic.php" method="post">
            <input type="hidden" name="id_noticia" value="<?= $id_noticia ?>">
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