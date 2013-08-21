<?php
class verify_meter2data{

    public static function comment(data_meter2data $data){
        if(!preg_match('/^[А-Яа-я0-9\., ]{0,255}$/u', $data->get_comment()))
            throw new e_model('Комментарий задан не верно.');
    }

    public static function company_id(data_meter2data $data){
        $company = new data_company();
        $company->id = $data->get_company_id();
        $company->verify('id');
    }

    public static function meter_id(data_meter2data $data){
        $meter = new data_meter();
        $meter->id = $data->get_meter_id();
        $meter->verify('id');
    }

    public static function number_id(data_meter2data $data){
        $number = new data_number();
        $number->id = $data->get_number_id();
        $number->verify('id');
    }

    public static function serial(data_meter2data $data){
        $meter = new data_number2meter();
        $meter->set_serial($data->get_serial());
        $meter->verify('serial');
    }

    public static function time(data_meter2data $data){
        (new verify_environment)->verify_unix_time($data->get_time());
    }

    /**
    * Верификация времени передачи показания.
    */
    public static function timestamp(data_meter2data $data){
        (new verify_environment)->verify_unix_time($data->get_timestamp());
    }

    public static function value(data_meter2data $data){
        foreach($data->get_value() as $value)
            if($value < 0)
                throw new e_model('Показание задано не верно');                
    }

    /**
    * Верификация способа передачи показания.
    */
    public static function way(data_meter2data $data){
        $ways = ['answerphone', 'telephone', 'fax', 'personally'];
        if(!in_array($data->get_way(), $ways, true))
            throw new e_model('Спсоб передачи показания задан не верно.');
    }
}