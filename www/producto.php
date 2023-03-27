<?php
	require_once "/usr/local/lib/php/vendor/autoload.php";
	include("controlador.php"); //añadimos el codigo del controlador

	$controlador = new Controlador();
	//indicamos el directorio de las plantillas
	$loader = new \Twig\Loader\FilesystemLoader('templates');
	$twig = new \Twig\Environment($loader);
	
	$variablesParaTwig = [];
	session_start();

	
	
	if($_SERVER['REQUEST_METHOD'] === 'GET'){	// GET
		if(is_numeric($_GET['pr'])){
			$idProd = $_GET['pr'];
		}else{
			$idProd = 1;
		}

		if(isset($_SESSION['usuario'])){
			$variablesParaTwig['user'] = $_SESSION['usuario'];
		}

		$prod = $controlador->getProducto($idProd);
		$com  = $controlador->getComentarios($idProd);
		$censurada = $controlador->getPalabrasProhibidas();
		$variablesParaTwig['producto'] = $prod;
		$variablesParaTwig['comentarios'] = $com;
		$variablesParaTwig['palabra'] = $censurada;
	}
	else if ($_SERVER['REQUEST_METHOD'] === 'POST'){	// POST
		
		if(isset($_POST['borrar'])) 			// BORRAR PRODUCTO
		{
			$controlador->deleteProducto($_POST['idP']);
			$controlador->close();
			$variablesParaTwig['user'] = $_SESSION['usuario'];
			header("Location: index.php");
		}
		else if(isset($_POST['save']))			// GUARDAR PRODUCTO
		{
			$nombre = $_POST['nombre'];
			$marca = $_POST['marca'];
			$precio = $_POST['precio'];
			$descripcion = $_POST['descripcion'];
			$imagen = $_POST['imagen'];

			// $controlador->alert($nombre . $marca . $precio . $imagen . $descripcion);
			if ($controlador->registrarProducto($nombre,$marca,$precio,$descripcion,$imagen)){
				$variablesParaTwig['user'] = $_SESSION['usuario'];
				header("Location: index.php");
			}
		}
		else if(isset($_POST['registrar'])) 	// REGISTRAR PRODUCTO (formulario)
		{
			$variablesParaTwig['user'] = $_SESSION['usuario'];
			$variablesParaTwig['registrar'] = true;	//variable para mostrar formulario con twig
		}

	}
		
	
	$controlador->close();

	echo $twig->render('producto.html',$variablesParaTwig);
?>
