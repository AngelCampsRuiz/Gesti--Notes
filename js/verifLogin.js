document.getElementById("username").onblur = function validaUser() {
    let username = this.value.trim()
    let errorUser = ""
    if(username.length == 0 || username == null || /^\s+$/.test(username)) {
        errorUser = "El campo no puede estar vacio."
    } else if(username < 2){
        errorUser = "El campo debe tener mas de 2 letras."
    } else if(!letras(username)){
        errorUser = "El campo solo puede contener letras."
    }
    function letras(username){
        let patron = /^[a-zA-Z]+$/
        return patron.test(username)
    }
    document.getElementById("errorUser").textContent = errorUser
    verificarForm()
}
document.getElementById("pwd").onblur = function validaPwd() {
    let pwd = this.value.trim()
    let errorPwd = ""
    if(pwd.length == 0 || pwd == null || /^\s+$/.test(pwd)) {
        errorPwd = "El campo no puede estar vacio."
    } else if(!patron(pwd)){
        errorPwd = "El campo necesita letra mayúscula, minúscula y número.(ser mas de 6)"
    }
    function patron(pwd){
        let patron = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{6,}/
        return patron.test(pwd)
    }
    verificarForm()
}
function verificarForm() {
    const errores = [
        document.getElementById("errorUser").innerHTML,
        document.getElementById("errorPwd").innerHTML
    ]
    const campos = [
        document.getElementById("username").value.trim(),
        document.getElementById("pwd").value.trim()
    ]
    const hayErrores = errores.some(error => error != "")
    const camposVacios = campos.some(campos => campos == "")
    document.getElementById("boton").disabled = hayErrores || camposVacios
}