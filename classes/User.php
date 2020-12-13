<?php
require_once $rootFolder."/classes/DatabaseFile.php";

class User extends DatabaseFile{

    private static $users;
    public $email, $password, $username, $login_time, $last_update_time, $user_ip, $status, $user_agent, $register_time, $logins_count;

    public function __construct(){  
        static::$users = DatabaseFile::get_instance()->data->users;
    }

    public static function all(){
        if( !static::$users ){
            static::$users = DatabaseFile::get_instance()->data->users;

        }
        return static::$users;
    }

    public static function all_users_no_passwords(){
        SELF::removePasswords();
        return SELF::all();
    }

    public function get_status(){
        foreach (SELF::all() as $user) {
            if($user->email === $this->email){
                return $user->status;
                break;
            }
        }
    }

    private function setUserData($user){
        $this->email = $user->email;
        $this->password =  $user->password;
        $this->username = $user->username;
        $this->login_time = $user->login_time;
        $this->last_update_time = $user->last_update_time;
        $this->user_ip = $user->user_ip;
        $this->status = $user->status;
        $this->user_agent = $user->user_agent;
        $this->register_time = $user->register_time;
        $this->logins_count = $user->logins_count;
    }

    public function update(){
        //update user to file
        DatabaseFile::get_instance()->update_user($this);
    }

    public function register($email, $password){
        $this->email = $email;
        $this->password =  password_hash($password, PASSWORD_DEFAULT);
        $this->username = ucfirst(explode( "@",  $this->email )[0]);
        $this->login_time = time();
        $this->last_update_time = time();
        $this->user_ip = $this->get_ip();
        $this->status = 1;
        $this->user_agent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
        $this->register_time = time();
        $this->logins_count = 1;

        //save user to file
        DatabaseFile::get_instance()->save_user($this);
        
        // login user
        Auth::get_instance()->login($this);
    }

    public function update_users_status(){
        $current_user = Auth::get_instance()->user;
        foreach (SELF::$users as $key => $user) {
            if( $user->email === $current_user->email){
                SELF::$users[$key]->last_update_time = time();
            }else{
                //check if last_update_time is less than 3 seconds then set user to offline
                $user_timestamp = (int) $user->last_update_time;
                $current_time = time();
                $result = $current_time - $user_timestamp;
                if( $result > 3 ){
                    SELF::$users[$key]->status = 0;
                }else{
                    SELF::$users[$key]->status = 1;
                }

            }
        }
        DatabaseFile::get_instance()->update_all_users(SELF::$users);
        SELF::removePasswords();
        return SELF::$users;
    }

    public static function removePasswords(){
        foreach(SELF::$users as $key => $user){
            unset(SELF::$users[$key]->password);
        }
    }

    public function offline(){
        $this->status = 0;
        DatabaseFile::get_instance()->update_user($this);
    }

    public function attempt_login($email, $password){
        foreach ( static::all() as $user) {
            if( $user->email === $email && password_verify($password, $user->password)){
                $user->status = 1;
                $user->logins_count++;
                $user->login_time = time();
                $user->last_update_time = time();
                $this->setUserData($user);
                $this->update();
                // login user
                Auth::get_instance()->login($this);
                return true;
                break;
            }
        }
        return false;
    }
    
    private function get_ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = filter_input(INPUT_SERVER, "HTTP_CLIENT_IP");
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = filter_input(INPUT_SERVER, "HTTP_X_FORWARDED_FOR");
        } else {
            $ip = filter_input(INPUT_SERVER, "REMOTE_ADDR");
        }
        return $ip;
    }

    public function exist( $email ){
        $exist = false;
        foreach ( static::all() as $user) {
            if( $user->email === $email ){
                $exist = true;
                break;
            }
        }
        return $exist;
    }

}