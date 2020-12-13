<?php
class DatabaseFile {
    private static $instance = null;
    protected $data;

    private function __construct()
    {
        $getfile = file_get_contents('../database.json');
        $this->data = json_decode($getfile);
    }
 
    public static function get_instance(){
        if (self::$instance == null){
            self::$instance = new DatabaseFile();
        }
    
        return self::$instance;
    }

    public function save_user($user){
        array_push($this->data->users, $user) ;
        file_put_contents('../database.json', json_encode($this->data));
    }

    public function update_user($user){
        if( isset($this->data->users) ){
            foreach($this->data->users as $key => $data_user){
                if($data_user->email === $user->email){
                    foreach ($data_user as $name => $value) {
                        $this->data->users[$key]->{$name} = $user->{$name};
                    }
                    break;
                }
            }
            file_put_contents('../database.json', json_encode($this->data));
        }
    }

    public function update_all_users($users){
        $this->data->users = $users;
        file_put_contents('../database.json', json_encode($this->data));
    }

}
