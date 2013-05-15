<?php
class model_session{

    private static $user;
    private static $company;
    private static $rules;
    private static $restrictions;
    private static $links;

    public static function get_restrictions(){
        return self::$restrictions;
    }

    public static function get_rules(){
        return self::$rules;
    }

    public static function get_user(){
        return self::$user;
    }
    public static function get_company(){
        return self::$company;
    }

    public static function set_user(data_current_user $user){
        if(!isset(self::$user)){
            self::set_cockies();
            $_SESSION['user'] = $user;
            self::$user = $user;
        }else
            throw new exception('Нельзя дважды определить пользователя.');
    }

    public static function set_company(data_company $company){
        if(!isset(self::$company)){
            $_SESSION['company'] = $company;
            self::$company = $company;
        }else
            throw new exception('Нельзя дважды определить компанию.');
    }
    
    public static function set_restrictions($restrictions){
        if(!isset(self::$restrictions)){
            self::$restrictions = $restrictions;
        }else
            throw new exception('Нельзя дважды определить ограничения доступа.');
    }

    public static function set_rules($rules){
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