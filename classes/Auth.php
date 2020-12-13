<?php
require_once $rootFolder."\classes\User.php";


class Auth {
    private static $instance = null;
    private $logged_in;
    public $user;
    
    private function __construct() {
        session_start();
        $this->check_login();
    }

    public static function get_instance(){
        if (self::$instance == null){
            self::$instance = new Auth();
        }
    
        return self::$instance;
    }
    
    public function is_logged_in(){
        return $this->logged_in;
    }
    
    public function login($user){
        $_SESSION['user'] = serialize($user);
        $this->logged_in = true;
    }
    
    public function logout(){
        if( isset($this->user) ){
            $this->user->offline();
        }
        unset($_SESSION['user']);
        unset($this->user);
        $this->logged_in = false;
    }
        
    private function check_login(){

        if(isset($_SESSION['user'])){
            $this->user = unserialize($_SESSION['user']);
            if($this->user->get_status() === 0){
                $this->logout();
                return;
            }
            $this->user->status = 1;
            $this->user->last_update_time = time();
            $this->user->update();
            $this->login($this->user);
        }else{
            $this->logout();
        }
    }
}


$auth = Auth::get_instance();
