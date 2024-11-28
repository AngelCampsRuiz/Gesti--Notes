document.getElementById("asignatura").onmouseleave = function validaAsignatura() {
    let asignatura = this.value.trim()
    let errorAsig = ""
    if(asignatura.length == 0 || asignatura == null || /^\s+$/.test(asignatura)) {
        errorAsig = "La asignatura no puede estar vacía"
    }
    document.getElementById("errorAsig").innerHTML = errorAsig
    validarForm()
}
document.getElementById("nota").oninput = function validaNota() {
    let nota = this.value.trim()
    let errorNota = ""
    if(nota.length == 0 || nota == null || /^\s+$/.test(nota)) {
        errorNota = "El campo no puede estar vacio."
    } else if(isNaN(nota) || nota < 0 || nota > 10) {
        errorNota = "La nota debe ser un número entre 0 y 10."
    }
    document.getElementById("errorNota").innerHTML = errorNota
    validarForm()
}
function validarForm() {
    const errores = [
        document.getElementById("errorAsig").innerHTML,
        document.getElementById("errorNota").innerHTML
    ]
    const campos = [
        document.getElementById("asignatura").value.trim(),
        document.getElementById("nota").value.trim()
    ]
    const hayErrores = errores.some(error => error !== "")
    const camposVacios = campos.some(campo => campo == "")
    document.getElementById("boton").disabled = hayErrores || camposVacios
}