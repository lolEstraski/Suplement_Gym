<?php

    /*
    Clase controlador de pago
    */

    /*Incluir el modelo*/
    require_once 'models/Model.php';

    /*Iniciar el buffer de salida*/
    ob_start();

    class ReportController{

        /*Funcion para abrir el reporte de Comisiones Ganadas por Usuario*/
        public function cgpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Comisiones Ganadas por Usuario*/
            $report = $model -> cgpu();
            /*Incluir la vista*/
            require_once "views/reports/Cgpu.html";
        }

        /*Funcion para abrir el reporte de Compras por Usuario*/
        public function cpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Compras por Usuario*/
            $report = $model -> cpu();
            /*Incluir la vista*/
            require_once "views/reports/Cpu.html";
        }

        /*Funcion para abrir el reporte de ventas realizadas*/
        public function dates(){
            /*Comprobar si llegan los datos del formulario enviados por post*/
            if (isset($_POST)) {
                /*Asignar los datos si llegan*/
                $inicio = isset($_POST['init']) ? $_POST['init'] : false;
                $fin = isset($_POST['end']) ? $_POST['end'] : false;
                /*Comprobar si los datos llegan*/
                if ($inicio && $fin){
                    $inicio2 = (new DateTime($inicio))->format('d/m/y');
                    $fin2 = (new DateTime($fin))->format('d/m/y');
                    /*Validar si la fecha inicial es menor a la fecha final*/
                    if($inicio <= $fin){
                        /*Instancia modelo*/
                        $model = new Model();
                        /*Llamar la funcion que obtiene el reporte de Ventas Realizadas*/
                        $report = $model -> vr($inicio2, $fin2);
                        /*Incluir la vista*/
                        require_once "views/reports/Vr.html";
                    /*De lo contrario*/
                    }else{
                        /*Crear la sesion y redirigir a la ruta pertinente*/
                        Helps::createSessionAndRedirect("errorreport", "Fecha erronea", "?controller=reportController&action=vr");
                    }
                /*De lo contrario*/  
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorreport", "Ha ocurrido un error inesperado", "?controller=reportController&action=vr");
                }
            /*De lo contrario*/  
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorreport", "Ha ocurrido un error inesperado", "?controller=reportController&action=vr");
            }
            /*Retornar el resultado*/
            return $report;
        }

        /*Funcion para abrir el reporte de Ganancias por Usuario*/
        public function gpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Ganancias por Usuario*/
            $report = $model -> gpu();
            /*Incluir la vista*/
            require_once "views/reports/Gpu.html";
        }

        /*Funcion para abrir el reporte de Referidos Activos vs. Inactivos*/
        public function rai(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Referidos Activos vs. Inactivos*/
            $report = $model -> rai();
            /*Incluir la vista*/
            require_once "views/reports/Rai.html";
        }

        /*Funcion para abrir el reporte de Usuarios por Nivel*/
        public function upn(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Usuarios por Nivel*/
            $report = $model -> upn();
            /*Incluir la vista*/
            require_once "views/reports/Upn.html";
        }

        /*Funcion para abrir el reporte de Usuarios Referidos por Usuario*/
        public function urpu(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Usuarios Referidos por Usuario*/
            $report = $model -> urpu();
            /*Incluir la vista*/
            require_once "views/reports/Urpu.html";
        }

        /*Funcion para abrir el reporte de Ventas y Comisiones por Nivel*/
        public function vcn(){
            /*Instancia modelo*/
            $model = new Model();
            /*Llamar la funcion que obtiene el reporte de Ventas y Comisiones por Nivel*/
            $report = $model -> vcn();
            /*Incluir la vista*/
            require_once "views/reports/Vcn.html";
        }

        /*Funcion para abrir la ventana de fechas para el reporte de ventas realizadas*/
        public function vr(){
            /*Incluir la vista*/
            require_once "views/reports/Dates.html";
        }

        /*Funcion para generar reporte de factura en formato PDF*/
        public function generatePdf(){
            /*Comprobar si llega el id enviado por get*/
            if(isset($_GET)){
                /*Asignar el dato si llega*/  
                $reporte = isset($_GET['report']) ? $_GET['report'] : false;
                /*Asignar el dato si llega*/
                if($reporte){
                    /*Llamar la funcion para obtener la compra*/
                    $report = $this -> $reporte();
                    /*Llamar la funcion de ayuda que genera el archivo PDF*/
                    Helps::pdf($reporte);
                /*De lo contrario*/ 
                }else{
                    /*Crear la sesion y redirigir a la ruta pertinente*/
                    Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowReports");
                }
            /*De lo contrario*/ 
            }else{
                /*Crear la sesion y redirigir a la ruta pertinente*/
                Helps::createSessionAndRedirect("errorventana", "Ha ocurrido un error inesperado", "?controller=administratorController&action=windowReports");
            }
        }

    }

?>