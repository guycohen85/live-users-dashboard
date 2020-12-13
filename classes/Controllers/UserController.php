<?php
require_once $rootFolder."\classes\User.php";
require_once $rootFolder."\classes\Auth.php";

class UserController{

    private $user;

    public function __construct(){
        if( !Auth::get_instance()->is_logged_in() ){
            header('Content-Type: application/json');
            header('Status: 403');
            echo "Forbidden";
            exit;
        }
    }

    public function index(){
        header('Content-Type: application/json');
        header('Status: 200');
        echo json_encode( User::all_users_no_passwords() );
        exit;
    }

    public function update_users_status(){
        $user = new User();
        $new_users_data = $user->update_users_status();
        header('Content-Type: application/json');
        header('Status: 200');
        echo json_encode( $new_users_data );
        exit;
    }

}