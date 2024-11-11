<?php

    /*
    Clase controlador de transaccion
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    /*Iniciar el buffer de salida*/
    ob_start();

    class TransactionController{

        /*Funcion para abrir la ventana de carrito*/
        public function windowCar(){
            /*Instanciar modelo*/
            $model = new Model();
            /*Variable bandera del total del carrito*/
            $total = 0;
            /*Obtener la lista de los productos agregados al carrito*/
            $list = $model -> productsListCar($_SESSION['loginsucces']['USER_ID']);
            /*Incluir la vista*/
            require_once "views/transaction/Car.html";
        }

        /*Funcion para realizar la compra de un producto*/
        public function shop(){
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
                    /*Obtener fecha actual*/
                    $created_at = date('Y-m-d');
                    $created_at2 = (new DateTime($created_at))->format('d/m/y');
                    /*Comprobar si el producto ha llegado*/
                    if($resultado){
                        /*Instanciar modelo*/
                        $model = new Model();
                        /*Llamar la funcion que comprueba si el producto ya ha sido o no agregado al carrito previamente*/
                        $unico = $model -> uniqueCp($_SESSION['loginsucces']['USER_ID'], $id);
                        /*Comprobar si el producto no ha sido agregado previamente*/
                        if($unico == 0){
                            /*Llamar la funcion que registra el producto en el carrito*/
                            $registro = $model -> registercar($_SESSION['loginsucces']['USER_ID'], 1, $created_at2);
                            /*Comprobar si el registro ha sido exitoso*/
                            if($registro){
                                /*Obtener el id del ultimo carrito registrado*/
                                $id_car = $model -> getLastCar();
                                /*Llamar la funcion que registra el producto del carrito*/
                                $registro2 = $model -> registerCarProduct($id_car, $id, 1, 1, 100, $created_at2);
                                /*Comprobar si el registro del producto del carrito fue exitoso*/
                                if($registro2){
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Helps::createSessionAndRedirect("aciertocarrito", "El producto ha sido agregado con exito", "?controller=transactionController&action=windowCar");
                                /*De lo contrario*/   
                                }else{
                                    /*Crear la sesion y redirigir a la ruta pertinente*/
                                    Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error al guardar el producto en el carrito", "?controller=productController&action=detail&id=$id");
                                }
                            /*De lo contrario*/   
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=productController&action=detail&id=$id");
                            }
                        /*De lo contrario*/   
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorcarrito", "Este producto ya ha sido agregado previamente", "?controller=productController&action=detail&id=$id");
                        }
                    /*De lo contrario*/  
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=productController&action=detail&id=$id");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=productController&action=detail&id=$id");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*Funcion para disminuir la cantidad del producto del carrito*/
        public function decreaseQuantity(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_GET)){
                /*Asignar los datos si llegan*/
                $idProduct = isset($_GET['id']) ? $_GET['id'] : false;
                /*Comprobar si llega el dato*/
                if($idProduct){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion*/
                    $resultado = $model -> decreaseQuantity($idProduct, $_SESSION['loginsucces']['USER_ID']);  
                    /*Si el resultado es correcto*/
                    if($resultado){
                        /*Redirigir*/
                        header("Location:"."http://localhost/Suplement_Gym/?controller=transactionController&action=windowCar");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errordecremento", "Ha ocurrido un error al decrementar las unidades", "?controller=transactionController&action=windowCar");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errordecremento", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
                }              
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errordecremento", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
            }
        }

        /*Funcion para aumentar la cantidad del producto del carrito*/
        public function increaseQuantity(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_GET)){
                /*Asignar los datos si llegan*/
                $idProduct = isset($_GET['id']) ? $_GET['id'] : false;
                /*Comprobar si llega el dato*/
                if($idProduct){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion*/
                    $resultado = $model -> increaseQuantity($idProduct, $_SESSION['loginsucces']['USER_ID']);  
                    /*Si el resultado es correcto*/
                    if($resultado){
                        /*Redirigir*/
                        header("Location:"."http://localhost/Suplement_Gym/?controller=transactionController&action=windowCar");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorincremento", "Ha ocurrido un error al incrementar las unidades", "?controller=transactionController&action=windowCar");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorincremento", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
                }              
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorincremento", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
            }
        }

        /*Funcion para eliminar un producto del carrito*/
        public function deleteProductCar(){
            /*Comprobar si llegan los datos del formulario enviados por get*/
            if(isset($_GET)){
                /*Asignar el dato si llega*/
                $idProduct = isset($_GET['id']) ? $_GET['id'] : false;
                /*Comprobar si el dato llego*/
                if($idProduct){
                    /*Instanciar el modelo*/
                    $model = new Model();
                    /*Llamar la funcion que elimina el producto del carrito*/
                    $resultado = $model -> deleteProductCar($idProduct, $_SESSION['loginsucces']['USER_ID']);
                    /*Comprobar si se ha eliminado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("aciertoeliminar", "El producto ha sido eliminado con exito", "?controller=transactionController&action=windowCar");
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido en error al eliminar el producto", "?controller=transactionController&action=windowCar");
                    }
                /*De lo contrario*/
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
                }
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
            }
        }

        /*Funcion para eliminar todo el carrito*/
        public function deleteCar(){
            /*Instanciar el modelo*/
            $model = new Model();
            /*Llamar la funcion que elimina el carrito*/
            $resultado = $model -> deleteCar($_SESSION['loginsucces']['USER_ID']);
            /*Comprobar si el carrito ha sido eliminado con exito*/
            if($resultado){
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("aciertoeliminar", "El carrito ha sido eliminado con exito", "?controller=VideojuegoController&action=inicio");
            /*De lo contrario*/
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("erroreliminar", "Ha ocurrido un error al eliminar el carrito", "?controller=transactionController&action=windowCar");
            }
        }

        /*Funcion para guardar productos en el carrito*/
        public function registerCar(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $idProduct = isset($_POST['idProduct']) ? $_POST['idProduct'] : false;
                $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : false;
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Comprobar si todos los datos llegaron*/
                if($idProduct && $cantidad){
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Llamar la funcion que comprueba si el producto ya ha sido o no agregado al carrito previamente*/
                    $unico = $model -> uniqueCp($_SESSION['loginsucces']['USER_ID'], $idProduct);
                    /*Comprobar si el producto no ha sido agregado previamente*/
                    if($unico == 0){
                        /*Llamar la funcion que registra el producto en el carrito*/
                        $registro = $model -> registercar($_SESSION['loginsucces']['USER_ID'], 1, $created_at2);
                        /*Comprobar si el registro ha sido exitoso*/
                        if($registro){
                            /*Obtener el id del ultimo carrito registrado*/
                            $id_car = $model -> getLastCar();
                            /*Llamar la funcion que registra el producto del carrito*/
                            $registro2 = $model -> registerCarProduct($id_car, $idProduct, 1, $cantidad, 100, $created_at2);
                            /*Comprobar si el registro del producto del carrito fue exitoso*/
                            if($registro2){
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("aciertocarrito", "El producto ha sido agregado con exito", "?controller=transactionController&action=windowCar");
                            /*De lo contrario*/   
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error al guardar el producto en el carrito", "?controller=productController&action=detail&id=$idProduct");
                            }
                        /*De lo contrario*/   
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=productController&action=detail&id=$idProduct");
                        }
                    /*De lo contrario*/   
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorcarrito", "Este producto ya ha sido guardado en el carrito", "?controller=productController&action=detail&id=$idProduct");
                    }
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=productController&action=detail&id=$idProduct");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorcarrito", "Ha ocurrido un error inesperado", "?controller=VideojuegoController&action=inicio");
            }
        }

        /*Funcion para abrir ventana de compra*/
        public function windowPurchase(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_GET)){
                /*Asignar los datos si llegan*/
                $total = isset($_GET['total']) ? $_GET['total'] : false;
                /*Comprobar si el dato ha llegado*/
                if($total){
                    /*Establecer bandera de descuento*/
                    $descuento = 0;
                    /*Instanciar modelo*/
                    $model = new Model();
                    /*Obtener lista de direcciones propias*/
                    $listDirections = $model -> directionListManagement($_SESSION['loginsucces']['USER_ID']);
                    /*Obtener lista de pagos propias*/
                    $listPays = $model -> payListManagement($_SESSION['loginsucces']['USER_ID']);
                    /*Incluir la vista*/
                    require_once "views/transaction/Purchase.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorwindow", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
                }
            /*De lo contrario*/   
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorwindow", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowCar");
            }
        }

        /*Funcion para ver el detalle de la compra*/
        public function detailShop(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener el detalle de la compra*/
            $detail = $model -> detailShop($_GET['id']);
            /*Total de la compra*/
            $total = 0;
            /*Incluir la vista*/
            require_once "views/transaction/DetailShop.html";
            /*Retornar el resultado*/
            return $detail;
        }

        /*Funcion para ver el detalle de la venta*/
        public function detailSale(){
            /*Instanciar modelo*/      
            $model = new Model();
            /*Obtener el detalle de la venta*/
            $detail = $model -> detailSale($_GET['id'], $_SESSION['loginsucces']['USER_ID']);
            /*Total de la compra*/
            $total = 0;
            /*Obtener la lista de estados de pago*/
            $listPurchasingStatus = $model -> getPurchasingStatues();
            /*Incluir la vista*/
            require_once "views/transaction/DetailSale.html";
        }

        /*Funcion para abrir ventana de confirmar la compra*/
        public function windowConfirm(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if(isset($_POST)){
                /*Asignar los datos si llegan*/
                $idPay = isset($_POST['id_pay']) ? $_POST['id_pay'] : false;
                $idDirection = isset($_POST['id_direction']) ? $_POST['id_direction'] : false;
                $total = isset($_POST['total']) ? $_POST['total'] : false;
                /*Comprobar si todos los datos llegaron*/
                if($idPay && $idDirection && $total){
                    /*Bandera de descuento*/
                    $descuento = 0;
                    /*Instanciar modelo*/  
                    $model = new Model();
                    /*Obtener el pago*/
                    $pay = $model -> getPay($_POST['id_pay']);
                    /*Obtener la direccion*/
                    $direction = $model -> getDirection($_POST['id_direction']);
                    /*Obtener el carrito del usuario que va a comprar*/
                    $list = $model -> productsListCarP($_SESSION['loginsucces']['USER_ID']);
                    /*Llamar la funcion que verifica el descuento del cumpleaños*/
                    $descuento = Helps::birthday($total);
                    /*Asegurarte de que today se inicialice antes de su uso*/
                    $today = new DateTime();
                    /*Incluir la vista*/
                    require_once "views/transaction/Confirm.html";
                /*De lo contrario*/    
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowPurchase");
                }
            /*De lo contrario*/     
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowPurchase");
            }
        }

       /*Funcion para guardar la transaccion*/
       public function purchase(){
        /*Instanciar modelo*/      
        $model = new Model();
        /*Obtener factura*/
        $number_bill = $model -> getLastTransaction() + 1000;
        /*Obtener comprador*/
        $idBuyer = $_SESSION['loginsucces']['USER_ID'];
        /*Comprobar si llegan los datos del formulario enviados por post*/
        if(isset($_POST)){
            /*Asignar los datos si llegan*/
            $idDirection = isset($_POST['id_direction']) ? $_POST['id_direction'] : false;
            $idPay = isset($_POST['id_pay']) ? $_POST['id_pay'] : false;
            $total = isset($_POST['total']) ? $_POST['total'] : false;
            $descuento = isset($_POST['descuento']) ? $_POST['descuento'] : false;
            /*Comprobar si los datos llegan*/
            if($idDirection && $idPay && $total){
                /*Obtener datos restantes*/
                $date_time = date('Y-m-d');
                $date_time2 = (new DateTime($date_time))->format('d/m/y');
                $created_at = date('Y-m-d');
                $created_at2 = (new DateTime($created_at))->format('d/m/y');
                /*Llamar la funcion del modelo que registra la transaccion*/  
                $resultado = $model -> registerTransaction($number_bill, $idBuyer, $idDirection, $idPay, $total, $descuento, $date_time2, $created_at2);
                /*Comprobar si el registrado ha sido exitoso*/                    
                if($resultado != false){
                    /*Obtener la ultima transaccion registrada*/
                    $id_transaction = $model -> getLastTransaction();
                    /*Obtener la lista de los productos agregados al carrito*/
                    $list = $model -> productsListCar($_SESSION['loginsucces']['USER_ID']);
                    /*Recorrer los productos del carrito*/
                    foreach($list as $listCar){
                        /*Obtener el id del vendedor del producto*/
                        $id_seller = $model -> getProductDataPu($listCar['PRODUCT_ID'])['USER_ID'];
                        /*Comprobar si los datos llegan*/
                        if($id_transaction && $created_at2){
                            /*Llamar la funcion del modelo que registra la transaccion del producto*/  
                            $resultado2 = $model -> registerTransactionProduct($id_transaction, $listCar['PRODUCT_ID'], $id_seller, 1, $listCar['AMOUNT'], $created_at2);
                            /*Comprobar si el registrado ha sido exitoso*/   
                            if($resultado2 != false){
                                /*Obtener producto en concreto*/
                                $product = $model -> getProduct($listCar['PRODUCT_ID']);
                                /*Validar que los usuarios externos no registren sus productos*/
                                if($_SESSION['loginsucces']['FOUNDER'] == 1 || $_SESSION['loginsucces']['HIGHER_USER_ID'] != NULL){
                                    /*Agregar productos*/
                                    $model -> registerProduct($_SESSION['loginsucces']['USER_ID'], 1, $product['NAME'], $product['PRICE'], $product['UNITS'], $product['CONTENT'], $listCar['AMOUNT'], $product['DESCRIPTION'], $product['IMAGE'], $created_at2);
                                }
                                /*Llamar la funcion del modelo que decrementa el inventario*/ 
                                $model -> decreaseInventory($listCar['PRODUCT_ID'], $listCar['AMOUNT']);
                                /*Eliminar carrito*/
                                $model -> deleteCar($_SESSION['loginsucces']['USER_ID']);
                                /*Redirigir*/
                                header("Location:"."http://localhost/Suplement_Gym/?controller=productController&action=windowProducts");
                            /*De lo contrario*/  
                            }else{
                                /*Crear la sesion y redirigir a la ruta pertinente*/
                                Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error al realizar la compra", "?controller=transactionController&action=windowPurchase");
                            }
                        /*De lo contrario*/  
                        }else{
                            /*Crear la sesion y redirigir a la ruta pertinente*/
                            Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowPurchase");
                        }
                    }
                    /*Comprobar si el producto tiene vendedor*/
                    if($id_seller != null){
                        /*Llamar la funcion que aumenta las ganancias del vendedor*/
                        $model -> increaseProfits($id_seller, $total);
                        /*Incrementar comisiones y porcentajes*/
                        $model -> distribuiteEarnings($_SESSION['loginsucces']['USER_ID'], $id_seller, $total);
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowPurchase");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errortransaccion", "Ha ocurdrido un error inesperado", "?controller=transactionController&action=windowPurchase");
            }
        /*De lo contrario*/  
        }else{
            /*Crear la sesion y redirigir a la ruta pertinente*/
            Helps::createSessionAndRedirect("errortransaccion", "Ha ocurrido un error inesperado", "?controller=transactionController&action=windowPurchase");
        }
    }

        /*Funcion para cambiar el estado de la compra*/
        public function changeStatus(){
            /*Comprobar si llega el id enviado por get*/  
            if(isset($_GET) && isset($_POST)){
                /*Comprobar si los datos existen*/
                $transaction_product = isset($_GET['id']) ? $_GET['id'] : false;
                $purchasingStatus = isset($_POST['purchaseStatus']) ? $_POST['purchaseStatus'] : false;
                /*Si el dato existe*/
                if($transaction_product && $purchasingStatus){
                    /*Instanciar modelo*/      
                    $model = new Model();
                    /*Llamar la funcion del modelo que actualiza el estado de la compra*/  
                    $resultado = $model -> changeStatus($transaction_product, $purchasingStatus);
                    /*Comprobar si el estado de la compra se ha actualizado con exito*/
                    if($resultado){
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('aciertocambio', "Se ha actualizado exitosamente el estado de la compra", "?controller=userController&action=mySales");
                    /*De lo contrario*/ 
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect('errorcambio', "Ha ocurrido un error al realizar la actualizacion de la compra", "?controller=transactionController&action=detailSale&id=$transaction_product");
                    }
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect('errorcambio', "Ha ocurrido un error inesperado", "?controller=transactionController&action=detailSale&id=$transaction_product");
                }
            /*De lo contrario*/    
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorcambio", "Ha ocurrido un error inesperado", "?controller=userController&action=mySales");
            }
        }

        /*Funcion para generar reporte de factura en formato PDF*/
        public function generatePdf2(){
            /*Llamar la funcion para obtener la compra*/
            $detalle = $this -> detailShop();
            /*Llamar la funcion de ayuda que genera el archivo PDF*/
            Helps::pdf2($detalle);
        }

    }

?>