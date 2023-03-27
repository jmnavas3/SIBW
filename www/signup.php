<?php
  require_once "/usr/local/lib/php/vendor/autoload.php";
  include('controlador.php');
  $controlador = new Controlador();

  $loader = new \Twig\Loader\FilesystemLoader('templates');
  $twig = new \Twig\Environment($loader);

  $variablesParaTwig = [];
  $variablesParaTwig['phU'] = "Usuario";
  $variablesParaTwig['phE'] = "email@ejemplo.com";
  $variablesParaTwig['phT'] = "Teléfono";
  $variablesParaTwig['phV'] = "Registrarse";
  session_start();
  
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_SESSION['usuario'])) // EDITAR DATOS PERSONALES
    {
      $nuevo = $_POST;
      /* se pueden modificar los datos salvo el nombre */
      $controlador->updateUsuario($nuevo,$_SESSION['usuario']['nombre']);
      $_SESSION['usuario'] = $controlador->getUsuario($nuevo['nombre']);
      header("Location: index.php");
      exit();
    }
    else                    // REGISTRARSE
    {
      $usuario = $_POST;
      
      if(!$controlador->checkUsuario($usuario['nombre'])){
        $controlador->registrarUsuario($usuario);
        $_SESSION['usuario'] = $controlador->getUsuario($usuario['nombre']);
        header("Location: index.php");
        exit();
      }
      else { $controlador->alert("Cambia de nombre o de correo"); }
    }
  }
  else if(isset($_SESSION['usuario'])){
    $variablesParaTwig['user'] = $_SESSION['usuario'];
  }
  
  echo $twig->render('signUp.html', $variablesParaTwig);

?>