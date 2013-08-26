<?php
class verify_house{

    /**
    * Верификация идентификатора города.
    */
    public static function city_id(data_house $house){
        $city = new data_city();
        $city->id = $house->city_id;
        $city->verify('id');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_house $house){
        $company = new data_company();
        $company->id = $house->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_house $house){
        $department = new data_department();
        $department->id = $house->department_id;
        $department->verify('id');
    }

    /**
    * Верификация идентификатора дома.
    */
    public static function id(data_house $house){
        if(!preg_match('/^[0-9]{1,5}$/', $house->get_id()))
            throw new e_model('Идентификатор дома задан не верно.');
        if($house->get_id() > 65535 OR $house->get_id() < 1)
            throw new e_model('Идентификатор дома задан не верно.');
    }

    /**
    * Верификация номера дома.
    */
    public static function number(data_house $house){
        if(!preg_match('/^[0-9]{1,3}[\/]{0,1}[А-Я]{0,1}$/', $house->number))
            throw new e_model('Номер дома задан не верно.');
    }

    /**
    * Верификация статуса дома.
    */
    public static function status(data_house $house){
        if(!in_array($house->status, ['true', 'false']))
            throw new e_model('Статус дома задан не верно.');
    }
}