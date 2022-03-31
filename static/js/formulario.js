var oculto = true;
var prohibidas = /profesor|maestro|caca|tonto|estupido|puto|puta|mierda|guarro|cabron/gi;

function revisarComentario(){
    var comentario = document.getElementById("comentario");
    comentario.value = comentario.value.replace(prohibidas, "*******");
}

function mostrarComentarios(){
    var css = document.getElementById("desplegable");

    if(!oculto)
        css.style.display = "none";
    else{
        css.style.display = "flex";
        css.style.flexDirection = "row-reverse";
    }
    
    oculto = !oculto;
}
/* Función para que los días y meses menores a 10 sean escritos con dos dígitos */
function checkFecha(date){
    var formato = date;
    if (date<10){
        formato = "0"+date;
    }
    console.log(formato);
    return formato;
}
/* REF: https://stackoverflow.com/questions/46155/whats-the-best-way-to-validate-an-email-address-in-javascript */
function validarEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

/* 
    Funcion para añadir un comentario nuevo al contenedor de comentarios
    como append child. El comentario usa appendChilds y text nodes para
    añadir los elementos de texto
*/
function crearComentario(fecha,nombre,texto){
    var text_node; //para crear nodos de texto
    var cont_comentario = document.createElement("div");
    cont_comentario.setAttribute("id","cont-comentario");

    var autor = document.createElement("h3");
    autor.setAttribute("id","autor");
    text_node = document.createTextNode(nombre);
    autor.appendChild(text_node);

    var fecha_comentario = document.createElement("h3");
    fecha_comentario.setAttribute("id","fecha");
    text_node = document.createTextNode(fecha);
    fecha_comentario.appendChild(text_node);

    var parrafo = document.createElement("p");
    text_node = document.createTextNode(texto);
    parrafo.appendChild(text_node);

    cont_comentario.appendChild(autor);
    cont_comentario.appendChild(fecha_comentario);
    cont_comentario.appendChild(parrafo);

    // se recuperan los comentarios anteriores y se añade el nuevo
    var desplegable = document.getElementById("cont-comentarios");
    desplegable.appendChild(cont_comentario);
    return false;
}

/*
   recoge la informacion enviada por el usuario y
   comprueba los datos antes de crear comentario
*/
function nuevoComentario(){
    var nombre = document.getElementById("nombre").value;
    var comentario = document.getElementById("comentario").value;
    var email = document.getElementById("email").value;
    var date = new Date();
    var dia = checkFecha(date.getDate());
    var mes = checkFecha(date.getMonth()+1); // empieza por 0
    var fecha = dia + "/" + mes + "/" + date.getUTCFullYear(); //expr.regular para fecha
    date = fecha;

    if ( !(nombre==="" || comentario==="" || email==="" )){
        if (validarEmail(email)){
            document.getElementById("nombre").value = "";
            document.getElementById("comentario").value = "";
            document.getElementById("email").value = "";
            
            crearComentario(date,nombre,comentario);
        }
        else
            alert("Email no válido");
    }
    else
        alert("Faltan campos por rellenar");
    
    return false;
}

document.getElementById("boton").addEventListener("click", mostrarComentarios);
document.getElementById("comentario").addEventListener("change", revisarComentario);
document.getElementById("submit").addEventListener("click", nuevoComentario);