<?php
class model_session{

    private static $rules;
    private static $restrictions;
    private static $links;
    private static $settings;
    private static $session;
    private static $profile;

    public static function get_restrictions(){
        return self::$restrictions;
    }

    public static function get_rules(){
        return self::$rules;
    }

    public static function get_profile(){
        return self::$profile;
    }

    public static function set_profile(data_profile $profile){
        self::$profile = $profile;
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