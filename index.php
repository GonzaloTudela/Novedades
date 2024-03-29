<?php
require_once "librerias/errores.php";
require_once "librerias/funcionesPHP.php";
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
//debugFor("79.152.7.228");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>Novedades</title>
    <link rel="icon" href="img/favicon.png">
    <link rel="stylesheet" href="css/index.css">
    <link rel="manifest" href="manifest.json">
    <script src="https://www.google.com/recaptcha/api.js?onload=recaptchaLoaded" async defer></script>
    <script src="librerias/funcionesJS.js"></script>
</head>
<body>
<div id="fondo">
    <form id="login_form" method="POST" action="modulos/login.php">
        <h1 id="titulo">NOVEDADES</h1>
        <pre id="info">Proyecto CFGS ASIR
Gonzalo Tudela Chavero, 2020
<a id="link" href="http://www.centrodonbosco.es">www.centrodonbosco.es</a></pre>
        <label for="login"></label>
        <input type="text" id="login" name="login" placeholder="Usuario"><br>
        <label for="pass"></label>
        <input type="password" id="pass" name="pass" placeholder="Contraseña"><br>
        <button>ENTRAR</button>
        <div id="error_container"><?php error_get() ?></div>
        <div class="g-recaptcha"
             data-sitekey="6LciBd8ZAAAAAGIs59aPs5dgFLoCB72NUmp5CXY-"
             data-badge="inline"
             data-theme="light">
        </div>
        <div id="loading">
            <h2>...Loading reCAPTCHAv2...</h2>
        </div>
    </form>
</div>
<script>
    window.recaptchaLoaded = function () {
        let loading = document.getElementById("loading")
        loading.style.transition = '2s';
        loading.style.opacity = '0';
        loading.remove();
        let login = document.getElementById('login');
        let pass = document.getElementById('pass');
        login.value = '';
        pass.value = '';
    }
</script>
</body>
</html>