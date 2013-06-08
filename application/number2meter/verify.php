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

    /**
    * Верификация даты выпуска счетчика.
    */
    public static function date_release(data_number2meter $number2meter){
        if($number2meter->date_release < 1)
            throw new e_model('Дата выпуска счетчика задана не верно.');
    }

    /**
    * Верификация даты установки счетчика.
    */
    public static function date_install(data_number2meter $number2meter){
        if($number2meter->date_install < 0)
            throw new e_model('Время даты установки задано не верно.');
    }

    /**
    * Верификация даты поверки счетчика.
    */
    public static function date_checking(data_number2meter $number2meter){
        if($number2meter->date_checking < 0)
            throw new e_model('Время даты поверки задано не верно.');
    }

    /**
    * Верификация места установки счетчика.
    */
    public static function place(data_number2meter $number2meter){
        if(array_search($number2meter->place, ['bathroom', 'kitchen', 'toilet']) === false)
            throw new e_model('Место установки задано не верно.');
    }

    /**
    * Верификация периода счетчика.
    */
    public static function period(data_number2meter $number2meter){
        if($number2meter->period < 1 OR $number2meter->period > 240)
            throw new e_model('Период задан не верно.');
    }

    /**
    * Верификация службы счетчика.
    */
    public static function service(data_number2meter $number2meter){
        $services = ['cold_water', 'hot_water', 'electrical'];
            if(array_search($number2meter->service, $services) === false)
                throw new e_model('Услуга задана не верно.');
    }
}