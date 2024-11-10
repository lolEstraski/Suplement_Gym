<?php

    /*
    Clase controlador de direccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class DirectionController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene los generos guardados*/
            $listDepartments = $model -> getDepartments();
            /*Incluir la vista*/
            require_once "views/direction/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/
            if(isset($_GET)){
                /*Asignar el dato si llega*/
                $direction_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/
                if ($direction_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene la direccion y la lista de departamentos*/                    
                    $direction = $model -> getDirection($direction_id);
                    $listDepartments = $model -> getDepartments();
                    /*Incluir la vista*/
                    require_once "views/direction/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=userController&action=managementDirections");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=userController&action=managementDirections");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)){
                /*Asignar los datos si llegan*/
                $user_id = $_SESSION['loginsucces']['USER_ID'];
                $department = isset($_POST['department']) ? $_POST['department'] : false;
                $city = isset($_POST['city']) ? $_POST['city'] : false;
                $carrer = isset($_POST['carrer']) ? $_POST['carrer'] : false;
                $street = isset($_POST['street']) ? $_POST['street'] : false;
                $postal_code = isset($_POST['postalCode']) ? $_POST['postalCode'] : false;
                $direction = isset($_POST['direction']) ? $_POST['direction'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if($user_id && $city && $department && $carrer && $street && $postal_code && $direction){
                    /*Instanciar modelo*/                    
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra la direccion*/  
                    $resultado = $model -> registerDirection($user_id, $department, 1, $city, $carrer, $street, $postal_code, $direction, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente la direccion", "?controller=userController&action=managementDirections");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro de la direccion", "?controller=directionController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else {
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=directionController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=directionController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $direction_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($direction_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina la direccion*/  
                    $resultado = $model -> deleteDirection($direction_id);
                    /*Comprobar si la direccion ha sido eliminada con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el pago", '?controller=userController&action=managementDirections');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del pago", '?controller=userController&action=managementDirections');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=userController&action=managementDirections');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=userController&action=managementDirections");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $direction_id = isset($_GET['id']) ? $_GET['id'] : false;
                $department = isset($_POST['department']) ? $_POST['department'] : false;
                $city = isset($_POST['city']) ? $_POST['city'] : false;
                $carrer = isset($_POST['carrer']) ? $_POST['carrer'] : false;
                $street = isset($_POST['street']) ? $_POST['street'] : false;
                $postal_code = isset($_POST['postal_code']) ? $_POST['postal_code'] : false;
                $direction = isset($_POST['direction']) ? $_POST['direction'] : false;
                /*Si el dato existe*/
                if($direction_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza la direccion*/  
                    $resultado = $model -> updateDirection($direction_id, $department, $city, $carrer, $street, $postal_code, $direction);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion de la direccion se ha realizado con exito", "?controller=userController&action=managementDirections");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion de la direccion", "?controller=directionController&action=windowUpdate&id=$direction_id");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=directionController&action=windowUpdate&id=$direction_id");
                }
            /*De lo contrario*/        
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=directionController&action=managementDirections");
            }
        }

    }

?>