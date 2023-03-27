<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);
  
  include('controlador.php');

  $controlador = new Controlador();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['nick'];
    $pass = $_POST['contraseña'];
  
    if ($controlador->checkLogin($usuario, $pass)) {
      session_start();
      $_SESSION['usuario'] = $controlador->getUsuario($usuario);  // guardo en la sesión el nick del usuario que se ha logueado
      header("Location: index.php");
      exit();
    }
    else {
      $controlador->alert("Usuario o contraseña incorrectos.");
    }
  }
  
  $productos = $controlador->getProductos();
  $variablesParaTwig['productos'] = $productos;

  echo $twig->render('index.html', $variablesParaTwig);
?>
