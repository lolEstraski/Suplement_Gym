<?php

    /*Incluir la configuracion de la base de datos*/
    require_once 'configuration/Db.php';

    /*
    Clase modelo
   */

    class Model {

        /*Atributo de conexion*/
        private $conn;

        /*Funcion constructor*/
        public function __construct(){
            /*Establecer conexion*/
            $this->conn = connectDb();
        }

        /*Funcion para que el usuario se loguee, comprobando desde la base de datos si los datos son validos*/
        public function login($email, $password){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := LOGIN(:email); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $cursor = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($cursor);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $userData = oci_fetch_assoc($cursor);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /*Verificar si el usuario fue encontrado y si la contraseña es correcta*/ 
            if($userData && password_verify($password, $userData['USER_PASSWORD'])){
                /*Retornar el resultado*/
                return $userData;
            }else{
                /*Retornar el resultado*/
                return null;
            }
        }
        
        /*Funcion para que el administrador se loguee, comprobando desde la base de datos si los datos son validos*/
        public function logina($email, $password){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := LOGINA(:email, :password); END;';
            $stid = oci_parse($this->conn, $query);
            /*Variable para almacenar el resultado numérico (1 o 0)*/
            $resultado = 0;
            /*Asignar los valores de entrada y el resultado de salida*/
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':password', $password);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, SQLT_INT);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Liberar recursos*/
            oci_free_statement($stid);
            /*Verificar si el resultado es 1 (usuario existe)*/
            if($resultado == 1){
                /*Retornar el resultado*/
                return 1;
            }else{
                /*Retornar el resultado*/
                return 0;
            }
        }        

        /*Funcion para registrar el usuario en la base de datos*/
        public function registerUser($genre_id, $active, $founder, $code, $name, $surname, $birthdate, $phone, $email, $password1, $image, $earnings, $higher_id, $created_at){
            /*Encriptar la clave*/
            $password = password_hash($password1, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_USER(:u_genre_id, :active, :founder, :code, :name, :surname, TO_DATE(:birthdate, \'YYYY-MM-DD\'), :phone, :email, :user_password, :image, :earnings, :higher_user_id, TO_DATE(:created_at, \'YYYY-MM-DD\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':u_genre_id', $genre_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':founder', $founder);
            oci_bind_by_name($stmt, ':code', $code);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':surname', $surname);
            oci_bind_by_name($stmt, ':birthdate', $birthdate);
            oci_bind_by_name($stmt, ':phone', $phone);
            oci_bind_by_name($stmt, ':email', $email);
            oci_bind_by_name($stmt, ':user_password', $password);
            oci_bind_by_name($stmt, ':image', $image); 
            oci_bind_by_name($stmt, ':earnings', $earnings); 
            oci_bind_by_name($stmt, ':higher_user_id', $higher_id);                    
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Liberar recursos*/
                oci_close($this->conn);
            }
            /*Establecer resultado como falso*/
            $respuesta = false;
            /*Comprobar si el resultado ha sido exitoso*/
            if($resultado = 1){
                /*Asignar resultado*/
                $respuesta = $this->login($email, $password1);
            }
            /*Liberar recursos*/
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $respuesta;
        } 
        
        /*Funcion para registrar la noticia en la base de datos*/
        public function registerNews($active, $title, $content, $link, $image, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_NEWS(:active, :title, :content, :link, :image, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':title', $title);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':link', $link);
            oci_bind_by_name($stmt, ':image', $image);
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }  

        /*Funcion para registrar el producto en la base de datos*/
        public function registerProduct($user_id, $active, $name, $price, $units, $content, $stock, $description, $image, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_PRODUCT(:user_id, :active, :name, :price, :units, :content, :stock, :description, :image, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':price', $price);
            oci_bind_by_name($stmt, ':units', $units);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':stock', $stock);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':image', $image);            
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }  

        /*Funcion para obtener el detalle del producto*/
        public function detailProduct($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DETAIL_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }
        
        /*Funcion para obtener la lista de los productos*/
        public function productsList($user_id, $founder, $higuer_user){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := PRODUCTS_LIST(:user_id, :founder, :higuer_user); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            oci_bind_by_name($stid, ':founder', $founder);
            oci_bind_by_name($stid, ':higuer_user', $higuer_user);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }   

        /*Funcion para obtener la lista de todos los productos en el apartado de gestion*/
        public function productsListManagement($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PRODUCTS_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        } 

        /*Funcion para registrar el pago*/
        public function registerPay($user_id, $entity, $active, $number_election, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_PAY(:user_id, :entity, :active, :number_election, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':entity', $entity);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':number_election', $number_election);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para registrar la direccion*/
        public function registerDirection($user_id, $department, $active, $city, $carrer, $street, $postal_code, $direction, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_DIRECTION(:user_id, :department, :active, :city, :carrer, :street, :postal_code, :direction, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':department', $department);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':city', $city);
            oci_bind_by_name($stmt, ':carrer', $carrer);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':postal_code', $postal_code);
            oci_bind_by_name($stmt, ':direction', $direction);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para obtener la lista de todos las direcciones en el apartado de gestion*/
        public function directionListManagement($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := DIRECTION_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $directions = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $directions[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $directions;
        }   

        /*Funcion para obtener la lista de todos los pagos en el apartado de gestion*/
        public function payListManagement($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PAY_LIST_MANAGEMENT(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $pays = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $pays[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $pays;
        }   

        /*Funcion para obtener la contraseña de un usuario a traves de su correo*/
        public function getPassword($email){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PASSWORD(:email); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear una variable para almacenar el resultado*/ 
            $password = '';
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $password, 255);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            /*Retornar el resultado*/ 
            return $password;
        }

        /*Funcion para obtener un producto en concreto*/
        public function getProduct($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PRODUCT(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $productData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $productData;
        }

        /*Funcion para obtener un pago en concreto*/
        public function getPay($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PAY(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $payData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $payData;
        }

        /*Funcion para obtener una direccion en concreto*/
        public function getDirection($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_DIRECTION(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $directionData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retonar el resultado*/ 
            return $directionData;
        }

        /*Funcion para eliminar un producto*/
        public function deleteProduct($product_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PRODUCT(:product_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':product_id', $product_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar una noticia*/
        public function deleteNews($news_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_NEW(:news_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':news_id', $news_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar un pago*/
        public function deletePay($pay_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PAY(:pay_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':pay_id', $pay_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar una direccion*/
        public function deleteDirection($direction_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_DIRECTION(:direction_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':direction_id', $direction_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar un usuario*/
        public function deleteUser($user_id, $deleted){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_USER(:user_id, TO_DATE(:deleted, \'DD/MM/YY\')); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':deleted', $deleted);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para recuperar un usuario*/
        public function recoveryUser($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := RECOVERY_USER(:user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':user_id', $user_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Función para comprobar si el email ya existe*/
        public function validateUniqueEmail($email){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := VALIDATE_UNIQUE_EMAIL(:email); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear una variable para almacenar el resultado*/
            $resultado = 0;
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':email', $email);
            oci_bind_by_name($stid, ':resultado', $resultado);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Liberar recursos*/
            oci_free_statement($stid);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para actualizar un usuario*/
        public function updateUser($id, $name, $surname, $phone, $email, $image = null){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_USER(:id, :name, :surname, :phone, :email, :image); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':surname', $surname);
            oci_bind_by_name($stmt, ':phone', $phone);
            oci_bind_by_name($stmt, ':email', $email);
            oci_bind_by_name($stmt, ':image', $image);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }        
        
        /*Funcion para actualizar un pago*/
        public function updatePay($id, $election, $number_election){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_PAY(:id, :election, :number_election); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':election', $election);
            oci_bind_by_name($stmt, ':number_election', $number_election);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para cambiar la clave de un usuario*/
        public function changePassword($idUser, $newPassword){
            /*Encriptar la clave*/
            $password = password_hash($newPassword, PASSWORD_BCRYPT, ['cost'=>4]);
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := CHANGE_PASSWORD(:idUser, :password); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':idUser', $idUser);
            oci_bind_by_name($stmt, ':password', $password);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para actualizar una direccion*/
        public function updateDirection($id, $department, $city, $carrer, $street, $postal_code, $direction){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_DIRECTION(:id, :department, :city, :carrer, :street, :postal_code, :direction); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':department', $department);
            oci_bind_by_name($stmt, ':city', $city);
            oci_bind_by_name($stmt, ':carrer', $carrer);
            oci_bind_by_name($stmt, ':street', $street);
            oci_bind_by_name($stmt, ':postal_code', $postal_code);
            oci_bind_by_name($stmt, ':direction', $direction);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para actualizar un producto*/
        public function updateProduct($id, $name, $price, $units, $content, $stock, $description, $image = null){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_PRODUCT(:id, :name, :price, :units, :content, :stock, :description, :image); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            oci_bind_by_name($stmt, ':price', $price);
            oci_bind_by_name($stmt, ':units', $units);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':stock', $stock);
            oci_bind_by_name($stmt, ':description', $description);
            oci_bind_by_name($stmt, ':image', $image);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para actualizar una noticia*/
        public function updateNews($id, $title, $content, $link, $image = null){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_NEW(:id, :title, :content, :link, :image); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':title', $title);
            oci_bind_by_name($stmt, ':content', $content);
            oci_bind_by_name($stmt, ':link', $link);
            oci_bind_by_name($stmt, ':image', $image);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para buscar productos en base a su nombre*/
        public function searchProducts($user_id, $founder, $higuer_user, $name){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SEARCH_PRODUCTS(:user_id, :founder, :higuer_user, :name); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            oci_bind_by_name($stid, ':founder', $founder);
            oci_bind_by_name($stid, ':higuer_user', $higuer_user);
            oci_bind_by_name($stid, ':name', $name);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener un usuario en concreto*/
        public function getUser($id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_USER(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $cursor = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':id', $id);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($cursor);
            /*Obtener el resultado como un arreglo asociativo*/
            $userData = oci_fetch_assoc($cursor);
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /*Retornar el resultado*/
            return $userData;
        }

        /*Funcion para registrar la transaccion en la base de datos*/
        public function registerTransaction($number_bill, $id_buyer, $id_direction, $id_pay, $total, $descuento, $date_time, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_TRANSACTION(:number_bill, :id_buyer, :id_direction, :id_pay, :total, :descuento, TO_DATE(:date_time, \'DD/MM/YY\'), TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':number_bill', $number_bill);
            oci_bind_by_name($stmt, ':id_buyer', $id_buyer);
            oci_bind_by_name($stmt, ':id_direction', $id_direction);
            oci_bind_by_name($stmt, ':id_pay', $id_pay);
            oci_bind_by_name($stmt, ':descuento', $descuento);
            oci_bind_by_name($stmt, ':total', $total);
            oci_bind_by_name($stmt, ':date_time', $date_time);          
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para registrar la transaccion del producto en la base de datos*/
        public function registerTransactionProduct($id_transaction, $id_product, $id_seller, $id_purchasing_status, $units, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_TP(:id_transaction, :id_product, :id_seller, :id_purchasing_status, :units, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id_transaction', $id_transaction);
            oci_bind_by_name($stmt, ':id_product', $id_product);
            oci_bind_by_name($stmt, ':id_seller', $id_seller);
            oci_bind_by_name($stmt, ':id_purchasing_status', $id_purchasing_status);
            oci_bind_by_name($stmt, ':units', $units);          
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para obtener los datos del producto para la compra*/
        public function getProductDataPu($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_DATA_PRODUCT_P(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $cursor = oci_new_cursor($this->conn);
            /*Asignar los valores de entrada y el cursor de salida*/
            oci_bind_by_name($stid, ':id', $id);
            oci_bind_by_name($stid, ':resultado', $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($cursor);
            /*Obtener el resultado como un arreglo asociativo*/
            $productData = oci_fetch_assoc($cursor);
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($cursor);
            /*Retornar el resultado*/
            return $productData;
        }    

        /*Funcion para obtener la ultima transacion registrada*/
        public function getLastTransaction(){
            /*Preparar la consulta para ejecutar la función de Oracle*/
            $query = "BEGIN :cursor := GET_LAST_TRANSACTION; END;";
            $stmt = oci_parse($this->conn, $query);
            /*Declarar un cursor como parámetro de salida*/
            $cursor = oci_new_cursor($this->conn);
            /*Asociar el cursor con el parámetro de salida*/
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la función*/
            oci_execute($stmt);
            /*Ejecutar el cursor*/
            oci_execute($cursor);
            /*Obtener los datos del cursor*/
            $row = oci_fetch_assoc($cursor);
            /*Cerrar cursor y statement*/
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            /*Retornar el resultado*/
            return $row['ID'];
        }

        /*Funcion para obtener la lista de compras realizadas*/
        public function shoppingList($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SHOPPING_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }  

        /*Funcion para obtener la lista de ventas realizadas*/
        public function salesList($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SALES_LIST(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        } 

        /*Funcion para obtener la lista de ventas realizadas por parte de la tienda*/
        public function salesListAdministrator(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := SALES_LIST_ADMINISTRATOR(); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        } 

        /*Funcion para obtener el detalle de la venta*/
        public function detailSale($t_transaction_id, $seller){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := DETAIL_SALE(:t_transaction_id, :p_seller); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':t_transaction_id', $t_transaction_id);
            oci_bind_by_name($stid, ':p_seller', $seller);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para obtener el detalle de la venta en el administrador*/
        public function detailSaleAdministrator($t_transaction_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := DETAIL_SALE_ADMINISTRATOR(:t_transaction_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':t_transaction_id', $t_transaction_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para obtener el detalle de la compra*/
        public function detailShop($t_transaction_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := DETAIL_SHOP(:t_transaction_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':t_transaction_id', $t_transaction_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para decrementar el inventario*/
        public function decreaseInventory($product_id, $cantidad){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DECREASE_INVENTORY(:p_product_id, :t_cantidad); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':p_product_id', $product_id);
            oci_bind_by_name($stmt, ':t_cantidad', $cantidad);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para aumentar las ganancias*/
        public function increaseProfits($id_seller, $total){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := INCREASE_PROFITS(:t_id_seller, :t_total); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':t_id_seller', $id_seller);
            oci_bind_by_name($stmt, ':t_total', $total);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para distribuir las ganancias por la compra*/
        public function distribuiteEarnings($higuer, $seller, $total){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DISTRIBUTE_EARNINGS(:higuer, :seller, :t_total); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':higuer', $higuer);
            oci_bind_by_name($stmt, ':seller', $seller);
            oci_bind_by_name($stmt, ':t_total', $total);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para registrar el carrito*/
        public function registerCar($user_id, $active, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_CAR(:user_id, :active, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':user_id', $user_id);
            oci_bind_by_name($stmt, ':active', $active);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 

        /*Funcion para registrar el carrito del producto*/
        public function registerCarProduct($car_id, $product_id, $active, $units, $price, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_CP(:car_id, :product_id, :active, :units, :price, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':car_id', $car_id);
            oci_bind_by_name($stmt, ':product_id', $product_id);
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':units', $units);     
            oci_bind_by_name($stmt, ':price', $price);        
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        } 
        
        /*Funcion para obtener el ultimo carrito registrado*/
        public function getLastCar(){
            /*Preparar la consulta para ejecutar la función de Oracle*/
            $query = "BEGIN :cursor := GET_LAST_CAR; END;";
            $stmt = oci_parse($this->conn, $query);
            /*Declarar un cursor como parámetro de salida*/
            $cursor = oci_new_cursor($this->conn);
            /*Asociar el cursor con el parámetro de salida*/
            oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
            /*Ejecutar la función*/
            oci_execute($stmt);
            /*Ejecutar el cursor*/
            oci_execute($cursor);
            /*Obtener los datos del cursor*/
            $row = oci_fetch_assoc($cursor);
            /*Cerrar cursor y statement*/
            oci_free_statement($stmt);
            oci_free_statement($cursor);
            /*Retornar el resultado*/
            return $row['ID'];
        }

        /*Funcion para listar los productos del carrito*/
        public function productsListCar($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PRODUCTS_LIST_CAR(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para comprobar si el producto ya ha sido previamente agregado al carrito*/
        public function uniqueCp($idUsuario, $idProducto){
            $sql = "BEGIN :result := UNIQUE_CP(:c_id_user, :cp_id_product); END;";
            /*Preparar la consulta SQL*/
            $stmt = oci_parse($this->conn, $sql);
            /*Variable para almacenar el resultado*/
            $result = 0;
            /*Asignar parámetros*/
            oci_bind_by_name($stmt, ":c_id_user", $idUsuario);
            oci_bind_by_name($stmt, ":cp_id_product", $idProducto);
            oci_bind_by_name($stmt, ":result", $result, -1, SQLT_INT);
            /*Ejecutar la consulta*/
            oci_execute($stmt);
            /*Cerrar el recurso*/
            oci_free_statement($stmt);
            /*Retornar el resultado*/
            return $result;
        }        

        /*Funcion para decrementar la cantidad del carrito*/
        public function decreaseQuantity($productId, $userId){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DECREASE_QUANTITY(:cp_product_id, :c_user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':cp_product_id', $productId);
            oci_bind_by_name($stmt, ':c_user_id', $userId);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar todo el carrito*/
        public function deleteCar($userId){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_CAR(:user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':user_id', $userId);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para eliminar un producto del carrito*/
        public function deleteProductCar($productId, $userId){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PRODUCT_CAR(:cp_product_id, :c_user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':cp_product_id', $productId);
            oci_bind_by_name($stmt, ':c_user_id', $userId);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para incrementar la cantidad del carrito*/
        public function increaseQuantity($productId, $userId){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := INCREASE_QUANTITY(:cp_product_id, :c_user_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':cp_product_id', $productId);
            oci_bind_by_name($stmt, ':c_user_id', $userId);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para listar los productos del carrito al confirmar la compra*/
        public function productsListCarP($user_id){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PRODUCTS_LIST_CAR_P(:user_id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para agregar usuario a la red*/
        public function addUser($userId, $userCode){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := ADD_USER(:userId, :userCode); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':userId', $userId);
            oci_bind_by_name($stmt, ':userCode', $userCode);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para quitar un usuario de la red*/
        public function disasociate($userId){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DISASOCIATE(:userId); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':userId', $userId);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para asingar usuarios fundadores*/
        public function assignFounder($userCode){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := ASSIGN_FOUNDER(:userCode); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':userCode', $userCode);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para listar todos los usuarios registrados*/
        public function getUsers(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_USERS; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para listar todos los productos registrados por los usuarios*/
        public function getProducts(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_PRODUCTS; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para listar todos los productos registrados por el administrador*/
        public function getProductsAdmin(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_PRODUCTS_ADMIN; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para actualizar un departamento*/
        public function updateDepartment($id, $name){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_DEPARTMENT(:id, :name); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para registrar el departamento*/
        public function registerDepartment($active, $name, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_DEPARTMENT(:active, :name, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':name', $name);      
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para eliminar un departamento*/
        public function deleteDepartment($department_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_DEPARTMENT(:department_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':department_id', $department_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para cambiar el estado de una compra*/
        public function changeStatus($transactionProductId, $purchasingStatus){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := CHANGE_STATUS(:transactionProductId, :purchasingStatus); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':transactionProductId', $transactionProductId);
            oci_bind_by_name($stmt, ':purchasingStatus', $purchasingStatus);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para actualizar un genero*/
        public function updateGenre($id, $name){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_GENRE(:id, :name); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para registrar el genero*/
        public function registerGenre($active, $name, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_GENRE(:g_active, :g_name, TO_DATE(:g_created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':g_active', $active);
            oci_bind_by_name($stmt, ':g_name', $name);       
            oci_bind_by_name($stmt, ':g_created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para eliminar un genero*/
        public function deleteGenre($genre_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_GENRE(:genre_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':genre_id', $genre_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para obtener un genero en concreto*/
        public function getGenre($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_GENRE(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $genreData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $genreData;
        }

        /*Funcion para obtener un departamento en concreto*/
        public function getDepartment($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_DEPARTMENT(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $departmentData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $departmentData;
        }

        /*Funcion para obtener una noticia en concreto*/
        public function getNews($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_NEWS(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $newsData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $newsData;
        }

        /*Funcion para actualizar una entidad bancaria*/
        public function updateBankEntity($id, $name){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_BANK_ENTITY(:id, :name); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para registrar la entidad bancaria*/
        public function registerBankEntity($active, $name, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_BANK_ENTITY(:active, :name, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':name', $name);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para eliminar una entidad bancaria*/
        public function deleteBankEntity($bank_entity_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_BANK_ENTITY(:bank_entity_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':bank_entity_id', $bank_entity_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para obtener una entidad bancaria en concreto*/
        public function getBankEntity($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_BANK_ENTITY(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $bankEntityData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $bankEntityData;
        }

        /*Funcion para actualizar un estado de la compra*/
        public function updatePurchasingStatus($id, $name){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := UPDATE_PURCHASING_STATUS(:id, :name); END;';
            /*Parsear la consulta*/
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':id', $id);
            oci_bind_by_name($stmt, ':name', $name);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para registrar el estado de la compra*/
        public function registerPurchasingStatus($active, $name, $created_at){
            /*Preparar la consulta que llama a la función de Oracle*/
            $sql = 'BEGIN :resultado := REGISTER_PURCHASING_STATUS(:active, :name, TO_DATE(:created_at, \'DD/MM/YY\')); END;';
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada y salida*/
            oci_bind_by_name($stmt, ':active', $active);
            oci_bind_by_name($stmt, ':name', $name);         
            oci_bind_by_name($stmt, ':created_at', $created_at);
            /*Variable bandera para asignar el resultado*/
            $resultado = '';
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }   

        /*Funcion para eliminar un estado de la compra*/
        public function deletePurchasingStatus($purchasing_status_id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $sql = 'BEGIN :resultado := DELETE_PURCHASING_STATUS(:purchasing_status_id); END;'; 
            $stmt = oci_parse($this->conn, $sql);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stmt, ':purchasing_status_id', $purchasing_status_id);
            /*Variable para almacenar el resultado*/ 
            $resultado = '';
            /*Asignar el valor de salida si estás usando la función*/ 
            oci_bind_by_name($stmt, ':resultado', $resultado, 100);
            /*Ejecutar la consulta*/ 
            $success = oci_execute($stmt);
            /*Manejar errores si la ejecución falla*/ 
            if(!$success){
                /*Establecer error*/
                $e = oci_error($stmt);
                /*Liberar recursos*/
                oci_free_statement($stmt);
                oci_close($this->conn);
                /*Lanzar excepcion*/
                throw new Exception('Error al ejecutar la consulta: ' . $e['message']);
            }
            /*Liberar recursos*/ 
            oci_free_statement($stmt);
            oci_close($this->conn);
            /*Retornar el resultado*/
            return $resultado;
        }

        /*Funcion para obtener un estado de la compra en concreto*/
        public function getPurchasingStatus($id){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := GET_PURCHASING_STATUS(:id); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/ 
            oci_bind_by_name($stid, ':id', $id, -1, SQLT_INT);
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Obtener el resultado como un arreglo asociativo*/ 
            $purchasingStatusData = oci_fetch_assoc($resultado);
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $purchasingStatusData;
        }

        /*Funcion para obtener todos los departamentos registrados*/
        public function getDepartments(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_DEPARTMENTS; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener la lista de gestion de las noticias*/
        public function newsManagement(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := NEWS_MANAGEMENT; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener todas las noticias registradas*/
        public function getsNews(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GETS_NEWS; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para obtener todos los generos registrados*/
        public function getGenres(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_GENRES; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener todos los estados de la compra registrados*/
        public function getPurchasingStatues(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_PURCHASING_STATUES; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener todas las entidades bancarias registradas*/
        public function getBankEntities(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := GET_BANK_ENTITIES; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $products;
        }

        /*Funcion para obtener la lista de todos los productos registrados en el apartado de ver todos*/
        public function getAllProducts($user_id, $founder, $higuer_user){
            /*Preparar la consulta que llama a la función de Oracle*/ 
            $query = 'BEGIN :resultado := ALL_PRODUCTS(:user_id, :founder, :higuer_user); END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/ 
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el cursor como el valor de salida*/ 
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Enlazar el parámetro user_id*/
            oci_bind_by_name($stid, ':user_id', $user_id);
            oci_bind_by_name($stid, ':founder', $founder);
            oci_bind_by_name($stid, ':higuer_user', $higuer_user);
            /*Ejecutar la consulta*/ 
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/ 
            oci_execute($resultado);
            /*Crear un array para almacenar todos los productos*/ 
            $products = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $products[] = $row;
            }
            /*Liberar recursos*/ 
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/ 
            return $products;
        }

        /*Funcion para obtener la piramide*/
        public function piramyd(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := PYRAMID; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $pyramidData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $pyramidData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $pyramidData;
        }   
        
        /*Funcion para obtener el reporte de Comisiones Ganadas por Usuario*/
        public function cgpu(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_CGPU; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Compras por Usuario*/
        public function cpu(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_CPU; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Ganancias por Usuario*/
        public function gpu(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_GPU; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Referidos Activos vs. Inactivos*/
        public function rai(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_RAI; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Usuarios por Nivel*/
        public function upn(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_UPN; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Usuarios Referidos por Usuario*/
        public function urpu(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_URPU; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de Ventas y Comisiones por Nivel*/
        public function vcn(){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_VCN; END;';
            $stid = oci_parse($this->conn, $query);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

        /*Funcion para obtener el reporte de ventas realizadas*/
        public function vr($fechaInicio, $fechaFin){
            /*Preparar la consulta que llama a la función de Oracle*/
            $query = 'BEGIN :resultado := REPORT_VR(TO_DATE(:fechaInicio, \'DD/MM/YY\'), TO_DATE(:fechaFin, \'DD/MM/YY\')); END;';
            $stid = oci_parse($this->conn, $query);
            /*Asignar los valores de entrada*/ 
            oci_bind_by_name($stid, ':fechaInicio', $fechaInicio);
            oci_bind_by_name($stid, ':fechaFin', $fechaFin);
            /*Crear un cursor para obtener el resultado*/
            $resultado = oci_new_cursor($this->conn);
            /*Asignar el valor de entrada y salida*/
            oci_bind_by_name($stid, ':resultado', $resultado, -1, OCI_B_CURSOR);
            /*Ejecutar la consulta*/
            oci_execute($stid);
            /*Ejecutar el cursor para obtener los datos*/
            oci_execute($resultado);
            /*Obtener todos los resultados en un arreglo*/
            $reportData = [];
            /*Obtener todos los registros como un arreglo asociativo*/ 
            while(($row = oci_fetch_assoc($resultado)) != false){
                /*Agregar elementos al array*/
                $reportData[] = $row;
            }
            /*Liberar recursos*/
            oci_free_statement($stid);
            oci_free_statement($resultado);
            /*Retornar el resultado*/
            return $reportData;
        }

    }

?>