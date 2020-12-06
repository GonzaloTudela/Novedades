<?php
session_start();
require_once('../librerias/errores.php');
require_once('../librerias/consultas.php');
require_once('../librerias/funcionesPHP.php');
//debugFor("79.152.7.228");

//region DATOS RECOGIDOS EN LOGIN - SI NO ESTÁN, ACCESO NO AUTENTIFICADO -> LOGIN.PHP.
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


// CONECTAMOS CON LA BD.
$db_operario = new mysqli('hl793.dinaserver.com', 'gonza_currito', 'NovedadesCurrito!',
    'gonza_novedades');
// Comprobamos si hay error de conexión.
if ($db_operario->connect_errno) {
    header("location:../index.php?error=mysql");
}
$db_operario->set_charset('utf8mb4');

// CONSULTAMOS DATOS DEL USUARIO PARA VERIFICAR.
if (isset($sql_test_usuario)) {
    $stmt_test_usuario = $db_operario->prepare($sql_test_usuario);
    if ($stmt_test_usuario === false) {
        $db_operario->close();
        exit("No se pudo preparar SQL test_usuario");
    }
    // LEER DATOS USUARIO
    $stmt_test_usuario->bind_param('i', $id_usuario);
    $stmt_test_usuario->execute();
    $stmt_test_usuario->bind_result($usuario, $email, $pass, $categoria);
    $res_test_usuario = $stmt_test_usuario->fetch();
    if ($res_test_usuario === null) {
        $stmt_test_usuario->close();
        exit("Error en la consulta, no se recuperaron datos");
    }
}
$db_operario->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>Novedades</title>
    <link rel="icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/usuario.css">
    <link rel="stylesheet" href="../css/general-queries.css">
    <script src="../librerias/funcionesJS.js"></script>
</head>
<body id="root">
<header class="sombra0">
    <h1 class="txt0 fs0" style="color:var(--txt-r1)">DATOS PERSONALES</h1>
    <button class="tituloButton"><?php echo file_get_contents('../img/account.svg') ?><span><?=$nombre?></span></button>
    <span class="fecha"><?php echo date('d/m/Y')?></span>
</header>
<main class="altura0">
    <div id="error_container"><?php error_get() ?></div>
    <form id="introducir" method="post" action="usuarioLogic.php"></form>
    <form id="cancelar" method="post" action="usuarioLogic.php"></form>
    <div class="mainGrid altura0">
        <div class="nombre justified altura1">
            <fieldset class="left txt3 fs2">
                <legend>Nombre</legend>
                <p class="fs1"><?= $nombre ?></p>
            </fieldset>
        </div>
        <div class="apellido1 justified altura1">
            <fieldset class="left txt3 fs2">
                <legend>Primer apellido</legend>
                <p class="fs1"><?= $apellido1 ?></p>
            </fieldset>
        </div>
        <div class="apellido2 justified altura1">
            <fieldset class="left txt3 fs2">
                <legend>Segundo apellido</legend>
                <p class="fs1"><?= $apellido2 ?></p>
            </fieldset>
        </div>
        <div class="categoria justified altura1">
            <fieldset class="left txt3 fs2">
                <legend>Categoría</legend>
                <p class="fs1"><?= $categoria ?></p>
            </fieldset>
        </div>
        <div class="usuario justified altura0">
            <fieldset class="altura1 sombra1 left txt1 fs2">
                <legend>Nombre de usuario</legend>
                <label for="usuario"></label>
                <input class="txt1 fs1" type="text" name="usuario" id="usuario" form="introducir" maxlength="32"
                       value="<?= $usuario ?>">
            </fieldset>
        </div>
        <div class="email justified altura0">
            <fieldset class="altura1 sombra1 left txt1 fs2">
                <legend>Correo Electrónico</legend>
                <label for="email"></label>
                <input class="txt1 fs1" type="email" name="email" id="email" form="introducir" maxlength="64"
                       value="<?= $email ?>">
            </fieldset>
        </div>
        <div class="pass center altura0">
            <fieldset class="altura1 sombra1 left txt1 fs2">
                <legend>Nueva contraseña</legend>
                <label for="pass"></label>
                <input class="txt1 fs1" type="password" name="pass" id="pass" form="introducir">
            </fieldset>

        </div>
        <div class="vpass center altura0">
            <fieldset class="altura1 sombra1 left txt1 fs2">
                <legend>Verificar contraseña</legend>
                <label for="vpass"></label>
                <input class="txt1 fs1" type="password" name="vpass" id="vpass" form="introducir">
            </fieldset>
        </div>
        <div class="cancelar justified webButton">
            <input type="hidden" name="cancelar" form="cancelar" value="<?= $urlOrigen ?>">
            <input type="submit" id="btn_cancelar" class="webButton txt-r2 fs1" form="cancelar" value="SALIR">
        </div>
        <div class="actualizar justified webButton">
            <input type="hidden" name="enviar" form="introducir" value="<?= $urlOrigen ?>">
            <input type="submit" id="btn_enviar" class="webButton txt-r2 fs1" form="introducir" value="ACUALIZAR">
        </div>
    </div>
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
