document.getElementById("username").oninput = function() {
    let username = document.getElementById("username").value;
    let errorUsername = ""
    if(username.length == 0 || username == null || /^\s+$/.test(username)) {
        errorUsername = "El campo nombre no puede estar vacío"
    } else if(!letrasYnumeros(username)) {
        errorUsername = "El nombre solo puede contener letras"
    }
    function letrasYnumeros(username) {
        return /^[a-zA-Z0-9]+$/.test(username);
    }
    document.getElementById("errorUsername").innerHTML = errorUsername
}
document.getElementById("nombre").oninput = function validaNombre() {
    let nombre = this.value.trim()
    let errorNombre = ""
    if(nombre.length == 0 || nombre == null || /^\s+$/.test(nombre)) {
        errorNombre = "El campo nombre no puede estar vacío"
    } else if(!letras(nombre)) {
        errorNombre = "El nombre solo puede contener letras"
    }
    function letras(nombre) {
        return /^[a-zA-Z]+$/.test(nombre);
    }
    document.getElementById("errorNombre").innerHTML = errorNombre
    veriForm()
}
document.getElementById("apellido").oninput = function validaApellido() {
    let apellido = this.value.trim()
    let errorApellido = ""
    if(apellido.length == 0 || apellido == null || /^\s+$/.test(apellido)) {
        errorApellido = "El campo apellido no puede estar vacío"
    } else if(!letras(apellido)) {
        errorApellido = "El apellido solo puede contener letras"
    }
    function letras(apellido) {
        return /^[a-zA-Z]+$/.test(apellido);
    }
    document.getElementById("errorApellido").innerHTML = errorApellido
    veriForm()
}
document.getElementById("dni").oninput = function validaDNI() {
    let dni = this.value
    let errorDNI = ""
    if(dni.length == 0 || dni == null || /^\s+$/.test(dni)){
        errorDNI = "El campo no puede estar vacio."
    } else if(!calculoDNI(dni)){
        errorDNI = "El DNI no es valido."
    } else if(!letraDni(dni)){
        errorDNI = "La letra del DNI no coincide con el numero."
    }
    function calculoDNI(dni){
        let formatoDni = /^\d{8}[A-Za-z]$/
        return formatoDni.test(dni)
    }
    function letraDni(dni){
        let letras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E', 'T']
        let numeroDNI = dni.substring(0,8)
        let letraDNI = dni.charAt(8).toUpperCase()
        let letraExtraida = letras[numeroDNI % 23]
        return letraDNI == letraExtraida
    }
    document.getElementById("errorDNI").innerHTML = errorDNI
}
document.getElementById("email").oninput = function validaEmail() {
    let email = this.value.trim()
    let errorEmail = ""
    if(email.length == 0 || email == null || /^\s+$/.test(email)) {
        errorEmail = "El campo email no puede estar vacío"
    } else if(!emailValido(email)) {
        errorEmail = "El email no es válido"
    }
    function emailValido(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    document.getElementById("errorEmail").innerHTML = errorEmail
    veriForm()
}
document.getElementById("telefono").oninput = function validaTelefono() {
    let telefono = this.value.trim()
    let errorTelefono = ""
    if(telefono.length == 0 || telefono == null || /^\s+$/.test(telefono)) {
        errorTelefono = "El campo teléfono no puede estar vacío"
    } else if(!telefonoValido(telefono)) {
        errorTelefono = "El teléfono no es válido"
    }
    function telefonoValido(telefono) {
        return /^(?:(?:\+?[0-9]{2,4})?[ ]?[6789][0-9 ]{8,13})$/.test(telefono);
    }
    document.getElementById("errorTelefono").innerHTML = errorTelefono
    veriForm()
}
document.getElementById("fecha").onmouseleave = function validaFecha() {
    let fecha = this.value.trim()
    let errorFecha = ""
    if(fecha.length == 0 || fecha == null || /^\s+$/.test(fecha)) {
        errorFecha = "El campo fecha no puede estar vacío"
    } else if(!fechaValida(fecha)) {
        errorFecha = "El alumno debe tener al menos 18 años"
    }
    function fechaValida(fecha) {
        let fechaNueva = new Date(fecha);
        let fechaActual = new Date();
        let diferenciaAnios = fechaActual.getFullYear() - fechaNueva.getFullYear();
        if (fechaActual.getMonth() < fechaNueva.getMonth() || 
            (fechaActual.getMonth() === fechaNueva.getMonth() && fechaActual.getDate() < fechaNueva.getDate())) {
            diferenciaAnios--;
        }
        return diferenciaAnios >= 18;
    }    
    document.getElementById("errorDia").innerHTML = errorFecha
    veriForm()
}
document.getElementById("direccion").oninput = function validaDireccion() {
    let direccion = this.value.trim()
    let errorDireccion = ""
    if(direccion.length == 0 || direccion == null || /^\s+$/.test(direccion)) {
        errorDireccion = "El campo dirección no puede estar vacío"
    } else if(direccion.length < 10){
        errorDireccion = "La dirección debe tener al menos 10 caracteres"
    }
    document.getElementById("errorDireccion").innerHTML = errorDireccion
    veriForm()
}
function veriForm() {
    const errores = [
        document.getElementById("errorNombre").innerHTML,
        document.getElementById("errorApellido").innerHTML,
        document.getElementById("errorEmail").innerHTML,
        document.getElementById("errorTelefono").innerHTML,
        document.getElementById("errorDia").innerHTML,
        document.getElementById("errorDireccion").innerHTML
    ]
    const campos = [
        document.getElementById("nombre").value.trim(),
        document.getElementById("apellido").value.trim(),
        document.getElementById("email").value.trim(),
        document.getElementById("telefono").value.trim(),
        document.getElementById("fecha").value.trim(),
        document.getElementById("direccion").value.trim()
    ]
    const camposVacios = campos.some(campo => campo == "")
    const hayErrores = errores.some(error => error !== "")
    document.getElementById('boton').disabled = hayErrores || camposVacios
}