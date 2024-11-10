<?php

    /*
    Clase controlador de estado de estado de la compra
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class PurchasingStatusController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/purchasingStatus/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $purchasing_status_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($purchasing_status_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene el estado de la compra*/                    
                    $purchasingStatus = $model -> getPurchasingStatus($purchasing_status_id);
                    /*Incluir la vista*/
                    require_once "views/purchasingStatus/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=managementPurchasingStatus");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=administratorController&action=managementPurchasingStatus");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $name = isset($_POST['psname']) ? $_POST['psname'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if($name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el estado de la compra*/  
                    $resultado = $model -> registerPurchasingStatus(1, $name, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el estado de la compra", "?controller=administratorController&action=windowManagementPurchasingStatues");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del estado de la compra", "?controller=purchasingStatusController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=purchasingStatusController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=purchasingStatusController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $purchasing_status_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($purchasing_status_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina el estado de la compra*/  
                    $resultado = $model -> deletePurchasingStatus($purchasing_status_id);
                    /*Comprobar si el estado de la compra ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el estado de la compra", '?controller=administratorController&action=windowManagementPurchasingStatues');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del estado de la compra", '?controller=administratorController&action=windowManagementPurchasingStatues');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementPurchasingStatues');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementPurchasingStatues");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y el nombre por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si el dato existe*/
                $purchasing_status_id = isset($_GET['id']) ? $_GET['id'] : false;
                $name = isset($_POST['nameps']) ? $_POST['nameps'] : false;
                /*Si el dato existe*/
                if($purchasing_status_id && $name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el estado de la compra*/  
                    $resultado = $model -> updatePurchasingStatus($purchasing_status_id, $name);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion del estado de la compra se ha realizado con exito", "?controller=administratorController&action=windowManagementPurchasingStatues");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion del estado de la compra", "?controller=purchasingStatusController&action=windowUpdate&id=$purchasing_status_id");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=purchasingStatusController&action=windowUpdate&id=$purchasing_status_id");
                }
            /*De lo contrario*/        
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=purchasingStatusController&action=managementPurchasingStatus");
            }
        }

    }

?>