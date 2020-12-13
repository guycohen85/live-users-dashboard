<?php
require_once $rootFolder."\classes\User.php";

class AuthController{

    private $user;

    public function __construct(){
        $this->user = new User();
    }

    public function login(){
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, "password");

        $this->validate_not_empty($email, $password);
        if( $this->user->attempt_login($email, $password) ){
            header('Location: /');
            exit;
        }else{
            header('Location: /');
            $_SESSION["error"] = "Wrong email or passowrd";
            exit;
        }        
    }

    public function logout(){
        Auth::get_instance()->logout();
        header('Location: /');
        exit;
    }

    public function register(){
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, "password");
        
        $this->validate_not_empty($email, $password);
        $this->validate_email_exist($email);

        //save user
        $this->user->register( $email, $password );

        header('Location: /');
        exit;
    }

    private function validate_not_empty($email, $password){
        if( empty($email) || empty($password) ){
            header('Location: /');
            $_SESSION["error"] = "All fields are required";
            exit;
        }
    }

    private function validate_email_exist($email){
        if( $this->user->exist( $email ) ){
            header('Location: /');
            $_SESSION["error"] = "Email already exist";
            exit;
        }
    }



}