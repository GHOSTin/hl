<?php
class verify_street{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_street $street){
        $company = new data_company();
        $company->set_id($street->get_company_id());
        $company->verify('id');
    }

    /**
    * Верификация идентификатора города.
    */
    public static function city_id(data_street $street){
        $city = new data_city();
        $city->set_id($street->get_city_id());
        $city->verify('id');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_street $street){
        $department = new data_department();
        $department->set_id($street->get_department_id());
        $department->verify('id');
    }

    /**
    * Верификация идентификатора улицы.
    */
    public static function id(data_street $street){
        if(!preg_match('/^[0-9]{1,5}$/', $street->get_id()))
            throw new e_model('Идентификатор улицы задан не верно.');
        if($street->get_id() > 65535 OR $street->get_id() < 1)
            throw new e_model('Идентификатор улицы задан не верно.');
    }

    /**
    * Верификация статуса улицы.
    */
    public static function status(data_street $street){
        if(in_array($street->get_status(), ['true', 'false']))
            throw new e_model('Статус улицы задан не верно.');
    }

    /**
    * Верификация названия улицы.
    */
    public static function name(data_street $street){
        if(!preg_match('/^[а-яА-Я]+$/u', $street->get_name()))
            throw new e_model('Название улицы задано не верно.');
    }
}