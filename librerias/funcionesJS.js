// Fullscreen on click document.
/* Get the documentElement (<html>) to display the page in fullscreen */
/* View in fullscreen W3SCHOOLS */
function openFullscreen() {
    let elem=document.documentElement;
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
function addEvent(element, evnt, funct){
    if (element.attachEvent)
        return element.attachEvent('on'+evnt, funct);
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
function botonSalir(){
    let body = document.getElementById('root');
    let infobox = document.createElement('div');
    infobox.setAttribute('id','infobox')
    let botonExit = document.getElementById('quit')
    botonExit.addEventListener('click', function () {
        body.prepend(infobox);
        // infobox.style.display = ('initial')
        infobox.innerHTML = `
            <button id="exit" class="txt0 answerButton sombra3">SALIR</button>
            <button id="close" class="txt0 answerButton sombra3">VOLVER</button>`
        let close=document.getElementById('close');
        let exit=document.getElementById('exit');
        close.addEventListener('click', function (){
            infobox.remove();
        })
        exit.addEventListener('click',function (){
            window.location.href='./quit.php'
        })
    });
}