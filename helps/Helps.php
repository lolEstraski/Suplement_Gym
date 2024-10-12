<?php

    /*
    Clase de archivo de ayudas
    */

    class Helps{

        /*Funcion para crear sesiones y redigirir a paginas*/
        public static function createSessionAndRedirect($nombreSesion, $contenidoSesion, $ruta){
            /*Crear sesion*/
            $_SESSION[$nombreSesion] = $contenidoSesion;
            /*Redirigir*/
            header("Location:"."http://localhost/Suplement_Gym/".$ruta);
        }

        /*Funcion para eliminar sesiones*/
        public static function deleteSession($nombreSesion){
            /*Comprobar si la sesion existe*/
            if(isset($_SESSION[$nombreSesion])){
                /*Eliminar sesion*/
                unset($_SESSION[$nombreSesion]);
            }
        }

        /*Funcion para comprobar el tipo de un archivo*/
        public static function comprobeImage($archivo){
            /*Comprobar si el archivo cumple las condiciones de formato de imagen*/
            if($archivo == "image/jpg" || $archivo == "image/jpeg" || $archivo == "image/png" || $archivo == "image/gif"){
                /*Retornar el resultado*/
                return 1;
            /*Comprobar si el archivo esta vacio*/
            }else if($archivo == ''){
                /*Retornar el resultado*/
                return 2;
            /*De lo contrario*/
            }else{
                /*Retornar el resultado*/
                return 3;
            }
        }

        /*Funcion para guardar la imagen del administrador en los archivos*/
        public static function saveImage($archivo, $carpetaGuardada){
            /*Comprobar si existe el archivo o este llega*/
            if(isset($archivo)){
                /*Extraer el tipo de archivo de la imagen*/
                $tipoArchivo = $archivo['type'];
                /*Comprobar si el archivo tiene la extensión de una imagen*/
                if(Helps::comprobeImage($tipoArchivo) == 1){
                    /*Extraer nombre del archivo de imagen*/
                    $nombreArchivo = $archivo['name'];
                    /*Comprobar si no existe un directorio para las imagenes a subir*/
                    if(!is_dir('resources/'.$carpetaGuardada)){
                        /*Crear el directorio*/
                        mkdir('resources/'.$carpetaGuardada, 0777, true);
                    }
                    /*Mover la foto subida a la ruta temporal del servidor y luego a la de la carpeta de las imagenes*/
                    move_uploaded_file($archivo['tmp_name'], 'resources/'.$carpetaGuardada.'/'.$nombreArchivo);
                }
            }
            /*Retornar el resultado*/
            return $nombreArchivo;
        }

        /*Funcion para validar la clave y que sea segura*/
        public static function validatePassword($contrasena) {
            /*Verificar todos los patrones*/
            if (strlen($contrasena) >= 5 && preg_match('/[A-Z]/', $contrasena) && preg_match('/[a-z]/', $contrasena) && preg_match('/\d/', $contrasena) && preg_match('/[\W_]/', $contrasena)) {
                /*Retornar el resultado*/
                return true;
            }
        }

        /*Funcion para generar un codigo aleatorio*/
        public static function generateRandomCode() {
            $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()-_=+[]{}|;:,.<>?';
            $codigo = '';
            $maxIndex = strlen($caracteres) - 1;
            /*Recorrer la palabra*/
            for ($i = 0; $i < 10; $i++) {
                $indiceAleatorio = random_int(0, $maxIndex);
                $codigo .= $caracteres[$indiceAleatorio];
            }
            /*Retornar el resultado*/
            return $codigo;
        }

        /*Funcion para comprobar la clave*/
        public static function checkKeys($password){
            /*instanciar el modelo*/
            $model = new Model();
            /*Capturar el correo del usuario ingresado*/
            $email = $_SESSION['loginsucces']['EMAIL'];
            /*Obtener clave actual del usuario logueado*/
            $passwordUser = $model -> getPassword($email);
            /*Verificar clave actual y nueva*/
            $passwordVerified = password_verify($password, $passwordUser);
            /*Comprobar si la clave actual y nueva coinciden*/
            if($passwordVerified){
                /*Retornar el resultado*/
                return true;
            }
        }

        /*Funcion para listar los datos del usuario*/
        public static function showUserInformation(){
            /*Comprobar si hay un inicio de sesion de usuario*/
            if(isset($_SESSION['loginsucces'])){
                /*Incluir el modelo*/
                require_once 'models/Model.php';
                /*Instanciar el objeto*/
                $model = new Model();
                /*Listar todos los usuarios desde la base de datos*/
                $datos = $model -> getUser($_SESSION['loginsucces']['USER_ID']);
                /*Retornar el resultado*/
                return $datos;
            }
        }

        /*Funcion para remover los caracteres especiales*/
        public static function removeSpecialCharacters($text){
            $search = array(
                'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 
                'ñ', 'Ñ', 'ü', 'Ü'
            );
            $reeplace = array(
                'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 
                'n', 'N', 'u', 'U'
            );
            // Reemplaza los caracteres acentuados por sus equivalentes sin acento
            $replacText = str_replace($search, $reeplace, $text);
            return $replacText;
        }

    }

?>    