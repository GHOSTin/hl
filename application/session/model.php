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
    /**
    * Настройка кук
    */
    private static function set_cockies(){
        setcookie("chat_host", application_configuration::chat_host, 0);
        setcookie("chat_port", application_configuration::chat_port, 0);
    }
}