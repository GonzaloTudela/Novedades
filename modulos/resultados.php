<?php
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
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

// RECOGEMOS DATOS FORMULARIO
if (isset($_GET['fecha_ini'], $_GET['fecha_fin'], $_GET['titulo'], $_GET['cuerpo'], $_GET['autor'])) {
    $getFecha_ini = $_GET['fecha_ini'];
    $getFecha_fin = $_GET['fecha_fin'];
    $getTitulo = $_GET['titulo'];
    $getCuerpo = $_GET['cuerpo'];
    $getAutor = $_GET['autor'];
}

// SI LAS FECHAS ESTAN VACÍAS SUSTITUIMOS POR LA MINIMA Y MAXIMA RESPECTIVAMENTE, EN CASO CONTRARIO RECOGEMOS DATOS.
// FECHAS MINIMAS Y MÁXIMAS EN MADRIADB 1000-01-01 hasta 9999-12-31
empty($getFecha_ini) ? $fecha_ini = '1000-01-01' : $fecha_ini = $getFecha_ini;
empty($getFecha_fin) ? $fecha_fin = '9999-12-31' : $fecha_ini = $getFecha_fin;

// TITULO: COLOCAMOS COMODINES SI VACIO, O FORMATEAMOS LA CADENA SI EL USUARIO HA ESCRITO ALGO.
if (empty($getTitulo)) {
    $titulo = '%';
} else {
    $arrayTitulo = explode(' ', $getTitulo);
    $titulo = '%'.implode('%', $arrayTitulo).'%';
}
// CUERPO: COLOCAMOS COMODINES SI VACIO, O FORMATEAMOS LA CADENA SI EL USUARIO HA ESCRITO ALGO.
if (empty($getCuerpo)) {
    $cuerpo = '%';
} else {
    $arrayCuerpo = explode(' ', $getCuerpo);
    $cuerpo = '%'.implode('%', $arrayCuerpo).'%';
}
// AUTOR: COLOCAMOS COMODINES SI VACIO, O FORMATEAMOS LA CADENA SI EL USUARIO HA ESCRITO ALGO.
if (empty($getAutor)){
    $autor = '%';
} else {
    $arrayAutor = explode(' ', $getAutor, 0);
    $autor = '%'.implode('%', $arrayAutor).'%';
}


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
    if (isset($sql_buscar)) {
        $stmt_buscar = $db_operario->prepare($sql_buscar);
        if ($stmt_buscar === false) {
            $db_operario->close();
            exit('Error sentencia SQL');
        }
        $stmt_buscar->bind_param('sssssi', $fecha_ini, $fecha_fin, $titulo, $cuerpo, $autor, $id_usuario);
        $stmt_buscar->execute();
        $res_buscar = $stmt_buscar->get_result();
        $buscar = $res_buscar->fetch_all(MYSQLI_ASSOC);
        $stmt_buscar->close();
        $db_operario->close();
    }
} // PREPARA SQL SI USUARIO ADMIN
elseif ($nivel === 999) {
    if (isset($sql_buscar_admin)) {
        $stmt_buscar = $db_operario->prepare($sql_buscar_admin);
        if ($stmt_buscar === false) {
            $db_operario->close();
            exit('Error sentencia SQL');
        }
        $stmt_buscar->bind_param('sssss', $fecha_ini, $fecha_fin, $titulo, $cuerpo, $autor);
        $stmt_buscar->execute();
        $res_buscar = $stmt_buscar->get_result();
        $buscar = $res_buscar->fetch_all(MYSQLI_ASSOC);
        $stmt_buscar->close();
        $db_operario->close();
    }
} else {
    die('error nivel usuario');
}
//endregion

// AÑADIRMOS A SESSION LOS RESULTADOS --- ANTES DE CAMBIAR EL ORDEN DE LAS FECHAS ---;
$_SESSION['buscar'] = $buscar;
$_SESSION['webOrigen'] = 'buscar';

// CAMBIO DEL ORDEN DE LAS FECHAS SOLO PARA MOSTRAR EN HTML
$size = count($buscar);
for ($i = 0; $i < $size; $i++) {
    $fi = $buscar[$i]['fecha_inicio'];
    $fi_ok = date("d/m/Y", strtotime($fi));
    $buscar[$i]['fecha_inicio'] = $fi_ok;
    if ($buscar[$i]['fecha_fin'] !== null) {
        $ff = $buscar[$i]['fecha_fin'];
        $ff_ok = date("d/m/Y", strtotime($ff));
        $buscar[$i]['fecha_fin'] = $ff_ok;
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
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">RESULTADOS</h1>
    <button class="tituloButton"><?php echo file_get_contents('../img/account.svg') ?><span><?=$nombre?></span></button>
    <span class="fecha"><?php echo date('d/m/Y')?></span>
</header>
<main class="altura0">
    <div class="mainGrid altura0">
        <?php
        escribeNovedades($buscar);
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