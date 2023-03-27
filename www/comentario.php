<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
	include("controlador.php");
    $controlador = new Controlador();
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	$variablesParaTwig = [];

	
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $idP = $_POST['idP'];
        
        if (isset($_POST['editar'])) // EDITAR COMENTARIO
        {
            session_start();
            $idM = $_SESSION['usuario']['id_usuario'];
            $idC = $_POST['idC'];
            $comentario = $_POST['coment'];
            $controlador->updateComentario($idP,$idC,$comentario,$idM);

        }
        else if(isset($_POST['borrar'])) // BORRAR COMENTARIO
        {
            $idC = $_POST['idC'];
            $controlador->deleteComentario($idP, $idC);
        }
        else                             // PUBLICAR COMENTARIO
        {
            $comentario = $_POST['comentario'];
            $controlador->nuevoComentario($idP, $_POST['nombre'], $comentario);
        }
        header("Location: producto.php?pr=$idP");
        exit();
	} else{
        $controlador->alert("Error 404");
    }
?>
