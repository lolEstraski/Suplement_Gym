<?php

    /*
    Clase controlador de usuario
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    class UserController{

        /*Funcion para abrir ventana de login*/
        public function windowlogin(){
            /*Incluir la vista*/
            require_once "views/user/Login.html";
        }

        /*Funcion para abrir ventana de cambiar clave*/
        public function windowChangePassword(){
            /*Incluir la vista*/
            require_once "views/user/ChangePassword.html";
        }

        /*Funcion para abrir la venta de motivo de desvinculacion del usuarios*/
        public function windowDisasociate(){
            /*Obtener id del usuario logueado*/
            $idUser = $_SESSION['loginsucces']['USER_ID'];
            /*Incluir la vista*/
            require_once "views/user/Disasociate.html";
        }

        /*Funcion para abrir ventana de registro*/
        public function windowRegister(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene los generos guardados*/
            $listGenres = $model -> getGenres();
            /*Incluir la vista*/
            require_once "views/user/Register.html";
        }

        /*Funcion para abrir ventana de gestion de productos*/
        public function managementProducts(){
            /*Instanciar el modelo*/
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene los productos*/  
            $listProducts = $model -> productsListManagement($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/user/ManagementProducts.html";
        }

        /*Funcion para abrir ventana de gestion de direcciones*/
        public function managementDirections(){
            /*Instanciar el modelo*/
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene las direcciones*/            
            $listDirections = $model -> directionListManagement($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/user/ManagementDirections.html";
        }
        
        /*Funcion para abrir ventana de gestion de pagos*/
        public function managementPays(){
            /*Instanciar el modelo*/            
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene los pagos*/  
            $listPays = $model -> payListManagement($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/user/ManagementPays.html";
        }

        /*Funcion para abrir ventana de mi perfil*/
        public function myProfile(){
            /*Incluir la vista*/
            require_once "views/user/MyProfile.html";
        }

        /*Funcion para abrir ventana de mis compras*/
        public function myShops(){
            /*Instanciar el modelo*/            
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene las compras*/  
            $list = $model -> shoppingList($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/user/MyShops.html";
        }

        /*Funcion para abrir ventana de mis ventas*/
        public function mySales(){
            /*Instanciar el modelo*/            
            $model = new Model();
            /*Llamar la funcion del modelo que obtiene las ventas*/  
            $list = $model -> salesList($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/user/MySales.html";
        }

        /*Funcion para cerrar sesion*/
        public function logout(){
            /*Llamar al archivo de ayudas*/
            Helps::deleteSession("loginsucces");
            Helps::deleteSession("loginsuccesa");
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("aciertologout", "Sesión cerrada con exito", "?controller=userController&action=windowlogin");
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
                    $resultado = $model -> login($email, $password);
                    /*Comprobar si el usuario existe*/
                    if($resultado != NULL){
                        /*Crear sesion de inicio de sesion exitoso*/
                        $_SESSION['loginsucces'] = $resultado;
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoiniciosesion", "Has ingresado exitosamente a Suplement Gym", "?controller=productController&action=windowProducts");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroriniciarsesion", "Este usuario no se encuentra registrado", "?controller=userController&action=windowlogin");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowlogin");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroriniciarsesion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowlogin");
            }
        }

        /*Funcion para cambiar la contraseña*/
        public function changePassword(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Obtener usuario logueado*/
                $user = $_SESSION['loginsucces'];
                /*Comprobar si el dato existe*/
                $user_id = isset($user['USER_ID']) ? $user['USER_ID'] : false;
                $newPassword = isset($_POST['passwordn']) ? $_POST['passwordn'] : false;
                $oldPassword = isset($_POST['passwordo']) ? $_POST['passwordo'] : false;
                /*Comprobar si los datos llegan*/
                if($user_id && $newPassword && $oldPassword){
                    /*Comprobar si la clave cumple los parametros de seguridad*/
                    if(Helps::validatePassword($newPassword)){
                        /*Comprobar si la clave antigua coincide*/
                        if(Helps::checkKeys($oldPassword)){
                            /*Instanciar modelo*/
                            $model = new Model();
                            /*Llamar la funcion que actualizas la clave*/
                            $change = $model -> changePassword($user_id, $newPassword);
                            /*Comprobar si el cambio ha sido exitoso*/
                            if($change){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("aciertoactualizacion", "Clave actualizada con exito", "?controller=userController&action=windowChangePassword");
                            }
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("erroractualizacion", "Clave actual incorrecta", "?controller=userController&action=windowChangePassword");
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizacion", "La clave no cumple los parametros de seguridad", "?controller=userController&action=windowChangePassword");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizacion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowChangePassword");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizacion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowChangePassword");
            }
        }

        /*Funcion para que un usuario se registre*/
        public function register(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Llamar la funcion que genera codigo aleatorio*/
                $code = helps::generateRandomCode();
                /*Asignar los datos si llegan*/
                $name = isset($_POST['nameu']) ? $_POST['nameu'] : false;
                $surname = isset($_POST['surname']) ? $_POST['surname'] : false;
                $genre_id = isset($_POST['genre']) ? $_POST['genre'] : false;
                $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : false;
                $phone = isset($_POST['phone']) ? $_POST['phone'] : false;
                $email = isset($_POST['email']) ? $_POST['email'] : false;
                $password = isset($_POST['password']) ? $_POST['password'] : false;
                $created_at = date('Y-m-d');
                $birthdate2 = (new DateTime($birthdate))->format('Y-m-d'); // Asegúrate de que la fecha esté en formato 'Y-m-d' para Oracle
                $earnings = 0;
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Establecer archivo de foto*/
                $file = $_FILES['image'];
                /*Establecer nombre del archivo de la foto*/
                $image = $file['name'];
                /*Comprobar si los datos llegan*/
                if($code && $name && $surname && $birthdate && $genre_id && $email && $password && $file){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Validar si la clave cumple con la seguridad necesaria*/
                    if(helps::validatePassword($password)){
                        /*Validar si el email no esta registrado*/
                        if($model -> validateUniqueEmail($email) != 1){
                            /*Comprobar si la foto es valida*/
                            $fotoGuardada = Helps::saveImage($file, "imagesUsers");
                            /*Comprobar si la foto ha sido guardada*/
                            if($fotoGuardada){
                                /*Llamar la funcion del modelo que registra el usuario*/  
                                $resultado = $model -> registerUser($genre_id, 1, 0, $code, $name, $surname, $birthdate2, $phone, $email, $password, $image, $earnings, NULL, $created_at2);
                                /*Comprobar si el registro se ha hecho de manera exitosa*/
                                if($resultado != false){
                                    /*Crear sesion de inicio de sesion exitoso*/
                                    $_SESSION['loginsucces'] = $resultado;
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Helps::createSessionAndRedirect("aciertoiniciosesion", "Bienvenido a Suplement_Gym", "?controller=productController&action=windowProducts");
                                /*De lo contrario*/
                                }else{
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Helps::createSessionAndRedirect("erroregistro", "Debes ser mayor de 18 años para poder registrarte", "?controller=userController&action=windowRegister");
                                }
                            /*De lo contrario*/    
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("erroregistro", "El archivo no corresponde a una imagen", "?controller=userController&action=windowRegister");
                            }
                        /*De lo contrario*/    
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("erroregistro", "Esta direccion de correo ya se encuentra asociada a un usuario", "?controller=userController&action=windowRegister");
                        }
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroregistro", "La clave no cumple los parametros de seguridad", "?controller=userController&action=windowRegister");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroregistro", "Ha ocurrido un error inesperado", "?controller=userController&action=windowRegister");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroregistro", "Ha ocurrido un error inesperado", "?controller=userController&action=windowRegister");
            }
        }

        /*Funcion para desvincularse de la red*/
        public function disasociate(){
            /*Comprobar si llega el dato del formulario enviado por post*/
            if(isset($_GET)){
                /*Obtener id del usuario logueado*/
                $userId = $_GET['id'];
                /*Comprobar si el dato llega*/
                if($userId){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que agrega el usuario a la red*/  
                    $resultado = $model -> disasociate($userId);
                    /*Comprobar si el registrado ha sido exitoso*/                  
                    if($resultado != false){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertodesvinculacion", "Te has desvinculado exitosamente", "?controller=productController&action=windowProducts");
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errordesvinculacion", "Ha ocurrido un error al realizar la desvinculacion", "?controller=userController&action=windowDisasociate");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errordesvinculacion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowDisasociate");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errordesvinculacion", "Ha ocurrido un error inesperado", "?controller=userController&action=windowDisasociate");
            }
        }

        /*Funcion para eliminar*/
        public function delete(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_GET)){
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
                        Helps::createSessionAndRedirect('eliminaracierto', "Se ha eliminado exitosamente el usuario", '?controller=productController&action=windowProducts');
                        Helps::deleteSession('loginsucces');
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('erroreliminar', "Ha ocurrido un error al realizar la eliminacion del usuario", '?controller=userController&action=myProfile');
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=userController&action=myProfile");
                }
            /*De lo contrario*/ 
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=userController&action=myProfile");
            }
        }

        /*Funcion para actualizar*/
        public function update(){
            /*Obtener usuario logueado*/
            $user = $_SESSION['loginsucces'];
            /*Comprobar si el dato existe*/
            $user_id = isset($user['USER_ID']) ? $user['USER_ID'] : false;
            $name = isset($_POST['name']) ? $_POST['name'] : false;
            $surname = isset($_POST['surname']) ? $_POST['surname'] : false;
            $phone = isset($_POST['phone']) ? $_POST['phone'] : false;
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            /*Establecer archivo de foto*/
            $file = $_FILES['image'];
            /*Establecer nombre del archivo de la foto*/
            $image = $file['name'];
            /*Si el dato existe*/
            if($user_id){
                /*Comprobar si la foto no tiene formato de imagen o no ha llegado*/
                if(Helps::comprobeImage($file['type']) != 3){
                    /*Comprobar si la foto tiene formato de imagen*/
                    if(Helps::comprobeImage($file['type']) == 1){
                        /*Comprobar si la foto ha sido validada y guardada*/
                        Helps::saveImage($file, "imagesUsers");
                    }
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el usuario*/  
                    $resultado = $model -> updateUser($user_id, $name, $surname, $phone, $email, $image);
                    /*Comprobar si el estado ha sido editado*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoactualizar", "La actualizacion del usuario se ha realizado con exito", "?controller=userController&action=myProfile");
                    /*De lo contrario*/    
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error al realizar la actualizacion del usuario", "?controller=userController&action=myProfile");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroractualizar", "El archivo no corresponde a una imagen", "?controller=userController&action=myProfile");
                } 
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroractualizar", "Ha ocurrido un error inesperado", "?controller=userController&action=myProfile");
            }
        }

    }

?>