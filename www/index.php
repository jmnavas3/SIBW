<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
	
	include('controlador.php');
	$controlador = new Controlador();
	
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	$variablesParaTwig = [];

	
	session_start();
	if(isset($_SESSION['usuario'])){
		$variablesParaTwig['user'] = $_SESSION['usuario'];
	}
	
	if($_SESSION['usuario']['rol'] === 'gestor' OR $_SESSION['usuario']['rol'] === 'superusuario'){
		$productos = $controlador->getProductos();
	}else{
		$productos = $controlador->obtenerPublicados("");
	}
	$variablesParaTwig['productos'] = $productos;
	
	$controlador->close();
	echo $twig->render('index.html', $variablesParaTwig);
?>
