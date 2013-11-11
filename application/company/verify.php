<?php
class verify_company{

    /**
    * Верификация названия.
    */
    public static function verify_name($name){
        if(!preg_match('/^[А-Я][а-я]{0,19}$/', $name))
            throw new e_model('Название компании задано не верно.');
    }

    /**
    * Верификация статуса.
    */
    public static function verify_status($status){
        if(!in_array($status, data_company::$statuses))
            throw new e_model('Статус компании задан не верно.');
    }

    /**
    * Верификация идентификатора.
    */
    public static function verify_id($id){
      if($id > 255 OR $id < 1)
        throw new e_model('Идентификатор компании задан не верно.');
    }
}