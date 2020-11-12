<?php
// Si recogemos en GET la clave error, contemplamos los casos segun el value.
function error_get()
{
    if (isset($_GET["error"])) {
        $error = htmlspecialchars($_GET["error"]);
        switch ($error) {
            case 'login':
                echo <<< _HTML
                <p class="error_text">Usuario o contraseña incorrectos.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "captcha":
                echo <<< _HTML
                <p class="error_text">Verifique la casilla del captcha.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "datos":
                echo <<< _HTML
                <p class="error_text">Inserte un nombre de usuario y contraseña.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "mysql":
                echo <<< _HTML
                <p class="error_text">Error al conectar con la base de datos.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "recaptcha":
                echo <<< _HTML
                <p class="error_text">Error en la respuesta de recaptcha.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "spam":
                echo <<< _HTML
                <p class="error_text">Verifique la casilla de recaptcha.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "ubaja":
                echo <<< _HTML
                <p class="error_text">Su usuario no esta dado de alta, consulte con su reponsable.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            case "ebaja":
                echo <<< _HTML
                <p class="error_text">Su usuario no pertenece a ninguna empresa activa.</p>
                <script class="killme">killme();</script>
                _HTML;
                break;
            default:
                break;
        }
    }
}