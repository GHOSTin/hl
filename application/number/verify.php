<?php
class verify_number{
    /**
    * Верификация сотового телефона лицевого счета.
    */
    public static function cellphone(data_number $number){
    }
    /**
    * Верификация идентификатора города.
    */
    public static function city_id(data_number $number){
        if($number->city_id < 1)
            throw new e_model('Идентификатор города задан не верно.');
    }
    /**
    * Верификация сотового телефона контактного лица.
    */
    public static function contact_cellphone(data_number $number){
        if(empty($number->contact_cellphone))
            throw new e_model('Сотовый телефон контактного лица задан не верно.');
    }
    /**
    * Верификация ФИО контактного лица.
    */
    public static function contact_fio(data_number $number){
        if(empty($number->contact_fio))
            throw new e_model('ФИО контактного лица заданы не верно.');
    }
    /**
    * Верификация телефона контактного лица.
    */
    public static function contact_telephone(data_number $number){
        if(empty($number->contact_telephone))
            throw new e_model('Телефон контактного лица задан не верно.');
    }
    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_number $number){
        if($number->company_id < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }
    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_number $number){
        if($number->department_id < 1)
            throw new e_model('Идентификатор участка задан не верно.');
    }
    /**
    * Верификация ФИО владельца лицевого счета.
    */
    public static function fio(data_number $number){
        if(empty($number->fio))
            throw new e_model('ФИО владельца лицевого счета заданы не верно.');
    }
    /**
    * Верификация номера квартиры.
    */
    public static function flat_number(data_number $number){
        if(empty($number->flat_number))
            throw new e_model('Номер квартиры задан не верно.');
    }
    /**
    * Верификация идентификатора квартиры.
    */
    public static function flat_id(data_number $number){
        if($number->flat_id < 1)
            throw new e_model('Идентификатор квартиры задан не верно.');
    }
    /**
    * Верификация идентификатора дома.
    */
    public static function house_id(data_number $number){
        if($number->house_id < 1)
            throw new e_model('Идентификатор дома задан не верно.');
    }
    /**
    * Верификация номера дома.
    */
    public static function house_number(data_number $number){
        if(empty($number->house_number))
            throw new e_model('Номер дома задан не верно.');
    }
    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function id(data_number $number){
        if($number->id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }
    /**
    * Верификация номера лицевого счета.
    */
    public static function number(data_number $number){
        if(empty($number->number))
            throw new e_model('Номер лицевого счета задан не верно.');
    }
    /**
    * Верификация пароля лицевого счета.
    */
    public static function password(data_number $number){
        if(empty($number->password))
            throw new e_model('Пароль лицевого счета задан не верно.');
    }
    /**
    * Верификация статуса лицевого счета.
    */
    public static function status(data_number $number){
        if(empty($number->status))
            throw new e_model('Статус лицевого счета задан не верно.');
    }
    /**
    * Верификация названия улицы.
    */
    public static function street_name(data_number $number){
        if(empty($number->street_name))
            throw new e_model('Название улицы задано не верно.');
    }
    /**
    * Верификация телефона владельца лицевого счета.
    */
    public static function telephone(data_number $number){
        if(empty($number->telephone))
            throw new e_model('Телефон владельца лицевого счета задан не верно.');
    }
    /**
    * Верификация типа лицевого счета.
    */
    public static function number_type(data_number $number){
        if(empty($number->type))
            throw new e_model('Тип лицевого счета задан не верно.');
    }
}