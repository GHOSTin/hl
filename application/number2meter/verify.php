<?php
class verify_number2meter{

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_number2meter $number2meter){
        if($number2meter->company_id < 1 OR $number2meter->company_id > 254)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация идентификатора счетчика.
    */
    public static function meter_id(data_number2meter $number2meter){
        if($number2meter->meter_id < 1)
            throw new e_model('Идентификатор счетчика задан не верно.');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function number_id(data_number2meter $number2meter){
        if($number2meter->number_id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация заводского номера счетчика.
    */
    public static function serial(data_number2meter $number2meter){
        if(!preg_match('/^[а-яА-Я0-9]+$/u', $number2meter->serial))
            throw new e_model('Заводской номер счетчика задано не верно.');
    }
}