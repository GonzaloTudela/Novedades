// Funcion que al pasarle un texto muestra un mensaje de error y al hacer focus en login o pass lo quita...
// ... asi evitamos repetir este codigo para cada error.
function errores(texto) {
    let form = document.getElementById("login_form");
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
}