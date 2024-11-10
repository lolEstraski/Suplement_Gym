<?php

    /*
    Archivo de conexion a la base de datos
    */

    /*Funcion para conectar la base de datos*/
    function connectDb(){
        /*Conectar a la base de datos*/
        $conn = oci_connect('ANDRES', 'root', 'localhost/xe');
        /*Comprobar si la conexion no es exitosa*/
        if (!$conn) {
            $e = oci_error();
            echo "Error al conectarse a Oracle: " . $e['message'];
            exit;
        }
        /*Retornar el resultado*/
        return $conn;
    }

?>