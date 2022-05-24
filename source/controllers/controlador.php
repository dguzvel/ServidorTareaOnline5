<?php

    //Llamamos a modelo.php
    require_once '../models/modelo.php';

     //Obtenemos los parámetros que serán modificados con el método PUT
     function getParams($input){

        $filterParams = [];
        
        //$input serán los valores obtenidos por $_GET, lo recorreremos y almacenaremos uno a uno en $param
        foreach($input as $param => $value){

            //Cada una de las posiciones almacenadas en el array contendrá el nombre de los parámetros a modificar
            $filterParams[] = "$param=:$param";

        }

        //Separamos cada elemento del array con comas
        return implode(", ", $filterParams);

    }

    //Asociamos la sentencia SQL con unos parámetros que vamos a introducirle
    function bindAllValues($query, $params){

        foreach($params as $param => $value){

            //Acada parámetro le añadimos un valor. Por ejemplo a :nick le añadimos el valor Juan1234
            $query->bindValue(':'.$param, $value);
            
        }

        return $query;

    }

    //Creamos un nuevo objeto tipo modelo
    $modelo = new modelo();

    //Si la petición del servidor es GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET'){

        //Si entre los parámetros se ha especificado una id concreta
        if (isset($_GET['id'])){
            
            //Se llama a la función de modelo que lista una entrada concreta mediante el id
            $modelo->listarUnaEntrada($_GET['id']);

        }else{

            //Si no se ha especificado ningún id se listan todas las entradas
            $modelo->listarEntradas();

        }

    }

    //Si la petición del servidor es POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){

        //Se llama a la función de modelo que inserta una nueva entrada en la base de datos
        $modelo->insertarEntrada();

    }

    //Si la petición del servidor es DELETE
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){

        //Se llama a la función de modelo que elimina una de las entradas de la base de datos
        $modelo->borrarEntrada();

    }

    //Si la petición del servidor es PUT
    if ($_SERVER['REQUEST_METHOD'] == 'PUT'){

        //Se llama a la función de modelo que mediante parámetros puede modificar los valores de algunos campos
        $modelo->modificarEntrada();

    }

    //En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

?>