<?php
class model_session{

    private static $user;

    public function get_user(){
        return self::$user;
    }

    public function set_user(data_current_user $user){
        if(!isset(self::$user)){
            $_SESSION['user'] = $user;
            self::$user = $user;
        }else
            throw new exception('Нельзя дважды определить пользователя.');
    }
}