<?php 
require_once $rootFolder."/classes/controllers/UserController.php";
require_once $rootFolder."/classes/controllers/AuthController.php";

/** GET Request **/
if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) {

    if( array_key_exists("users", $_GET) ){
        (new UserController())->index();
    }

/** POST Request **/
}else{

    if( array_key_exists("form", $_POST) ){ //if login or register pages

        if( $_POST["form"] === "register" ){

            (new AuthController())->register();

        }else if($_POST["form"] === "login"){

            (new AuthController())->login();

        }else if($_POST["form"] === "logout"){

            (new AuthController())->logout();

        }

    }else if( array_key_exists("update_users", $_POST) ){
        (new UserController())->update_users_status();
    }

}
