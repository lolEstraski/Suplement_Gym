<?php

    /*
    Clase controlador de genero
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class GenreController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/genre/Create.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $genre_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($genre_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene el genero*/                    
                    $genre = $model -> getGenre($genre_id);
                    /*Incluir la vista*/
                    require_once "views/genre/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=managementGenres");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=administratorController&action=managementGenres");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $name = isset($_POST['nameg']) ? $_POST['nameg'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si los datos llegan*/
                if($name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que registra el genero*/  
                    $resultado = $model -> registerGenre(1, $name, $created_at2);
                    /*Comprobar si el registrado ha sido exitoso*/                    
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el genero", "?controller=administratorController&action=windowManagementGenres");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del genero", "?controller=genreController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=genreController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=genreController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $genre_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($genre_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina el genero*/  
                    $resultado = $model -> deleteGenre($genre_id);
                    /*Comprobar si el genero ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el genero", '?controller=administratorController&action=windowManagementGenres');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del genero", '?controller=administratorController&action=windowManagementGenres');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementGenres');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementGenres");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y el nombre por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si el dato existe*/
                $genre_id = isset($_GET['id']) ? $_GET['id'] : false;
                $name = isset($_POST['name']) ? $_POST['name'] : false;
                /*Si el dato existe*/
                if($genre_id && $name){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el genero*/  
                    $resultado = $model -> updateGenre($genre_id, $name);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion del genero se ha realizado con exito", "?controller=administratorController&action=windowManagementGenres");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion del genero", "?controller=genreController&action=windowUpdate&id=$genre_id");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=genreController&action=windowUpdate&id=$genre_id");
                }
            /*De lo contrario*/        
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=genreController&action=managementGenres");
            }
        }

    }

?>