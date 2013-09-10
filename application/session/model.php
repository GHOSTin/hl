<?php
class model_session{

    private static $user;
    private static $company;
    private static $rules;
    private static $restrictions;
    private static $links;
    private static $settings;
    private static $session;

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

    public static function get_settings(){
        return self::$settings;
    }

    public static function get_setting_param($component, $param){
        return self::$settings[$component][$param];
    }

    public static function set_user(data_user $user){
        if(!isset(self::$user)){
            self::set_cockies();
            $_SESSION['user'] = $user;
            self::$user = $user;
            setcookie("uid", $user->id, 0);
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

    public static function set_session(component_session_manager $session){
        if(!isset(self::$session)){
            self::$session = $session;
        }else
            throw new exception('Нельзя дважды определить сессию компонента.');
    }

    public static function get_session(){
        return self::$session;
    }

    public static function set_settings($settings){
        if(!isset(self::$settings)){
            self::$settings = $settings;
        }else
            throw new exception('Нельзя дважды определить настройки.');
    }

    public static function set_setting_param($component, $param, $value){
        self::$settings[$component][$param] = $value;
        $_SESSION['settings'] = self::$settings;
    }

    /**
    * Настройка кук
    */
    private static function set_cockies(){
        setcookie("chat_host", application_configuration::chat_host, 0);
        setcookie("chat_port", application_configuration::chat_port, 0);
    }
}