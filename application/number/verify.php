<?php
class verify_number{

    /**
    * Верификация сотового телефона лицевого счета.
    */
    public static function cellphone(data_number $number){
        if(!empty($number->cellphone))
            if(!preg_match('/^\+7[0-9]{10}$/', $number->cellphone))
                throw new e_model('Номер сотового телефона задан не верно.');
    }

    /**
    * Верификация идентификатора города.
    */
    public static function city_id(data_number $number){
        $city = new data_city();
        $city->id = $number->city_id;
        $city->verify('id');
    }

    /**
    * Верификация сотового телефона контактного лица.
    */
    public static function contact_cellphone(data_number $number){
        if(!empty($number->contact_cellphone))
            if(!preg_match('/^\+7[0-9]{10}$/', $number->contact_cellphone))
                throw new e_model('Номер сотового телефона задан не верно.');
    }

    /**
    * Верификация ФИО контактного лица.
    */
    public static function contact_fio(data_number $number){
        if(!preg_match('/^[А-Яа-я0-9]{0,20}$/u', $number->contact_fio))
            throw new e_model('ФИО контактного лица заданы не верно.');
    }

    /**
    * Верификация телефона контактного лица.
    */
    public static function contact_telephone(data_number $number){
        if(!empty($number->contact_telephone))
            if(!preg_match('/^[0-9]{4,11}$/', $number->contact_telephone))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_number $number){
        $company = new data_company();
        $company->id = $number->company_id;
        $company->verify('id');
    }

    /**
    * Верификация идентификатора участка.
    */
    public static function department_id(data_number $number){
        $department = new data_department();
        $department->id = $number->department_id;
        $department->verify();
    }

    /**
    * Верификация ФИО владельца лицевого счета.
    */
    public static function fio(data_number $number){
        if(!preg_match('/^[А-Яа-я0-9\. ]{3,20}$/u', $number->fio))
            throw new e_model('ФИО владельца лицевого счета заданы не верно.');
    }

    /**
    * Верификация идентификатора квартиры.
    */
    public static function flat_id(data_number $number){
        $flat = new data_flat();
        $flat->id = $number->flat_id;
        $flat->verify('id');
    }

    /**
    * Верификация идентификатора дома.
    */
    public static function house_id(data_number $number){
        $house = new data_house();
        $house->id = $number->house_id;
        $house->verify('id');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function id(data_number $number){
        if(!preg_match('/^[0-9]{1,8}$/', $number->get_id()))
            throw new e_model('Идентификатор лицевого счета задан не верно.');
        if($number->get_id() > 16777215 OR $number->get_id() < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация номера лицевого счета.
    */
    public static function number(data_number $number){
        if(!preg_match('/^[0-9]{1,20}$/', $number->number))
            throw new e_model('Номер лицевого счета задан не верно.');
    }

    /**
    * Верификация пароля лицевого счета.
    */
    public static function password(data_number $number){
        if(!preg_match('/^[A-Za-z0-9]{6,20}$/', $number->password))
            throw new e_model('Пароль лицевого счета задан не верно.');
    }

    /**
    * Верификация статуса лицевого счета.
    */
    public static function status(data_number $number){
        if(!in_array($number->status, ['true', 'false']))
            throw new e_model('Статус лицевого счета задан не верно.');
    }
    
    /**
    * Верификация телефона владельца лицевого счета.
    */
    public static function telephone(data_number $number){
        if(!empty($number->telephone))
            if(!preg_match('/^[0-9]{2,11}$/', $number->telephone))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }

    /**
    * Верификация типа лицевого счета.
    */
    public static function number_type(data_number $number){
        if(!$number->type !== 'human')
            throw new e_model('Тип лицевого счета задан не верно.');
    }
}