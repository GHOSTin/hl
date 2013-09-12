<?php
class verify_flat{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_flat $flat){
        $company = new data_company();
        $company->set_id($flat->get_company_id());
        $company->verify('id');
    }

    /**
    * Верификация идентифкатора дома.
    */
    public static function house_id(data_flat $flat){
        $house = new data_house();
        $house->set_id($flat->get_house_id());
        $house->verify('id');
    }

    /**
    * Верификация идентификатора квартиры.
    */
    public static function id(data_flat $flat){
        if(!preg_match('/^[0-9]{1,8}$/', $flat->get_id()))
            throw new e_model('Идентификатор квартиры задан не верно.');
        if($flat->get_id() > 16777215 OR $flat->get_id() < 1)
            throw new e_model('Идентификатор квартиры задан не верно.');
    }

    /**
    * Верификация номера квартиры.
    */
    public static function number(data_flat $flat){
        if(!preg_match('/^[0-9]{1,3}$/', $flat->get_number()))
            throw new e_model('Номер квартиры задан не верно.');
    }

    /**
    * Верификация статуса квартиры.
    */
    public static function status(data_flat $flat){
        if(!in_array($flat->get_status(), ['true', 'false']))
            throw new e_model('Статус квартиры задан не верно.');
    }
}