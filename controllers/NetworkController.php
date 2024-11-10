<?php

    /*
    Clase controlador de red
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class NetworkController{

        /*Funcion para abrir ventana de agregar usuario a la red*/
        public function windowAddUser(){
            /*Incluir la vista*/
            require_once "views/network/AddUser.html";
        }

        /*Funcion para agregar usuario a la red*/
        public function addUser(){
            /*Comprobar si llega el dato del formulario enviado por post*/
            if (isset($_POST)) {
                /*Obtener id del usuario logueado*/
                $userId = $_SESSION['loginsucces']['USER_ID'];
                /*Asignar el dato si llega*/
                $code = isset($_POST['code']) ? $_POST['code'] : false;
                /*Comprobar si el dato llega*/
                if($code){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que agrega el usuario a la red*/  
                    $resultado = $model -> addUser($userId, $code);
                    /*Comprobar si el registrado ha sido exitoso*/                  
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoasignacion", "El usuario ha sido asignado a tu red exitosamente", "?controller=networkController&action=windowAddUser");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error al realizar la asignacion del usuario a tu red", "?controller=networkController&action=windowAddUser");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=networkController&action=windowAddUser");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=networkController&action=windowAddUser");
            }
        }

    }

?>