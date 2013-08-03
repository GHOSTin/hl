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
        if($house->id < 1)
            throw new e_model('Идентификатор дома задан не верно.');
    }

    /**
    * Верификация номера дома.
    */
    public static function number(data_house $house){
        if(empty($house->number))
            throw new e_model('Номер дома задан не верно.');
    }

    /**
    * Верификация статуса дома.
    */
    public static function status(data_house $house){
        if(empty($house->status))
            throw new e_model('Статус дома задан не верно.');
    }















    /**
    * Верификация идентификатора работы.
    */
    public static function id(data_work $work){
        if($work->id < 1)
            throw new e_model('Идентификатор работы задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_work $work){
        $company = new data_company();
        $company->id = $work->company_id;
        $company->verify('id');
    }

    /**
    * Верификация названия работы.
    */
    public static function name(data_work $work){
        if(!preg_match('/^[а-яА-Я]+$/u', $work->name))
            throw new e_model('Название работы не удовлетворяет "а-яА-Я".');
    }

    /**
    * Верификация статуса работы.
    */
    public static function status(data_work $work){
        if(in_array($work->status, ['true', 'false']))
            throw new e_model('Статус работы задан не верно.');
    }

    /**
    * Верификация идентификатора группы работ.
    */
    public static function workgroup_id(data_work $work){
        $worgroup = new data_workgroup();
        $worgroup->id = $work->workgroup_id;
        $worgroup->verify('id');
    }
}