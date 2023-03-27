<?php
    require_once("./bd.php");
    Class Controlador {
        public static $mysql;

        public function __construct() {
            self::$mysql = new ConexionMySqli();
            self::$mysql->conectar();
        }

        public function registrarProducto($nombre, $marca, $precio, $descripcion,$link) {
            return self::$mysql->registrarProducto($nombre, $marca, $precio, $descripcion,$link);
        }
        public function updateProducto($idP, $nombre, $marca, $precio, $descripcion,$link){
            return self::$mysql->updateProducto($idP, $nombre, $marca, $precio, $descripcion,$link);
        }
        public function updatePublicado($idP, $estado){
            return self::$mysql->updatePublicado($idP, $estado);
        }
        public function getProductos() {
            $productos = self::$mysql->getProductos();
            return $productos;
        }
        public function getProducto($idProd) {
            $producto = self::$mysql->getProducto($idProd);
            return $producto;
        }
        public function deleteProducto($idProd) {
            return self::$mysql->deleteProducto($idProd);
        }
        public function obtenerPublicados($search) {
            return self::$mysql->obtenerPublicados($search);
        }
        public function obtenerProductos($search) {
            return self::$mysql->obtenerProductos($search);
        }
        /*--------------------- USUARIOS ----------------------- */
        public function registrarUsuario($nombreU) {
            $usuario = self::$mysql->registrarUsuario($nombreU);
            return $usuario;
        }
        public function getUsuario($nombreU) {
            $usuario = self::$mysql->getUsuario($nombreU);
            return $usuario;
        }
        public function getUsuarios() {
            $usuarios = self::$mysql->getUsuarios();
            return $usuarios;
        }
        public function checkLogin($nick, $pass) {
            return self::$mysql->checkLogin($nick,$pass);
        }
        public function checkUsuario($nombreU) {
            $usuario = self::$mysql->checkUsuario($nombreU);
            return $usuario;
        }
        public function updateUsuario($usuario, $antiguo){
            return self::$mysql->updateUsuario($usuario,$antiguo);
        }
        public function updateRol($usuario, $rol) {
            return self::$mysql->updateRol($usuario,$rol);
        }
        /*--------------------- COMENTARIOS ----------------------- */
        public function nuevoComentario($idP, $nombre, $opinion) {
            return self::$mysql->nuevoComentario($idP,$nombre,$opinion);
        }
        public function getComentario($idP,$idC) {
            return self::$mysql->getComentario($idP,$idC);
        }
        public function getComentarios($idP) {
            return self::$mysql->getComentarios($idP);
        }
        public function getAllComentarios() {
            return self::$mysql->getAllComentarios();
        }
        public function deleteComentario($idP,$idC) {
            return self::$mysql->deleteComentario($idP,$idC);
        }
        public function updateComentario($idP,$idC,$msg,$idM) {
            return self::$mysql->updateComentario($idP,$idC,$msg,$idM);
        }

        public function getPalabrasProhibidas() {
            return self::$mysql->getPalabrasProhibidas();
        }

        public function alert($msg) {
            self::$mysql->alert($msg);
        }
        
        public function close(){
            self::$mysql->desconectar();
        }
    }
?>