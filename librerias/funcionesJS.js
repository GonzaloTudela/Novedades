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