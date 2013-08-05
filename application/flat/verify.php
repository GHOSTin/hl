<?php
class verify_flat{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_flat $flat){
        $company = new data_company();
        $company->id = $flat->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентифкатора дома.
    */
    public static function house_id(data_flat $flat){
        $house = new data_house();
        $house->id = $flat->house_id;
        $house->verify('id');
    }

    /**
    * Верификация идентификатора квартиры.
    */
    public static function id(data_flat $flat){
        if($flat->id < 1)
            throw new e_model('Идентификатор квартиры задан не верно.');
    }

    /**
    * Верификация номера квартиры.
    */
    public static function number(data_flat $flat){
        if(empty($flat->number))
            throw new e_model('Номер квартиры задан не верно.');
    }

    /**
    * Верификация статуса квартиры.
    */
    public static function verify_status(data_flat $flat){
        if(!in_array($flat->status, ['true', 'false']))
            throw new e_model('Статус квартиры задан не верно.');
    }
}