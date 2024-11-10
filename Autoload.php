<?php

    /*
    Clase para establecer la autocarga de clases
    */

    /*Auto carga*/
    function autocarga($clase){
        /*Ruta del directorio donde se encuentran los controladores*/
        $ruta = 'controllers/';
        /*Nombre del archivo del controlador*/
        $nombreArchivo = $ruta . $clase . '.php';
        /*Verificar si el archivo existe*/
        if(file_exists($nombreArchivo)){
            /*Incluir el archivo del controlador si existe*/
            include_once($nombreArchivo);
        }
    }

    /*Registrar la function autocarga para cargar automaticamente la clases si no han sido definidas*/
    spl_autoload_register('autocarga');

?>