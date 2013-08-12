<?php
class verify_number2meter{

    /**
    * Верификация коментария компании.
    */
    public static function comment(data_number2meter $number2meter){
        if(!preg_match('/^[А-Яа-я0-9\., ]{0,255}$/u', $number2meter->comment))
            throw new e_model('Комментарий задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_number2meter $number2meter){
        $company = new data_company();
        $company->id = $number2meter->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора счетчика.
    */
    public static function meter_id(data_number2meter $number2meter){
        $meter = new data_meter();
        $meter->id = $number2meter->meter_id;
        $meter->verify('id');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function number_id(data_number2meter $number2meter){
        $number = new data_number();
        $number->id = $number2meter->number_id;
        $number->verify('id');
    }

    /**
    * Верификация заводского номера счетчика.
    */
    public static function serial(data_number2meter $number2meter){
        if(!preg_match('/^[а-яА-Я0-9]{1,20}$/u', $number2meter->serial))
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
        if($number2meter->date_install < 1)
            throw new e_model('Время даты установки задано не верно.');
    }

    /**
    * Верификация даты поверки счетчика.
    */
    public static function date_checking(data_number2meter $number2meter){
        if($number2meter->date_checking < 1)
            throw new e_model('Время даты поверки задано не верно.');
    }

    /**
    * Верификация места установки счетчика.
    */
    public static function place(data_number2meter $number2meter){
        if(!in_array($number2meter->place, ['bathroom', 'kitchen', 'toilet']))
            throw new e_model('Место установки задано не верно.');
    }

    /**
    * Верификация периода счетчика.
    */
    public static function period(data_number2meter $number2meter){
        if($number2meter->period < 1 OR $number2meter->period > 241)
            throw new e_model('Период задан не верно.');
    }

    /**
    * Верификация услуги счетчика, привязанного к лицевому счету.
    */
    public static function service(data_number2meter $number2meter){
        $meter = new data_meter();
        $meter->service = $number2meter->service;
        $meter->verify('service');
    }

    /**
    * Верификация статуса счетчика.
    */
    public static function status(data_number2meter $number2meter){
        if(!in_array($number2meter->status, ['enabled', 'disabled']))
            throw new e_model('Статус счетчика задан не верно.');
    }
}