// VALIDAR FORMULARIO AÑADIR NOTICIA
function validarIntroducir() {
    let enviar = document.getElementById('btn_enviar');
    let cuerpo = document.getElementById('cuerpo');
    let titulo = document.getElementById('titulo');
    let fecha_fin = document.getElementById('fecha_fin');
    let fecha_hoy = new Date();
    fecha_hoy.setHours(0,0,0,0)
    let t = false;
    let c = false;
    let ff = false;
    enviar.setAttribute('disabled', '');
    enviar.value = "FALTAN DATOS";
    setInterval(validar, 250)
    setInterval(fecha, 500)
    function fecha() {
        let datos_leidos = fecha_fin.value;
        let fecha_leida = new Date(datos_leidos)
        fecha_leida.setHours(0,0,0,0)
        if(datos_leidos === '' || fecha_leida < fecha_hoy){
            ff=false
        }
        if (fecha_leida>=fecha_hoy){
            ff=true
        }
    }

    function validar() {
        if (t === true && c === true && ff === true) {
            enviar.removeAttribute('disabled');
            enviar.value = "ENVIAR";
        }
        if (t === false || c === false || ff === false) {
            enviar.setAttribute('disabled', '');
            enviar.value = "FALTAN DATOS";
        }
    }

    titulo.addEventListener("input", function () {
        if (titulo.value.length > 0) {
            t = true;
        }
        if (titulo.value.length === 0) {
            t = false;
        }
    })
    cuerpo.addEventListener("input", function () {
        if (cuerpo.value.length > 0) {
            c = true;
        }
        if (cuerpo.value.length === 0) {
            c = false;
        }
    });
    window.addEventListener("unload", function (){
        clearInterval(validar)
        clearInterval(fecha)
    })
}

// Fullscreen on click document.
/* Get the documentElement (<html>) to display the page in fullscreen */

/* View in fullscreen W3SCHOOLS */
function openFullscreen() {
    let elem = document.documentElement;
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.webkitRequestFullscreen) { /* Safari */
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) { /* IE11 */
        elem.msRequestFullscreen();
    }
}

/* Close fullscreen W3SCHOOLS*/
function closeFullscreen() {
    if (document.exitFullscreen) {
        document.exitFullscreen();
    } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
    } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
    }
}

// Añade eventos al elemento (elemento, evento, funcion).
// https://stackoverflow.com/questions/6348494/addeventlistener-vs-onclick
function addEvent(element, evnt, funct) {
    if (element.attachEvent)
        return element.attachEvent('on' + evnt, funct);
    else
        return element.addEventListener(evnt, funct, false);
}

// Elimino elementos por nombre de clase.
function removeClass(clase) {
    let elemento = document.getElementsByClassName(clase);
    while (elemento.length > 0) {
        elemento[0].parentNode.removeChild(elemento[0]);
    }
}

// Elimino clases killme (llamadas a scripts), y si hay click en document elimino clases error_text.
function killme() {
    // Buscamos suicidas
    let killme = document.getElementsByClassName("killme");
    // Buscamos errores
    let error_text = document.getElementsByClassName("error_text");
    // Mientras que haya 1 killme (>0), !DESDE EL PADRE! del primer killme (0) vamos eliminando hijos (killme)
    while (killme.length > 0) {
        killme[0].parentNode.removeChild(killme[0]);
    }
    document.addEventListener("click", function () {
        // Borramos errores al hacer click.
        while (error_text.length > 0) {
            error_text[0].parentNode.removeChild(error_text[0]);
        }
    });
}

// Agrega la funcionalidad al boton salir.
function botonSalir() {
    let body = document.getElementById('root');
    let infobox = document.createElement('div');
    infobox.setAttribute('id', 'infobox')
    let botonExit = document.getElementById('quit')
    botonExit.addEventListener('click', function () {
        body.prepend(infobox);
        // infobox.style.display = ('initial')
        infobox.innerHTML = `
            <button id="exit" class="txt0 answerButton sombra3">SALIR</button>
            <button id="close" class="txt0 answerButton sombra3">VOLVER</button>`
        let close = document.getElementById('close');
        let exit = document.getElementById('exit');
        close.addEventListener('click', function () {
            infobox.remove();
        })
        exit.addEventListener('click', function () {
            window.location.href = './quit.php'
        })
    });
}