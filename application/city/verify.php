<?php
class verify_city{

    /**
    * Верификация идентификатора города.
    */
    public static function id(data_city $city){
        if(!preg_match('/^[0-9]{1,5}$/', $city->id))
            throw new e_model('Идентификатор города задан не верно.');
        if($city->id > 65535 OR $city->id < 1)
            throw new e_model('Идентификатор города задан не верно.');
    }

    /**
    * Верификация названия города.
    */
    public static function name(data_city $city){
        if(!preg_match('/^[А-Я][а-я]{0,19}$/', $city->name))
            throw new e_model('Название города задано не верно.');
    }

    /**
    * Верификация статуса города.
    */
    public static function status(data_city $city){
        if(!in_array($city->status, ['false', 'true']))
            throw new e_model('Статус города задан не верно.');
    }
}