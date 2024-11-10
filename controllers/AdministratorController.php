<?php

    /*
    Clase controlador de administrador
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class AdministratorController{

        /*Funcion para abrir ventana de login*/
        public function windowlogin(){
            /*Incluir la vista*/
            require_once "views/administrator/Login.html";
        }

        /*Funcion para abrir ventana de editar producto*/
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
                    Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error al cargar la ventana", "?controller=administratorController&action=windowManagementProducts");
                }
            /*De lo contrario*/      
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementProducts");
            }
        }

        /*Funcion para abrir ventana de registro de producto*/
        public function windowRegisterProduct(){
            /*Incluir la vista*/
            require_once "views/administrator/CreateProduct.html";
        }

        /*Funcion para abrir la pantalla de inicio*/
        public function home(){
            /*Incluir la vista*/
            require_once "views/administrator/Home.html";
        }

        /*Funcion para abrir la ventana de gestion de los productos*/
        public function windowManagementProducts(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener la lista de los productos creados por los usuarios*/
            $listProducts = $model -> getProducts(); 
            /*Obtener la lista de los productos creados por el administrador*/  
            $listProductsAdmin = $model -> getProductsAdmin();          
            /*Incluir la vista*/
            require_once "views/administrator/ManagementProducts.html";
        }

        /*Funcion para abrir la ventana de gestion de los usuarios*/
        public function windowManagementUsers(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de usuarios*/
            $list = $model -> getUsers();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementUsers.html";
        }

        /*Funcion para abrir la ventana de ventas hechas por la tienda*/
        public function windowSalesAdministrator(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene las ventas realizadas*/  
            $list = $model -> salesListAdministrator();
            /*Incluir la vista*/
            require_once "views/administrator/Sales.html";
        }

        /*Funcion para ver el detalle de la venta*/
        public function detailSaleAdministrator(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener el detalle de la venta*/
            $detail = $model -> detailSaleAdministrator($_GET['id']);
            /*Total de la compra*/
            $total = 0;
            /*Obtener la lista de estados de pago*/
            $listPurchasingStatus = $model -> getPurchasingStatues();
            /*Incluir la vista*/
            require_once "views/administrator/DetailSale.html";
        }

        /*Funcion para cambiar el estado de la compra*/
        public function changeStatusAdministrator(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $transaction_product = isset($_GET['id']) ? $_GET['id'] : false;
                $purchasingStatus = isset($_POST['purchaseStatus']) ? $_POST['purchaseStatus'] : false;
                /*Si los datos existen*/
                if($transaction_product && $purchasingStatus){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el estado de la compra*/  
                    $resultado = $model -> changeStatus($transaction_product, $purchasingStatus);
                    /*Comprobar si el estado de la compra se ha actualizado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertocambio', "Se ha actualizado exitosamente el estado de la compra", "?controller=administratorController&action=windowSalesAdministrator");
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('errorcambio', "Ha ocurrido un error al realizar la actualizacion de la compra", "?controller=administratorController&action=detailSaleAdministrator&id=$transaction_product");
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('errorcambio', "Ha ocurrido un error inesperado", "?controller=administratorController&action=detailSaleAdministrator&id=$transaction_product");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorcambio", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowSalesAdministrator");
            }
        }

        /*Funcion para abrir la ventana de la piramide*/
        public function pyramid(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener los usuarios y piramide*/
            $pyramid = $model -> piramyd();
            /*Crear un arreglo para organizar los usuarios por nivel*/
            $niveles = [];
            foreach ($pyramid as $usuario) {
                $nivel = $usuario['HIERARCHY_LEVEL'];
                $niveles[$nivel][] = $usuario;
            }
            /*Incluir la vista*/
            require_once "views/administrator/Pyramid.html";
        }

        /*Funcion para abrir la ventana de gestion de los generos*/
        public function windowManagementGenres(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de generos*/
            $list = $model -> getGenres();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementGenres.html";
        }

        /*Funcion para abrir la ventana de gestion de los departamentos*/
        public function windowManagementDepartments(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de departamentos*/
            $list = $model -> getDepartments();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementDepartments.html";
        }

        /*Funcion para abrir la ventana de reportes*/
        public function windowReports(){
            /*Incluir la vista*/
            require_once "views/administrator/Reports.html";
        }

        /*Funcion para abrir la ventana de gestion de los estados de la compra*/
        public function windowManagementPurchasingStatues(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de estados de compra*/
            $list = $model -> getPurchasingStatues();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementPurchasingStatues.html";
        }

        /*Funcion para abrir la ventana de gestion de las noticias*/
        public function windowManagementNews(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de noticias*/
            $list = $model -> newsManagement();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementNews.html";
        }

        /*Funcion para abrir la ventana de gestion de las entidades bancarias*/
        public function windowManagementBankEntities(){
            /*Instanciar modelo*/  
            $model = new Model();
            /*Obtener lista de entidades bancarias*/
            $list = $model -> getBankEntities();
            /*Incluir la vista*/
            require_once "views/administrator/ManagementBankEntities.html";
        }

        /*Funcion para abrir ventana para la asignacion de usuarios fundadores*/
        public function windowAddUser(){
            /*Incluir la vista*/
            require_once "views/administrator/AddUser.html";
        }

        /*Funcion para eliminar el usuario*/
        public function deleteUser(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_GET)) {
                /*Asignar los datos si llegan*/
                $user_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($user_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Obtener fecha de hoy*/
                    $deleted_at = date('Y-m-d');
                    $deleted_at2 = (new DateTime($deleted_at))->format('d/m/y');
                    /*Llamar la funcion del modelo que elimina el usuario*/  
                    $resultado = $model -> deleteUser($user_id, $deleted_at2);
                    /*Comprobar si el usuario ha sido eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('eliminaracierto', "Se ha eliminado exitosamente el usuario", '?controller=administratorController&action=windowManagementUsers');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del usuario", '?controller=administratorController&action=windowManagementUsers');
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementUsers");
                }
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementUsers");
            }
        }

        /*Funcion para recuperar el usuario*/
        public function recovery(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_GET)) {
                /*Asignar los datos si llegan*/
                $user_id = isset($_GET['id']) ? $_GET['id'] : false;
                /*Si el dato existe*/
                if($user_id){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que recupera el usuario*/  
                    $resultado = $model -> recoveryUser($user_id);
                    /*Comprobar si el usuario ha sido recuperado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('recuperaracierto', "Se ha recuperado exitosamente el usuario", '?controller=administratorController&action=windowManagementUsers');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('recuperarerror', "Ha ocurrido un error al realizar la recuperacion del usuario", '?controller=administratorController&action=windowManagementUsers');
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("recuperarerror", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementUsers");
                }
            /*De lo contrario*/ 
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("recuperarerror", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementUsers");
            }
        }

        /*Funcion para agregar usuarios fundadores*/
        public function addUser(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar el dato si llega*/
                $code = isset($_POST['code']) ? $_POST['code'] : false;
                /*Comprobar si el dato llega*/
                if($code){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que asigna los usuarios fundadores*/  
                    $resultado = $model -> assignFounder($code);
                    /*Comprobar si la asignacion ha sido exitosa*/                  
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoasignacion", "Se ha asignado el usuario fundador exitosamente", "?controller=administratorController&action=windowAddUser");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error al asignar el usuario fundador", "?controller=administratorController&action=windowAddUser");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowAddUser");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorasignacion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowAddUser");
            }
        }

        /*Funcion para eliminar un product*/
        public function deleteProduct(){
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
                        Helps::createSessionAndRedirect('aciertoeliminar', "Se ha eliminado exitosamente el producto", '?controller=administratorController&action=windowManagementProducts');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del producto", '?controller=administratorController&action=windowManagementProducts');
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error inesperado", '?controller=administratorController&action=windowManagementProducts');
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowManagementProducts");
            }
        }

        /*Funcion para registrar un producto*/
        public function registerProduct(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $name = isset($_POST['name']) ? $_POST['name'] : false;
                $price = isset($_POST['price']) ? $_POST['price'] : false;
                $units = isset($_POST['units']) ? $_POST['units'] : false;
                $content = isset($_POST['content']) ? $_POST['content'] : false;
                $stock = isset($_POST['stock']) ? $_POST['stock'] : false;
                $description = isset($_POST['description']) ? $_POST['description'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Comprobar si los datos llegan*/
                if($name && $price && $units && $content && $stock && $description){
                    /*Comprobar si la foto es valida*/
                    $fotoGuardada = Helps::saveImage($file, "imagesProducts");
                    /*Comprobar si la foto ha sido guardada*/
                    if($fotoGuardada){
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion del modelo*/ 
                        $resultado = $model -> registerProduct(NULL, 1, $name, $price, $units, $content, $stock, $description, $image, $created_at2);
                        /*Comprobar si el registrado ha sido exitoso*/
                        if($resultado != -1){
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("aciertoregistro", "Se ha registrado exitosamente el producto", "?controller=administratorController&action=windowManagementProducts");
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error al realizar el registro del producto", "?controller=administratorController&action=windowRegisterProduct");
                        }
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorregistro", "El archivo no corresponde a una imagen", "?controller=administratorController&action=windowRegisterProduct");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegisterProduct");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorregistro", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowRegisterProduct");
            }
        }

        /*Funcion para iniciar de sesion*/
        public function login(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $password = isset($_POST['password']) ? $_POST['password'] : false;
                /*Comprobar si los datos llegan*/
                if($email && $password){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion del modelo que valida las credenciales de acceso*/  
                    $resultado = $model -> logina($email, $password);
                    /*Comprobar si el usuario existe*/
                    if($resultado != NULL){
                        /*Crear sesion de inicio de sesion exitoso*/
                        $_SESSION['loginsuccesa'] = 'Admin logueado';
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoiniciarsesion", "Bienvenido a Suplement_Gym", "?controller=administratorController&action=home");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroriniciarsesion", "Este administrador no se encuentra registrado", "?controller=administratorController&action=windowlogin");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowlogin");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowlogin");
            }
        }

    }

?>