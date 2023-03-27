<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
	include("controlador.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
    $controlador = new Controlador();
    $usuarios = $controlador->getUsuarios();

	$variablesParaTwig = [];
    
	session_start();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['rol'])){
            $controlador->updateRol($_POST['UsuarioNombre'] , $_POST['rol']);
        }else{
            $controlador->alert("No se pudo cambiar el rol de " . $_POST['UsuarioNombre'] . " a " . $_POST['rol']);
        }
        $controlador->close();
        header("Location: roles.php");
        exit();
	}
    $variablesParaTwig['user'] = $_SESSION['usuario'];
    $variablesParaTwig['users'] = $usuarios;
    $controlador->close();

    echo $twig->render('roles.html', $variablesParaTwig);

?>
