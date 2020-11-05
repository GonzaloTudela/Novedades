<?php
require_once "librerias/recaptchalib.php"
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Novedades</title>
        <link rel="icon" href="img/favicon.png">
        <link rel="stylesheet" href="css/general.css">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <script type="text/javascript" src="librerias/funcionesJS.js" async></script>
    </head>
    <body>
    <div id="fondo">
        <form id="login_form" method="POST" action="">
            <h1>NOVEDADES</h1>
            <label for="login"></label>
            <input type="text" id="login" name="login" placeholder="Usuario"><br>
            <label for="pass"></label>
            <input type="password" id="pass" name="pass" placeholder="Contraseña"><br>
            <button>ENTRAR</button>
            <div class="g-recaptcha"
                 data-sitekey="6LciBd8ZAAAAAGIs59aPs5dgFLoCB72NUmp5CXY-"
                 data-badge="inline"
                 data-theme="light">
            </div>
        </form>
    </div>
    </body>
    </html>
<?php
include("librerias/antisqli.php");
if (isset($_GET["error"])) {
    $error = antiSQLi($_GET["error"]);
    switch ($error) {
        case 'login':
            $texto = "Usuario o contraseña incorrectos.";
            ?>
            <script>errores("<?=$texto?>")</script>
            <?php
            break;
        case "captcha":
            $texto = "Por favor, verifique el captcha.";
            ?>
            <script>errores("<?=$texto?>")</script>
            <?php
            break;
        default:
            $texto = "";
            break;
    }
}
?>