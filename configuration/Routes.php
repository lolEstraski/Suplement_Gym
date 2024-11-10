<?php

    /*
    Clase con todas las rutas utilizadas
    */

    /*Ruta de alojamiento local*/

    define("init", "http://localhost/Suplement_Gym");

    /*Ruta para usuario*/

    define("user", "/?controller=userController");

    define("windowLogin", "&action=windowlogin");
    define("windowRegisterUser", "&action=windowRegister");
    define("login", "&action=login");
    define("registerUser", "&action=register");
    define("logoutUser", "&action=logout");
    define("myProfile", "&action=myProfile");
    define("myShops", "&action=myShops");
    define("mySales", "&action=mySales");
    define("windowChangePassword", "&action=windowChangePassword");
    define("managementProducts", "&action=managementProducts");
    define("managementDirections", "&action=managementDirections");
    define("managementPays", "&action=managementPays");
    define("deleteUser", "&action=delete");
    define("updateUser", "&action=update");
    define("changePassword", "&action=changePassword");
    define("windowDisasociate", "&action=windowDisasociate");
    define("disasociate", "&action=disasociate");
    define("recoveryUser", "&action=recovery");

    /*Ruta para producto*/

    define("product", "/?controller=productController");

    define("windowRegisterProduct", "&action=windowRegister");
    define("windowProducts", "&action=windowProducts");
    define("registerProduct", "&action=register");
    define("windowUpdateProduct", "&action=windowUpdate");
    define("detailProduct", "&action=detail");
    define("deleteProduct", "&action=delete");
    define("updateProduct", "&action=update");
    define("searchProduct", "&action=search");
    define("allProducts", "&action=all");

    /*Rutas para pago*/

    define("pay", "/?controller=payController");

    define("windowRegisterPay", "&action=windowRegister");
    define("registerPay", "&action=register");
    define("windowUpdatePay", "&action=windowUpdate");
    define("deletePay", "&action=delete");
    define("updatePay", "&action=update");

    /*Rutas para departamento*/

    define("department", "/?controller=departmentController");

    define("windowRegisterDepartment", "&action=windowRegister");
    define("registerDepartment", "&action=register");
    define("windowUpdateDepartment", "&action=windowUpdate");
    define("deleteDepartment", "&action=delete");
    define("updateDepartment", "&action=update");

    /*Rutas para genero*/

    define("genre", "/?controller=genreController");

    define("windowRegisterGenre", "&action=windowRegister");
    define("registerGenre", "&action=register");
    define("windowUpdateGenre", "&action=windowUpdate");
    define("deleteGenre", "&action=delete");
    define("updateGenre", "&action=update");

    /*Rutas para entidad bancaria*/

    define("bankEntity", "/?controller=bankingEntityController");

    define("windowRegisterBankEntity", "&action=windowRegister");
    define("registerBankEntity", "&action=register");
    define("windowUpdateBankEntity", "&action=windowUpdate");
    define("deleteBankEntity", "&action=delete");
    define("updateBankEntity", "&action=update");

    /*Rutas para estado de la compra*/

    define("purchasingStatus", "/?controller=purchasingStatusController");

    define("windowRegisterPurchasingStatus", "&action=windowRegister");
    define("registerPurchasingStatus", "&action=register");
    define("windowUpdatePurchasingStatus", "&action=windowUpdate");
    define("deletePurchasingStatus", "&action=delete");
    define("updatePurchasingStatus", "&action=update");

    /*Rutas para envio*/

    define("direction", "/?controller=directionController");

    define("windowRegisterDirection", "&action=windowRegister");
    define("registerDirection", "&action=register");
    define("windowUpdateDirection", "&action=windowUpdate");
    define("deleteDirection", "&action=delete");
    define("updateDirection", "&action=update");

    /*Rutas para noticia*/

    define("news", "/?controller=newsController");

    define("windowRegisterNews", "&action=windowRegister");
    define("registerNews", "&action=register");
    define("windowUpdateNews", "&action=windowUpdate");
    define("deleteNews", "&action=delete");
    define("updateNews", "&action=update");
    define("allNews", "&action=all");

    /*Rutas para transaccion*/

    define("transaction", "/?controller=transactionController");

    define("shop", "&action=shop");
    define("windowCar", "&action=windowCar");
    define("registerCar", "&action=registerCar");
    define("windowPurchase", "&action=windowPurchase");
    define("purchase", "&action=purchase");
    define("confirm", "&action=windowConfirm");
    define("detailShop", "&action=detailShop");
    define("detailSale", "&action=detailSale");
    define("generatePdf2", "&action=generatePdf2");
    define("deleteProductCar", "&action=deleteProductCar");
    define("deleteCar", "&action=deleteCar");
    define("increaseQuantity", "&action=increaseQuantity");
    define("decreaseQuantity", "&action=decreaseQuantity");
    define("changeStatus", "&action=changeStatus");

    /*Rutas para establecer la red*/

    define("network", "/?controller=networkController");

    define("windowAddUser", "&action=windowAddUser");
    define("addUser", "&action=addUser"); 
    
    /*Rutas para el administrador*/

    define("administrator", "/?controller=administratorController");
    
    define("windowLoginAdministrator", "&action=windowlogin");
    define("windowRegisterProductA", "&action=windowRegisterProduct");
    define("registerProductAdmin", "&action=registerProduct");
    define("windowManagementUsers", "&action=windowManagementUsers");
    define("registerProductA", "&action=registerProduct");
    define("windowManagementProducts", "&action=windowManagementProducts");
    define("windowManagementDepartments", "&action=windowManagementDepartments");
    define("windowManagementPurchasingStatus", "&action=windowManagementPurchasingStatues");
    define("windowManagementBankEntities", "&action=windowManagementBankEntities");
    define("windowManagementNews", "&action=windowManagementNews");
    define("windowManagementGenres", "&action=windowManagementGenres");
    define("windowAddUserAdmin", "&action=windowAddUser");
    define("windowReports", "&action=windowReports");
    define("addUserAdmin", "&action=addUser"); 
    define("deleteProductA", "&action=deleteProduct");
    define("deleteUserA", "&action=deleteUser");
    define("pyramid", "&action=pyramid");
    define("sales", "&action=windowSalesAdministrator");
    define("detailSaleAdministrator", "&action=detailSaleAdministrator");
    define("changeStatusAdministrator", "&action=changeStatusAdministrator");

    /*Rutas para los reportes*/

    define("reports", "/?controller=reportController");

    define("cgpu", "&action=cgpu");
    define("cpu", "&action=cpu");
    define("gpu", "&action=gpu");
    define("rai", "&action=rai");
    define("upn", "&action=upn");
    define("urpu", "&action=urpu");
    define("vcn", "&action=vcn");
    define("vr", "&action=vr");
    define("dates", "&action=dates");
    define("generatePdf", "&action=generatePdf");

?>