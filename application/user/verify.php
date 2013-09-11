<?php
class verify_user{

    /**
    * Верификация сотового телефона.
    */
    public static function cellphone(data_user $user){
        if(!empty($user->get_cellphone()))
            if(!preg_match('/^\+7[0-9]{10}$/', $user->get_cellphone()))
                throw new e_model('Номер сотового телефона пользователя задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_user $user){
        $company = new data_company();
        $company->set_id($user->get_company_id());
        $company->verify('id');
    }

    /**
    * Верификация имени.
    */
    public static function firstname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}+$/u', $user->get_firstname()))
            throw new e_model('Имя пользователя задано не верно.');
    }

    /**
    * Верификация отчества.
    */
    public static function middlename(data_user $user){
        if(!empty($user->get_middlename()))
            if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}+$/u', $user->get_middlename()))
                throw new e_model('Отчество пользователя задано не верно.');
    }

    /**
    * Верификация идентификатора.
    */
    public static function id(data_user $user){
        if(!preg_match('/^[0-9]{1,5}$/', $user->get_id()))
            throw new e_model('Идентификатор пользователя задан не верно.');
        if($user->get_id() > 65535 OR $user->get_id() < 1)
            throw new e_model('Идентификатор пользователя задан не верно.');
    }

    /**
    * Верификация фамилии.
    */
    public static function lastname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}+$/u', $user->get_lastname()))
            throw new e_model('Фамилия пользователя задана не верно.');
    }

    /**
    * Верификация логина.
    */
    public static function login(data_user $user){
        if(!preg_match('/^[a-zA-Z0-9]{6,20}$/u', $user->get_login()))
            throw new e_model('Логин не удовлетворяет a-zA-Z0-9 или меньше 6 символов.');
    }

    /**
    * Верификация пароля.
    */
    public static function password(data_user $user){
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $user->get_password()))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 или больше 20 символов.');
    }

    /**
    * Верификация статуса.
    */
    public static function status(data_user $user){
        if(!in_array($user->get_status(), ['true', 'false']))
            throw new e_model('Статус пользователя задан не верно.');
    }
 
    /**
    * Верификация телефона.
    */
    public static function telephone(data_user $user){
        if(!empty($user->get_telephone()))
            if(!preg_match('/^[0-9]{2,11}$/', $user->get_telephone()))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }
}