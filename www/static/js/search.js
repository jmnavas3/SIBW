function buscarProductos(rol) {
    $.ajax({
        data: {rol_post: rol, busqueda_post: document.getElementById("buscar").value},
        url: './buscar.php',
        type: 'POST',
        success: function(productos) {
            console.log(productos);
            mostrarProductosBusqueda(productos, rol);
        }
    });
}

function editarEstado(id, estado) {
    $.ajax({
        data: {idP: id,
               estadoP: estado,
               rol_post: document.getElementById("rol_usuario").value,
               busqueda_post: document.getElementById("buscar").value
              },
        url: './buscar.php',
        type: 'POST',
        success: function(productos) {
            console.log("Producto:" + id + " Estado:" + estado);
            mostrarProductosBusqueda(productos, document.getElementById("rol_usuario").value);
        }
    });
}

function setAttributes(el, valor){
    for(var clave in valor){
        el.setAttribute(clave,valor[clave]);
    }
}

function mostrarProductosBusqueda(productos, rol) {
    $("#busqueda").empty(); //borramos los elementos de la anterior búsqueda
    
    for (let i = 0; i < productos.length; i++){
        let enlace = document.createElement('a');
        enlace.setAttribute('href', "./producto.php?pr=" + productos[i]["idP"]);
        let div = document.createElement('div');
        let p = document.createElement('p');
        let input = document.createElement('input');
        // let image = document.createElement('img');
        // image.setAttribute('src', "static/image/" + productos[i]["img_link"] + ".jpg");
        // div.appendChild(image);
        enlace.appendChild(div);
        if(rol == 'gestor' || rol == 'superusuario')
        {
            let opcion;
            
            if(productos[i]["publicado"] == 0){
                p.appendChild(document.createTextNode(productos[i]['nombre'] + " (sin publicar)"));
                opcion="publicar";
            }
            else{
                p.appendChild(document.createTextNode(productos[i]['nombre'] + " (publicado)"));
                opcion="ocultar";
            }

            let llamada = "editarEstado(" + productos[i]['idP'] + "," + productos[i]['publicado'] + ")";
            setAttributes(input, {"class":"datos", "type":"button", "onclick":llamada, "value":opcion} );
            
        }
        else
            p.appendChild(document.createTextNode(productos[i]['nombre']));
        
        div.appendChild(p);
        document.getElementById("busqueda").appendChild(enlace);
        if(rol == 'gestor' || rol == 'superusuario')
            document.getElementById("busqueda").appendChild(input);
    }

    if(document.getElementById("tabla_prod") != null)
        document.getElementById("tabla_prod").style.display = "none";


}