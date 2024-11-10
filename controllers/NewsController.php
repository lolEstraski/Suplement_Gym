<?php

    /*
    Clase controlador de la noticia
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class NewsController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/news/Create.html";
        }

        /*Funcion para abrir ventana de todas las noticias*/
        public function all(){
            /*Instanciar modelo*/ 
            $model = new Model();
            /*Llamar la funcion del modelo*/ 
            $listNews = $model -> getAllNews();
            /*Incluir la vista*/
            require_once "views/news/All.html";
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/            
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $news_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if ($news_id){
                    /*Instanciar modelo*/                      
                    $model = new Model();
                    /*Llamar la funcion del modelo que obtiene la noticia*/                    
                    $news = $model -> getNews($news_id);
                    /*Incluir la vista*/
                    require_once "views/news/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=managementNews");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreditar", "Ha ocurrido un error inesperado", "controller=administratorController&action=managementNews");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $title = isset($_POST['title']) ? $_POST['title'] : false;
                $content = isset($_POST['content']) ? $_POST['content'] : false;
                $link = isset($_POST['link']) ? $_POST['link'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Comprobar si los datos llegan*/
                if($title && $content && $link){
                    /*Comprobar si la foto es valida*/
                    $fotoGuardada = Helps::saveImage($file, "imagesNews");
                    /*Comprobar si la foto ha sido guardada*/
                    if($fotoGuardada){
                        /*Lamar funcion auxiliar que quita caracteres especiales*/
                        $title = Helps::removeSpecialCharacters($title);
                        $content = Helps::removeSpecialCharacters($content);
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion del modelo*/ 
                        $resultado = $model -> registerNews(1, $title, $content, $link, $image, $created_at2);
                        /*Comprobar si el registrado ha sido exitoso*/
                        if($resultado != -1){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente la noticia", "?controller=administratorController&action=windowManagementNews");
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro de la noticia", "?controller=administratorController&action=windowRegister");
                        }
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "El archivo no corresponde a una imagen", "?controller=administratorController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegister");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $news_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($news_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina la noticia*/  
                    $resultado = $model -> deleteNews($news_id);
                    /*Comprobar si la noticia ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente la noticia", '?controller=administratorController&action=windowManagementNews');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion de la noticia", '?controller=administratorController&action=windowManagementNews');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementNews');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementNews");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y los datos del producto por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $news_id = isset($_GET['id']) ? $_GET['id'] : false;
                $title = isset($_POST['title']) ? $_POST['title'] : false;
                $content = isset($_POST['content']) ? $_POST['content'] : false;
                $link = isset($_POST['link']) ? $_POST['link'] : false;
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Si el dato existe*/
                if($news_id){
                    /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                    if(Helps::comprobeImage($file['type']) != 3){
                        /*Comprobar si la foto tiene formato de imagen*/
                        if(Helps::comprobeImage($file['type']) == 1){
                            /*Comprobar si la foto ha sido validada y guardada*/
                            Helps::saveImage($file, "imagesNews");
                        }
                        /*Instanciar modelo*/      
                        $model = new Model();
                        /*Llamar la funcion del modelo que actualiza el usuario*/  
                        $resultado = $model -> updateNews($news_id, $title, $content, $link, $image);
                        /*Comprobar si el estado ha sido editado*/
                        if($resultado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion de la noticia se ha realizado con exito", "?controller=administratorController&action=windowManagementNews");
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion de la noticia", "?controller=newsController&action=windowUpdate&id=$news_id");
                        }
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "El archivo no corresponde a una imagen", "?controller=newsController&action=windowUpdate&id=$news_id");
                    } 
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=newsController&action=windowUpdate&id=$news_id");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=managementNews");
            }
        }

    }

?>