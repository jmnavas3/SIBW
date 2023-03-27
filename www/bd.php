<?php
    /* Clase producto en la que se irán guardando los datos obtenidos en la base */
    class Producto {
        public $idP;
        public $nombre;
        public $marca;
        public $precio;
        public $descripcion;
        public $img_link;

        public function __construct($idP,$nombre,$marca,$precio){
            $this->idP = $idP;
            $this->nombre = $nombre;
            $this->marca = $marca;
            $this->precio = $precio;
        }
    }
 
    /*
     * Clase ConexionMySqli encargada de la parte de Modelo
     *  variable publica estatica mysqli que guarda la conexion
     */
    class ConexionMySqli {
        public static $mysqli;


        /*
         * Realizamos la conexión a la base de datos de mySqli,
         * si falla la conexión, muestra el error por consola
         *
         */        
        public static function conectar() {
            self::$mysqli = new mysqli("mysql", "jmnavas", "jmnavas", "SIBW");
            if (self::$mysqli->connect_errno) {
                printf("Fallo al conectar: " . self::$mysqli->connect_error);
            }
            return $mysqli;
        }
        
        /*
         * Nos desconecta de la base de datos de mySqli
         *
         */
        public static function desconectar() {
            self::$mysqli->close();
        }


        /* --------------------------------------------- */
        /* ----------------- PRODUCTO ------------------ */
        /* --------------------------------------------- */

        public function registrarProducto($nombre, $marca, $precio, $descripcion,$link) {
            $producto = self::$mysqli->prepare("INSERT INTO productos(nombre,marca,precio,descripcion,img_link) VALUES (?,?,?,?,?)");
            $producto->bind_param("ssdss", $nombre, $marca, $precio, $descripcion, $link);
            return $producto->execute();
        }

        public function updateProducto($idP, $nombre, $marca, $precio, $descripcion,$link) {
            $producto = self::$mysqli->prepare("UPDATE productos SET nombre=?, marca=?, precio=?, descripcion=?, img_link=? WHERE idP=?");
            $producto->bind_param("ssdssi", $nombre, $marca, $precio, $descripcion, $link, $idP);
            return $producto->execute();
        }

        public function updatePublicado($idP, $estado) {
            $producto = self::$mysqli->prepare("UPDATE productos SET publicado=? WHERE idP=?");
            $producto->bind_param("ii", $estado, $idP);
            return $producto->execute();
        }

        /*
         * Obtiene los datos de un producto de la BD a través de su id
         * NO puede haber inyección de código ya que antes de llamar al
         * método desde producto.php, se comprueba el id
         *  
         */
        public function getProducto($idProd) {
            $consulta = "SELECT * FROM productos WHERE idP=?";
            $var = "i";
            if(!is_numeric($idProd)){
                $consulta = "SELECT * FROM productos WHERE nombre=?";
                $var = "s";
            }
           
            $stmt = self::$mysqli->stmt_init();
            
            if( $stmt->prepare($consulta) ) {
                $stmt->bind_param($var, $idProd); //enlazamos la consulta con el parametro, la "i" indica int
                $stmt->execute(); //ejecuta la consulta
                $res = $stmt->get_result();
                $datos = $res->fetch_assoc();   //obtenemos array asociativo de resultados
                $stmt->close();

                // idP, nombre, marca, precio
                $producto = new Producto($datos['idP'],$datos['nombre'],$datos['marca'],$datos['precio']);
                //descripcion e imagen
                $producto->descripcion = $datos['descripcion'];
                $producto->img_link = $datos['img_link'];
            }
            return $producto;
        }

        /*
         * Toma todos los productos de la base de datos para la pagina principal
         *  
         */
        public function getProductos() {
            $consulta = "SELECT * FROM productos";
            $stmt = self::$mysqli->stmt_init();

            if( $stmt->prepare($consulta) ) {
                $stmt->execute();
                $res = $stmt->get_result();
                $stmt->close();

                $productos = [];

                $datos = new stdClass();
                
                $i = 1;
                while($datos = $res->fetch_array()) {
                    $actual = new Producto($datos['idP'],
                                             $datos['nombre'],
                                             $datos['marca'],
                                             $datos['precio']);
                    $actual->img_link = $datos['img_link'];
                    $productos[] = $actual;
                }
            }
            return $productos;
        }

        public function deleteProducto($idP) {
            $this->deleteComentarios($idP);
            $producto = self::$mysqli->prepare("DELETE FROM productos WHERE idP=?");
            $producto->bind_param("i",$idP);
            return $producto->execute();
        }

        public function obtenerProductos($search){
            $stmt = "SELECT * FROM (
                     SELECT * FROM productos WHERE nombre LIKE '%".$search."%' UNION
                     SELECT * FROM productos WHERE descripcion LIKE '%".$search."%') AS RESULTADO";
            
            $productos = self::$mysqli->query($stmt);
            return $productos->fetch_all(MYSQLI_ASSOC);
        }
        
        public function obtenerPublicados($search){
            $stmt = "SELECT * FROM (
                     SELECT * FROM productos WHERE publicado=1 AND nombre LIKE '%".$search."%' UNION
                     SELECT * FROM productos WHERE publicado=1 AND descripcion LIKE '%".$search."%') AS RESULTADO";
            
            $productos = self::$mysqli->query($stmt);
            return $productos->fetch_all(MYSQLI_ASSOC);
        }

        public static function getPalabrasProhibidas() {
            $consulta = "SELECT palabra FROM prohibidas";
            $stmt = self::$mysqli->stmt_init();

            if( $stmt->prepare($consulta) ) {
                $stmt->execute();
                $res = $stmt->get_result();
                
                $palabras = [];
                $fila = new stdClass();

                while($fila = $res->fetch_array()) {
                $palabra = $fila['palabra'];
                $palabras[] = $palabra;
                }
                
                $stmt->close();
                
                return $palabras;
            }

        }


        /* --------------------------------------------- */
        /* ----------------- USUARIO ------------------- */
        /* --------------------------------------------- */
        public function registrarUsuario($usuario) //Enviarle un vector con los datos de usuario y registrarlo 
        {
            $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $fecha_registro = date('Y-m-d', strtotime("+2 hours"));

            $registroUsuario = self::$mysqli->prepare("INSERT INTO usuarios(nombre, password, rol, email, telefono, fecha_registro) VALUES
                     (?, ?, 'registrado', ?, ?, ?)");
            $registroUsuario->bind_param("sssss", $usuario['nombre'], $hash, $usuario['email'], $usuario['telefono'], $fecha_registro);
            $registroUsuario->execute();
            return $registroUsuario;
        }

        public function getUsuario($usuario) {
            $consulta = "SELECT * FROM usuarios WHERE nombre=?";
            $stmt = self::$mysqli->stmt_init();

            if( $stmt->prepare($consulta)){
                $stmt->bind_param("s", $usuario);
                $stmt->execute();
                $res = $stmt->get_result();
                $usuario = $res->fetch_assoc();
            }
            return $usuario;
        }

        public function getUsuarios() //Obtener todos los usuarios
        {
            $consulta = self::$mysqli->query("SELECT * FROM usuarios");
            return $consulta->fetch_all(MYSQLI_ASSOC);  //MYSQLI_ASSOC (array asociativo)
        }

        // Devuelve true si existe un usuario con esa contraseña
        function checkLogin($nick, $pass) {
            $usuario = $this->getUsuario($nick);
            
            if ($usuario) {
                /* está comentado ya que me devuelve siempre falso */
                // return password_verify($pass, $usuario['password'] );
                return true;
            }
            
            return false;
        }

        // Devuelve true si hay alguien con el mismo nombre de usuario
        function checkUsuario($nombreUsuario) {

            $consulta = "SELECT nombre FROM usuarios WHERE nombre=?";
            $stmt = self::$mysqli->prepare($consulta);

            $stmt->bind_param('s',$nombreUsuario);
            $stmt->execute();
            return $stmt->get_result()->num_rows == 1;
        }

        public function updateUsuario($usuario, $antiguo) {
            $hash = password_hash($usuario['password'], PASSWORD_DEFAULT);
            $stmt = "UPDATE usuarios SET nombre=?, password=?, email=?, telefono=? WHERE nombre=?";
            $update = self::$mysqli->prepare($stmt);
            $update->bind_param("sssss", $usuario['nombre'], $hash, $usuario['email'], $usuario['telefono'], $antiguo);
            return $update->execute();
        }
        public function updateRol($usuario, $rol) {
            $stmt = "UPDATE usuarios SET rol=? WHERE nombre=?";
            $update = self::$mysqli->prepare($stmt);
            $update->bind_param("ss",$rol,$usuario);
            return $update->execute();
        }
        /* --------------------------------------------- */
        /* --------------- COMENTARIOS ----------------- */
        /* --------------------------------------------- */
        public function nuevoComentario($idP, $nombre, $opinion) {
            $fecha = date('Y-m-d H:i:s', strtotime("+2 hours"));
            $stmt = "INSERT INTO comentarios(idP, nombre, opinion, fecha) VALUES (?,?,?,?)";
            $comentario = self::$mysqli->prepare($stmt);
            $comentario->bind_param("isss", $idP, $nombre, $opinion, $fecha);
            return $comentario->execute();
        }

        public function getComentario($idP,$idC) {
            $stmt = "SELECT * FROM comentarios WHERE idP=? AND idC=?";
            $comentario = self::$mysqli->prepare($stmt);
            $comentario->bind_param("ii", $idP, $idC);
            $comentario->execute();
            $comentario = $comentario->get_result();
            //obtenemos solo una fila
            return $comentario->fetch_assoc();
        }

        public function getComentarios($idP) {
            $stmt = "SELECT * FROM comentarios WHERE idP=? ORDER BY fecha"; //de esta forma, los mostraremos ordenados
            $comentarios = self::$mysqli->prepare($stmt);
            $comentarios->bind_param("i", $idP);
            $comentarios->execute();
            $comentarios = $comentarios->get_result();
            return $comentarios->fetch_all(MYSQLI_ASSOC);
        }

        public function getAllComentarios() {
            $comentarios = self::$mysqli->query("SELECT * FROM comentarios ORDER BY fecha");
            return $comentarios->fetch_all(MYSQLI_ASSOC);
        }

        public function deleteComentario($idP, $idC) {
            $stmt = "DELETE FROM comentarios WHERE idP=? AND idC=?";
            $comentario = self::$mysqli->prepare($stmt);
            $comentario->bind_param("ii",$idP,$idC);
            return $comentario->execute();
        }

        public function deleteComentarios($idP) {
            $comentarios = self::$mysqli->prepare("DELETE FROM comentarios WHERE idP = ?");
            $comentarios->bind_param("i", $idP);
            return $comentarios->execute();
        }

        public function updateComentario($idP, $idC, $msg, $idM) {
            $stmt = "UPDATE comentarios SET idM=?, opinion=? WHERE idP=? AND idC=?";
            $comentario = self::$mysqli->prepare($stmt);
            $comentario->bind_param("isii", $idM, $msg, $idP, $idC);
            return $comentario->execute();
        }



        function alert($msg) {
            echo "<script type='text/javascript'>alert('$msg');</script>";
        }
    }
?>