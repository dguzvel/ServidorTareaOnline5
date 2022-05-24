<?php

    //Clase modelo
    class modelo{

        //Se crean las variables que serán necesarias, como la conexión
        private $conexion;
        
        //Así como otras que contendrán los valores con los que se llevará a cabo la conexión
        private $host = "localhost";
        private $nombreBase = "bdapi";
        private $usuario = "root";
        private $password = "";

        //La función del constructor llama en el contexto de la clase modelo a la función conectar (que establece la conexión)
        public function __construct(){
            $this->conectar();
        }

        public function conectar(){

            try {
                
                //Nos conectamos a la Base de Datos
                $this->conexion = new PDO("mysql:host=$this->host; dbname=$this->nombreBase", $this->usuario, $this->password);
                //Activamos el  modo de excepciones
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
            } catch (PDOException $e) {

                return $e->getMessage();

            }

        }
        
        /**
         * La id que se le pasa por parámetro sirve para filtrar la sentencia SELECT SQL a una entrada específica
         *
         * @param [type] $id
         * @return void
         */
        public function listarUnaEntrada($id){
 
            try {

                //Se establece la sentencia SQL
                $sql = "SELECT * FROM entradasblog WHERE id = :id;";

                //Se prepara la sentencia en una conexión específica, que es la que hemos realizado
                $query = $this->conexion->prepare($sql);

                //Se ejecuta la sentencia preparada y el campo id obtendrá el valor de aquel que se le pase por parámetro
                $query->execute(['id' => $id]);
                    
                header("HTTP/1.1 200 OK");

                //Se devuelve el json de los valores obtenidos cuando se realice un FETCH a la sentencia
                echo json_encode(  $query->fetch(PDO::FETCH_ASSOC)  );
                exit();

            } catch (PDOException $e) {

                return $e->getMessage();

            }
    
        }

        /**
         * Funciona de manera muy similar a la función que lista una entrada específica, pero en este caso no requiere ningún
         * parámetro. Seleccionará todas las filas de una tabla específica.
         *
         * @return void
         */
        public function listarEntradas(){
 
            try {

                $sql = "SELECT * FROM entradasblog";

                $query = $this->conexion->prepare($sql);

                $query->execute();

                $query->setFetchMode(PDO::FETCH_ASSOC);

                header("HTTP/1.1 200 OK");

                //En este caso se trata de FETCHALL porque obtendrá más de una fila de la base de datos como información
                echo json_encode( $query->fetchAll()  );
                exit();

            } catch (PDOException $e) {

                return $e->getMessage();

            }

        }

        /**
         * Insertará una nueva fila en la tabla de entradasblog
         *
         * @return void
         */
        public function insertarEntrada(){

            try {

                //Obtendrá los valores obtenidos del form-data mediante el método POST
                $input = $_POST;

                $sql = "INSERT INTO entradasblog (id, nick, titulo, imagen, descripcion, fecha) VALUES (NULL, :nick, :titulo, :imagen, :descripcion, :fecha)";
                
                $query = $this->conexion->prepare($sql);
                
                //Con esta función vamos a dar valores a cada uno de los :campos, esos valores serán los obtenidos por $_POST
                bindAllValues($query, $input);
                
                $query->execute();

                $postId = $this->conexion->lastInsertId();

                if($postId){

                    $input['id'] = $postId;
                    header("HTTP/1.1 200 OK");
                    echo json_encode($input);
                    exit();

                }

            } catch (PDOException $e) {

                return $e->getMessage();

            }

        }

        /**
         * Esta función elimina una entrada de la tabla
         *
         * @return void
         */
        public function borrarEntrada(){

            try {

                //Obtenemos una id como parámetro mediante $_GET
                $id = $_GET['id'];

                $sql = "DELETE FROM entradasblog where id=:id";
                
                $query = $this->conexion->prepare($sql);

                //La entrada que se borrará será la que tenga el id especificado
                $query->bindValue(':id', $id);

                $query->execute();

                header("HTTP/1.1 200 OK");
                exit();

            } catch (PDOException $e) {

                return $e->getMessage();

            }

        }
        
        /**
         * Esta función modificará uno o varios campos de una entrada especificada por su id
         *
         * @return void
         */
        public function modificarEntrada(){

            try {

                //Obtenemos todos los valores introducidos como parámetros $_GET
                $input = $_GET;

                //Reservamos la id de la entrada con la que queremos trabajar en la variable $postId. Será el valor de $input con clave id
                $postId = $input['id'];

                //Los camposque serán modificados serán aquellos obtenidos por la función getParams de controlador.php
                $fields = getParams($input);
            
                $sql = "UPDATE entradasblog SET $fields WHERE id='$postId'";
            
                $query = $this->conexion->prepare($sql);

                bindAllValues($query, $input);
            
                $query->execute();
                
                header("HTTP/1.1 200 OK");
                exit();

            } catch (PDOException $e) {

                return $e->getMessage();

            }

        }        

    }

?>