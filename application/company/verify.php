<?php
class verify_company{

    /**
    * Верификация идентификатора компании.
    */
    public static function id(data_company $company){
        if($company->id < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация названия компании.
    */
    public static function name(data_company $company){
        if(empty($company->name))
            throw new e_model('Название компании задано не верно.');
    }

    /**
    * Верификация статуса компании.
    */
    public static function status(data_company $company){
        if(!in_array($company->status, ['true', 'false']))
            throw new e_model('Статус компании задан не верно.');
    }
}