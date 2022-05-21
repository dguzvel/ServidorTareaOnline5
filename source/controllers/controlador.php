<?php

    require_once '../models/modelo.php';

     //Obtener parametros para updates
     function getParams($input){

        $filterParams = [];
        
        foreach($input as $param => $value){

            $filterParams[] = "$param=:$param";

        }

        return implode(", ", $filterParams);

    }

    //Asociar todos los parametros a un sql
    function bindAllValues($query, $params){

        foreach($params as $param => $value){

            $query->bindValue(':'.$param, $value);
            
        }

        return $query;

    }

    $modelo = new modelo();

    if ($_SERVER['REQUEST_METHOD'] == 'GET'){

        if (isset($_GET['id'])){
    
            $modelo->listarUnaEntrada($_GET['id']);

        }else{

            $modelo->listarEntradas();

        }

    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        $modelo->insertarEntrada();

    }

    if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        $modelo->borrarEntrada();

    }

    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){

        $modelo->modificarEntrada();

    }

    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

?>