<?php
class model_session{

    private static $user;
    private static $rules;
    private static $restrictions;
    private static $links;

    public function get_rules(){
        return self::$rules;
    }

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

    public function set_links($links){
        if(!isset(self::$links)){
            self::$links = $links;
        }else
            throw new exception('Нельзя дважды определить ссылки меню.');
    }

    public function set_restrictions($restrictions){
        if(!isset(self::$restrictions)){
            self::$restrictions = $restrictions;
        }else
            throw new exception('Нельзя дважды определить ограничения доступа.');
    }

    public function set_rules($rules){
        if(!isset(self::$rules)){
            self::$rules = $rules;
        }else
            throw new exception('Нельзя дважды определить правила доступа.');
    }
    /**
    * Настройка кук
    */
    private static function set_cockies(){
        setcookie("chat_host", application_configuration::chat_host, 0);
        setcookie("chat_port", application_configuration::chat_port, 0);
    }
}