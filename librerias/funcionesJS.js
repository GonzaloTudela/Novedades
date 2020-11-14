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
// Dibujo el menu inferior en funcion de los privilegios del usuario.
/*
function printMenu(container,nivel){
    let footer=document.getElementById(container);
    if (nivel>=0 && nivel<=998){
        console.log('menu normal')
    }else if (nivel===999){
        console.log('menu admin')
    }
}*/
