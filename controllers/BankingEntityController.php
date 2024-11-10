<?php

    /*
    Clase controlador de entidad bancaria
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class BankingEntityController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/bankEntity/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $bank_entity_controller = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($bank_entity_controller){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene la entidad bancaria*/                    
                    $bankEntity = $model -> getBankEntity($bank_entity_controller);
                    /*Incluir la vista*/
                    require_once "views/bankEntity/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=managementBankEntities");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=administratorController&action=managementBankEntities");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $name = isset($_POST['namebe']) ? $_POST['namebe'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if($name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra la entidad bancaria*/  
                    $resultado = $model -> registerBankEntity(1, $name, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente la entidad bancaria", "?controller=administratorController&action=windowManagementBankEntities");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro dla entidad bancaria", "?controller=bankEntityController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=bankEntityController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=bankEntityController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $bank_entity_controller = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($bank_entity_controller){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina la entidad bancaria*/  
                    $resultado = $model -> deleteBankEntity($bank_entity_controller);
                    /*Comprobar si la entidad bancaria ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente la entidad bancaria", '?controller=administratorController&action=windowManagementBankEntities');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion dla entidad bancaria", '?controller=administratorController&action=windowManagementBankEntities');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementBankEntities');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementBankEntities");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y el nombre por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si el dato existe*/
                $bank_entity_controller = isset($_GET['id']) ? $_GET['id'] : false;
                $name = isset($_POST['namebe']) ? $_POST['namebe'] : false;
                /*Si el dato existe*/
                if($bank_entity_controller && $name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza la entidad bancaria*/  
                    $resultado = $model -> updateBankEntity($bank_entity_controller, $name);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion dla entidad bancaria se ha realizado con exito", "?controller=administratorController&action=windowManagementBankEntities");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion dla entidad bancaria", "?controller=bankEntityController&action=windowUpdate&id=$bank_entity_controller");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=bankEntityController&action=windowUpdate&id=$bank_entity_controller");
                }
            /*De lo contrario*/        
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=bankEntityController&action=managementBankEntities");
            }
        }

    }

?>