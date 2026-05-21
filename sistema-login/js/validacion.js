/* VALIDACION EN EL CLIENTE Y FUNCIONES DE INTERFAZ */

document.addEventListener("DOMContentLoaded", () => {
    const campoClave = document.getElementById("clave");
    const indicadorFortaleza = document.getElementById("indicador-fortaleza");
    const botonMostrarClave = document.getElementById("mostrar-clave");

    // ACTUALIZA EL INDICADOR DE FORTALEZA DE LA CONTRASEÑA
    if (campoClave && indicadorFortaleza) {
        campoClave.addEventListener("input", () => {
            const valorClave = campoClave.value;
            let fortaleza = 0;

            if (valorClave.length > 7) fortaleza++;
            if (/[A-Z]/.test(valorClave)) fortaleza++;
            if (/[0-9]/.test(valorClave)) fortaleza++;
            if (/[^A-Za-z0-9]/.test(valorClave)) fortaleza++;

            indicadorFortaleza.className = "indicador-fortaleza";
            if (fortaleza <= 1) indicadorFortaleza.classList.add("fortaleza-debil");
            else if (fortaleza === 2 || fortaleza === 3) indicadorFortaleza.classList.add("fortaleza-media");
            else indicadorFortaleza.classList.add("fortaleza-fuerte");
        });
    }

    // ALTERNA LA VISIBILIDAD DE LA CONTRASEÑA
    if (botonMostrarClave && campoClave) {
        botonMostrarClave.addEventListener("click", () => {
            const tipoActual = campoClave.getAttribute("type");
            campoClave.setAttribute("type", tipoActual === "password" ? "text" : "password");
            botonMostrarClave.textContent = tipoActual === "password" ? "OCULTAR" : "MOSTRAR";
        });
    }

    // VALIDACION BASICA DEL FORMULARIO
    const formulario = document.querySelector("form");
    if (formulario) {
        formulario.addEventListener("submit", (evento) => {
            const email = document.getElementById("email").value;
            const clave = document.getElementById("clave").value;

            if (!email || !clave) {
                alert("POR FAVOR COMPLETE TODOS LOS CAMPOS");
                evento.preventDefault();
            }
        });
    }
});
