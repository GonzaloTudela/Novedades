<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Novedades</title>
    <link rel="stylesheet" href="css/general.css">
</head>
<body>
<div id="fondo">
    <form id="login_form" method="POST" action="#">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <h1>NOVEDADES</h1>
        <label for="login"></label>
        <input type="text" id="login" name="login" placeholder="Usuario"><br>
        <label for="pass"></label>
        <input type="password" id="pass" name="pass" placeholder="Contraseña"><br>
        <button>ENTRAR</button>
        <div class="g-recaptcha"
             data-sitekey="6LciBd8ZAAAAAGIs59aPs5dgFLoCB72NUmp5CXY-"
             data-badge="inline">
        </div>
    </form>
</div>
</body>
</html>
<script>
    // Funcion que al pasarle un texto muestra un mensaje de error y al hacer focus en login o pass lo quita...
    // ... asi evitamos repetir este codigo para cada error.
    function errores(texto) {
        let form = document.getElementById("form");
        let error = document.createElement("p");
        error.innerText = texto;
        error.setAttribute("id", "error");
        error.setAttribute("class", "error");
        form.appendChild(error);
        let login = document.getElementById("login");
        let pass = document.getElementById("pass");
        login.addEventListener("focus", function () {
            error.style.display = "none";
        });
        pass.addEventListener("focus", function () {
            error.style.display = "none";
        });
        document.getElementsByTagName("script")[0].remove();
    }
</script>
<?php
include("librerias/antisqli.php");
if (isset($_GET["error"])) {
    $error = antiSQLi($_GET["error"]);
    switch ($error) {
        case 'login':
            $texto = "NOMBRE O CONTRASEÑA INCORRECTOS";
            ?>
            <script>errores("<?=$texto?>")</script>
            <?php
            break;
        case "captcha":
            $texto = "POR FAVOR VERIFIQUE EL CAPTCHA";
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