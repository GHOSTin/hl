<?php
class verify_company{

    /**
    * Верификация идентификатора компании.
    */
    public static function id(data_company $company){
        if(!preg_match('/^[0-9]{1,3}$/', $company->get_id()))
            throw new e_model('Идентификатор компании задан не верно.');
        if($company->get_id() > 255 OR $company->get_id() < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация названия компании.
    */
    public static function name(data_company $company){
        if(!preg_match('/^[А-Я][а-я]{0,19}$/', $company->get_name()))
            throw new e_model('Название компании задано не верно.');
    }

    /**
    * Верификация статуса компании.
    */
    public static function status(data_company $company){
        if(!in_array($company->get_status(), ['true', 'false']))
            throw new e_model('Статус компании задан не верно.');
    }
}