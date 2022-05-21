<?php

    class modelo{

        private $conexion;
        
        private $host = "localhost";
        private $nombreBase = "bdapi";
        private $usuario = "root";
        private $password = "";

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
 
        public function listarUnaEntrada($id){
 
            try {

                $sql = "SELECT * FROM entradasblog WHERE id = :id;";

                $query = $this->conexion->prepare($sql);

                $query->execute(['id' => $id]);
                    
                header("HTTP/1.1 200 OK");
                echo json_encode(  $query->fetch(PDO::FETCH_ASSOC)  );
                exit();

            } catch (PDOException $e) {


            }
    
        }

        public function listarEntradas(){
 
            try {

                $sql = "SELECT * FROM entradasblog";

                $query = $this->conexion->prepare($sql);

                $query->execute();

                $query->setFetchMode(PDO::FETCH_ASSOC);

                header("HTTP/1.1 200 OK");
                echo json_encode( $query->fetchAll()  );
                exit();

            } catch (PDOException $e) {


            }

        }

        public function insertarEntrada(){

            try {

                $input = $_POST;

                $sql = "INSERT INTO entradasblog (id, nick, titulo, imagen, descripcion, fecha) VALUES (:id, :nick, :titulo, :imagen, :descripcion, :fecha)";
                
                $query = $this->conexion->prepare($sql);
                
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


            }

        }

        public function borrarEntrada(){

            try {

                $id = $_GET['id'];

                $sql = "DELETE FROM entradasblog where id=:id";
                
                $query = $this->conexion->prepare($sql);

                $query->bindValue(':id', $id);

                $query->execute();

                header("HTTP/1.1 200 OK");
                exit();

            } catch (PDOException $e) {


            }

        }
        
        public function modificarEntrada(){

            try {

                $input = $_GET;

                $postId = $input['id'];

                $fields = getParams($input);
            
                $sql = "UPDATE entradasblog SET $fields WHERE id='$postId'";
            
                $query = $this->conexion->prepare($sql);

                bindAllValues($query, $input);
            
                $query->execute();
                
                header("HTTP/1.1 200 OK");
                exit();

            } catch (PDOException $e) {


            }

        }        

    }

    

?>