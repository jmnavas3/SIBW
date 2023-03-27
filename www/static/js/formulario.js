
var oculto = true;
var editar = true;
// var prohibidas = /profesor|maestro|caca|tonto|estupido|puto|puta|mierda|guarro|cabron/gi;

function revisarComentario(palabras){
    var comentario = document.getElementById("comentario").value;
    for(palabra of palabras){
        if (comentario.match(palabra)){
            console.log("censurado: " + palabra.length);
            comentario = comentario.replace(palabra, "*".repeat(palabra.length));
        }
    }
    document.getElementById("comentario").value = comentario;
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
/* Función para mostrar el textArea para editar comentario */
function editarComentario(num){
    var css = document.getElementsByName("coment");
    var conf = document.getElementsByName("confirm");
    // console.log("tam: " + tam + "num: " + num);
    // console.log(css[num]);
    // console.log(conf[num]);

    if(!editar){
        css[num].style.display = "none";
        conf[num].style.display = "none";
    }
    else{
        css[num].style.display = "flex";
        conf[num].style.display = "flex";
    }

    
    editar = !editar;
}
/* Función para que los días y meses menores a 10 sean escritos con dos dígitos */
function checkFecha(date){
    var formato = date;
    if (date<10){
        formato = "0"+date;
    }
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

    if ( !(comentario==="" || comentario===null )){
        if (validarEmail(email)){
            // document.getElementById("nombre").value = "";
            // document.getElementById("comentario").value = "";
            // document.getElementById("email").value = "";
            
            // crearComentario(date,nombre,comentario);
            console.log(nombre);
            console.log(comentario);
            console.log(email);
            return true;
        }
        else
            alert("Email no válido");
    }
    else
        alert("Faltan campos por rellenar");

    console.log(nombre);
    console.log(comentario);
    console.log(email);
    return false;
}

if(document.getElementById("boton") != null)
    document.getElementById("boton").addEventListener("click", mostrarComentarios);