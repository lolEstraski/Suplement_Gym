<?php

    /*
    Clase controlador de producto
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class productController{

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Incluir la vista*/
            require_once "views/product/Create.html";
        }

        /*Funcion para abrir ventana de inicio*/
        public function all(){
            /*Instanciar modelo*/
            $model = new Model();
            /*Establecer el id del usuario, fundador y superior como nulo*/
            $user_id = NULL;
            $founder = NULL;
            $higuer_user = NULL;
            /*Comprobar si el usuario esta logueado*/
            if(isset($_SESSION['loginsucces'])){
                /*Establecer el id del usuario y si es funder con el id del usuario logueado*/
                $user_id = $_SESSION['loginsucces']['USER_ID'];
                $founder = $_SESSION['loginsucces']['FOUNDER'];
                $higuer_user = $_SESSION['loginsucces']['HIGHER_USER_ID'];
            }
            /*Llamar la funcion del modelo*/
            $listProducts = $model -> getAllProducts($user_id, $founder, $higuer_user);
            /*Incluir la vista*/
            require_once "views/product/All.html";
        }

        /*Funcion para la busqueda de productos*/
        public function search(){
            /*Comprobar si llega el id enviado por get*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/  
                $name = isset($_POST['namebu']) ? $_POST['namebu'] : false;
                /*Asignar el dato si llega*/                
                if($name){
                    /*Instanciar modelo*/ 
                    $model = new Model();
                    /*Establecer el id del usuario, fundador y superior como nulo*/
                    $user_id = NULL;
                    $founder = NULL;
                    $higuer_user = NULL;
                    /*Comprobar si el usuario esta logueado*/
                    if(isset($_SESSION['loginsucces'])){
                        /*Establecer el id del usuario y si es funder con el id del usuario logueado*/
                        $user_id = $_SESSION['loginsucces']['USER_ID'];
                        $founder = $_SESSION['loginsucces']['FOUNDER'];
                        $higuer_user = $_SESSION['loginsucces']['HIGHER_USER_ID'];
                    }
                    /*Llamar la funcion del modelo*/ 
                    $listProducts = $model -> searchProducts($user_id, $founder, $higuer_user, $name);
                    /*Incluir la vista*/
                    require_once "views/product/Search.html";
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "?controller=userController&action=managementProducts");
            }
        }

        /*Funcion para abrir ventana de catalogo*/
        public function windowProducts(){
            /*Comprobar si el administrador esta logueado*/ 
            if(isset($_SESSION['loginsuccesa'])){
                /*Incluir la vista*/
                require_once "views/administrator/Home.html";
            /*De lo contrario*/ 
            }else{
                /*Instanciar modelo*/
                $model = new Model();
                /*Establecer el id del usuario, fundador y superior como nulo*/
                $user_id = NULL;
                $founder = NULL;
                $higuer_user = NULL;
                /*Comprobar si el usuario esta logueado*/
                if(isset($_SESSION['loginsucces'])){
                    /*Establecer el id del usuario y si es funder con el id del usuario logueado*/
                    $user_id = $_SESSION['loginsucces']['USER_ID'];
                    $founder = $_SESSION['loginsucces']['FOUNDER'];
                    $higuer_user = $_SESSION['loginsucces']['HIGHER_USER_ID'];
                }
                /*Obtener la lista de productos*/        
                $listProducts = $model -> productsList($user_id, $founder, $higuer_user);
                /*Obtener la lista de todos productos*/        
                $listAllProducts = $model -> getAllProducts($user_id, $founder, $higuer_user);
                /*Obtener la lista de noticias*/        
                $listNews = $model -> getsNews();
                /*Incluir la vista*/
                require_once "views/layout/Products.html";
            }
        }

        /*Funcion para abrir ventana de editar*/
        public function windowUpdate(){
            /*Comprobar si llega el id enviado por get*/
            if(isset($_GET)){
                /*Asignar el dato si llega*/                
                $product_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Asignar el dato si llega*/                
                if($product_id){
                    /*Instanciar modelo*/ 
                    $model = new Model();
                    /*Llamar la funcion del modelo*/ 
                    $product = $model -> getProduct($product_id);
                    /*Incluir la vista*/
                    require_once "views/product/Update.html";
                /*De lo contrario*/     
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error al cargar la ventana", "?controller=userController&action=managementProducts");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "controller=userController&action=managementProducts");
            }
        }

        /*Funcion para registrar*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $user_id = $_SESSION['loginsucces']['USER_ID'];
                $name = isset($_POST['namep']) ? $_POST['namep'] : false;
                $price = isset($_POST['price']) ? $_POST['price'] : false;
                $units = isset($_POST['units']) ? $_POST['units'] : false;
                $content = isset($_POST['contentp']) ? $_POST['contentp'] : false;
                $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
                $description = isset($_POST['description']) ? $_POST['description'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Comprobar si los datos llegan*/
                if ($user_id && $name && $price && $units && $content && $stock && $description) {
                    /*Comprobar si la foto es valida*/
                    $fotoGuardada = Helps::saveImage($file, "imagesProducts");
                    /*Comprobar si la foto ha sido guardada*/
                    if ($fotoGuardada) {
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion del modelo*/ 
                        $resultado = $model -> registerProduct($user_id, 1, $name, $price, $units, $content, $stock, $description, $image, $created_at2);
                        /*Comprobar si el registrado ha sido exitoso*/
                        if($resultado != -1){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el producto", "?controller=userController&action=managementProducts");
                        /*De lo contrario*/
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del producto", "?controller=productController&action=windowRegister");
                        }
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "El archivo no corresponde a una imagen", "?controller=productController&action=windowRegister");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=productController&action=windowRegister");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=productController&action=windowRegister");
            }
        }

        /*Funcion para ver el detalle*/
        public function detail(){
            /*Comprobar si el dato está llegando*/
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($id){
                    /*Instanciar el modelo*/
                    $model = new Model();
                    /*Llamar funcion que trae un producto en especifico*/
                    $resultado = $model -> detailProduct($id);
                    /*Comprobar si el producto ha llegado*/
                    if($resultado){
                        /*Incluir la vista*/
                        require_once 'views/product/Detail.html';
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errordetalle", "Ha ocurrido un error al intentar ver el producto", "?controller=productController&action=inicio");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errordetalle", "Ha ocurrido un error inesperado", "?controller=productController&action=inicio");
                }
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errordetalle", "Ha ocurrido un error inesperado", "?controller=productController&action=inicio");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET)){
                /*Comprobar si el dato existe*/
                $product_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($product_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que elimina el producto*/  
                    $resultado = $model -> deleteProduct($product_id);
                    /*Comprobar si el producto ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el producto", '?controller=userController&action=managementProducts');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del producto", '?controller=controller=userController&action=managementProducts');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=controller=userController&action=managementProducts');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=controller=userController&action=managementProducts");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Comprobar si llega el id enviado por get y los datos del producto por post*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $product_id = isset($_GET['id']) ? $_GET['id'] : false;
                $name = isset($_POST['name']) ? $_POST['name'] : false;
                $price = isset($_POST['price']) ? $_POST['price'] : false;
                $units = isset($_POST['units']) ? $_POST['units'] : false;
                $content = isset($_POST['content']) ? $_POST['content'] : false;
                $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
                $description = isset($_POST['description']) ? $_POST['description'] : false;
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Si el dato existe*/
                if($product_id){
                    /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                    if(Helps::comprobeImage($file['type']) != 3){
                        /*Comprobar si la foto tiene formato de imagen*/
                        if(Helps::comprobeImage($file['type']) == 1){
                            /*Comprobar si la foto ha sido validada y guardada*/
                            Helps::saveImage($file, "imagesProducts");
                        }
                        /*Instanciar modelo*/      
                        $model = new Model();
                        /*Llamar la funcion del modelo que actualiza el usuario*/  
                        $resultado = $model -> updateProduct($product_id, $name, $price, $units, $content, $stock, $description, $image);
                        /*Comprobar si el estado ha sido editado*/
                        if($resultado){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion del producto se ha realizado con exito", "?controller=productController&action=windowUpdate&id=$product_id");
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion del producto", "?controller=productController&action=windowUpdate&id=$product_id");
                        }
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "El archivo no corresponde a una imagen", "?controller=productController&action=windowUpdate&id=$product_id");
                    } 
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=productController&action=windowUpdate&id=$product_id");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=userController&action=managementProducts");
            }
        }

    }

?>