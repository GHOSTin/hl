<?php
class verify_street{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_street $street){
        $company = new data_company();
        $company->id = $street->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора города.
    */
    public static function city_id(data_street $street){
        $city = new data_city();
        $city->id = $street->city_id;
        $city->verify('id');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_street $street){
        $department = new data_department();
        $department->id = $street->department_id;
        $department->verify('id');
    }

    /**
    * Верификация идентификатора улицы.
    */
    public static function id(data_street $street){
        if(!preg_match('/^[0-9]{1,5}$/', $street->id))
            throw new e_model('Идентификатор улицы задан не верно.');
        if($street->id > 65535 OR $street->id < 1)
            throw new e_model('Идентификатор улицы задан не верно.');
    }

    /**
    * Верификация статуса улицы.
    */
    public static function status(data_street $street){
        if(in_array($street->status, ['true', 'false']))
            throw new e_model('Статус улицы задан не верно.');
    }

    /**
    * Верификация названия улицы.
    */
    public static function verify_name(data_street $street){
        if(!preg_match('/^[а-яА-Я]+$/u', $street->name))
            throw new e_model('Название улицы задано не верно.');
    }
}