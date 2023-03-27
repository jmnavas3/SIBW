<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
	include("controlador.php");
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	$controlador = new Controlador();
	$com = $controlador->getAllComentarios();
	$controlador->close();
	$variablesParaTwig = [];

	session_start();

	if(isset($_SESSION['usuario'])){
		$variablesParaTwig['user'] = $_SESSION['usuario'];
		if($_SESSION['usuario']['rol'] === 'superusuario' || $_SESSION['usuario']['rol'] === 'moderador'){
			$variablesParaTwig['comentarios'] = $com;
			$variablesParaTwig['perfil'] = true; //variable usada en comentario.html
		}
	}
	echo $twig->render('perfil.html', $variablesParaTwig);
?>
