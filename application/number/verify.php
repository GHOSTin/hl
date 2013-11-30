<?php
class verify_number{

    /**
    * Верификация сотового телефона лицевого счета.
    */
    public static function cellphone(data_number $number){
        if(!empty($number->get_cellphone()))
            if(!preg_match('/^\+7[0-9]{10}$/', $number->get_cellphone()))
                throw new e_model('Номер сотового телефона задан не верно.');
    }

    /**
    * Верификация сотового телефона контактного лица.
    */
    public static function contact_cellphone(data_number $number){
        if(!empty($number->get_contact_cellphone()))
            if(!preg_match('/^\+7[0-9]{10}$/', $number->get_contact_cellphone()))
                throw new e_model('Номер сотового телефона задан не верно.');
    }

    /**
    * Верификация ФИО контактного лица.
    */
    public static function contact_fio(data_number $number){
        if(!preg_match('/^[А-Яа-я0-9]{0,20}$/u', $number->get_contact_fio()))
            throw new e_model('ФИО контактного лица заданы не верно.');
    }

    /**
    * Верификация телефона контактного лица.
    */
    public static function contact_telephone(data_number $number){
        if(!empty($number->get_contact_telephone()))
            if(!preg_match('/^[0-9]{4,11}$/', $number->get_contact_telephone()))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }

    /**
    * Верификация ФИО владельца лицевого счета.
    */
    public static function verify_fio($fio){
        if(!preg_match('/^[А-Яа-я0-9\. ]{3,255}$/u', $fio))
            throw new e_model('ФИО владельца лицевого счета заданы не верно.');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function verify_id($id){
        if($id > 16777215 OR $id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация номера лицевого счета.
    */
    public static function verify_num($num){
        if(!preg_match('/^[0-9А-Яа-я]{1,20}$/u', $num))
            throw new e_model('Номер лицевого счета задан не верно.');
    }

    /**
    * Верификация пароля лицевого счета.
    */
    public static function password(data_number $number){
        if(!preg_match('/^[A-Za-z0-9]{6,20}$/', $number->get_password()))
            throw new e_model('Пароль лицевого счета задан не верно.');
    }

    /**
    * Верификация статуса лицевого счета.
    */
    public static function status(data_number $number){
        if(!in_array($number->get_status(), ['true', 'false']))
            throw new e_model('Статус лицевого счета задан не верно.');
    }
    
    /**
    * Верификация телефона владельца лицевого счета.
    */
    public static function verify_telephone($telephone){
        if(!empty($telephone))
            if(!preg_match('/^[0-9]{2,11}$/', $telephone))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }
}