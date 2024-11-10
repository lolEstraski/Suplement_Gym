<?php
    /*Iniciar el buffer de salida*/
    ob_start();
    /*Activar la sesión*/ 
    session_start();
    /*Incluir los archivo de autocarga de controladores*/
    require_once 'Autoload.php';
    /*Incluir archivo de ayudas*/
    require_once 'helps/helps.php';
    /*Incluir los archivo de configuracion de rutas*/
    require_once 'Configuration/Routes.php';
    /*Incluir archivo de configuracion de base de datos*/
    require_once 'Configuration/Db.php';
    /*Incluir el archivo de la vista de cabecera*/
    require_once 'views/layout/Header.html';

    /*Comprobar si llega el controlador por la URL*/
    if(isset($_GET['controller'])){
        /*Establecer nombre del controlador*/
        $nombre = $_GET['controller'];
    /*Comprobar si no llega el controlador y no existe el metodo*/
    }elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
        /*Comprobar si existe el inicio de sesion exitoso del administrador*/
        if(isset($_SESSION['loginsuccesa'])){
            /*Establecer nombre del controlador*/
            $nombre = "AdministratorController";
        /*De lo contrario*/    
        }else{
            /*Establecer nombre del controlador*/
            $nombre = "ProductController";
        }
    /*De lo contrario*/      
    }else{
        /*Salir*/
        exit();
    }
    /*Comprobar si existe el controlador*/
    if(class_exists($nombre)){
        /*instanciar el objeto*/
        $controlador = new $nombre();
        /*Comprobar si llega la acción y si el metodo existe dentro del controlador*/
        if(isset($_GET['action']) && method_exists($controlador, $_GET['action'])){
            /*Invocar o llamar a ese metodo*/
            $action = $_GET['action'];
            /*Llamar la acción*/
            $controlador -> $action();
        /*Comprobar si no llega el controlador y no existe la accion*/
        }elseif(!isset($_GET['controller']) && !isset($_GET['action'])){
            /*Comprobar si existe la sesion de login administrador exitoso*/
            if(isset($_SESSION['loginsuccesa'])){
                /*Establecer metodo por defecto*/
                $actionDefault = "Home";
            /*De lo contrario*/      
            }else{
                /*Establecer metodo por defecto*/
                $actionDefault = "windowProducts";
            }
            /*Realizar accion*/
            $controlador -> $actionDefault();
        /*De lo contrario*/      
        }else{
            exit();
        }
    /*De lo contrario*/          
    }else{
        exit();
    }

    /*Incluir el archivo de la vista de pie de pagina*/
    require_once 'views/layout/footer.html';

?>