<?php
    include("controlador.php");

    $controlador = new Controlador();

    header('Content-Type: application/json');
    
    $busqueda = $_POST['busqueda_post'];
    $rol = $_POST['rol_post'];
    

    if ($rol === 'superusuario' OR $rol === 'gestor') {
        if(isset($_POST['idP'])){
            $nuevoEstado = $_POST['estadoP'] === '0' ? 1 : 0;
            $controlador->updatePublicado($_POST['idP'], $nuevoEstado);
        }
        $encontrados = $controlador->obtenerProductos($busqueda);
    } 
    else {
        $encontrados = $controlador->obtenerPublicados($busqueda);
    } 

    echo(json_encode($encontrados));
?>