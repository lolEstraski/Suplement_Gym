<?php

    /*
    Clase controlador de departamento
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class DepartmentController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/department/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $department_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($department_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene el departamento*/                    
                    $department = $model -> getDepartment($department_id);
                    /*Incluir la vista*/
                    require_once "views/department/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=managementDepartments");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=administratorController&action=managementDepartments");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $name = isset($_POST['namede']) ? $_POST['namede'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si el dato llega*/
                if($name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el departamento*/  
                    $resultado = $model -> registerDepartment(1, $name, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el departamento", "?controller=administratorController&action=windowManagementDepartments");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del departamento", "?controller=departmentController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=departmentController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=departmentController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $department_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($department_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina el departamento*/  
                    $resultado = $model -> deleteDepartment($department_id);
                    /*Comprobar si el departamento ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el departamento", '?controller=administratorController&action=windowManagementDepartments');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del departamento", '?controller=administratorController&action=windowManagementDepartments');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementDepartments');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementDepartments");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y el nombre por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si el dato existe*/
                $department_id = isset($_GET['id']) ? $_GET['id'] : false;
                $name = isset($_POST['namede']) ? $_POST['namede'] : false;
                /*Si el dato existe*/
                if($department_id && $name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el departamento*/  
                    $resultado = $model -> updateDepartment($department_id, $name);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion del departamento se ha realizado con exito", "?controller=administratorController&action=windowManagementDepartments");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion del departamento", "?controller=departmentController&action=windowUpdate&id=$department_id");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=departmentController&action=windowUpdate&id=$department_id");
                }
            /*De lo contrario*/        
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=departmentController&action=managementDepartments");
            }
        }

    }

?>