<?php
class verify_user{

    /**
    * Верификация сотового телефона.
    */
    public static function cellphone(data_user $user){
        if(!empty($user->cellphone))
            if(!preg_match('/^\+7[0-9]{10}$/', $user->cellphone))
                throw new e_model('Номер сотового телефона пользователя задан не верно.');
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_user $user){
        $company = new data_company();
        $company->id = $user->company_id;
        $company->verify('id');
    }

    /**
    * Верификация имени.
    */
    public static function firstname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]+$/u', $user->firstname))
            throw new e_model('Имя пользователя задано не верно.');
    }

    /**
    * Верификация отчества.
    */
    public static function middlename(data_user $user){
        if(!empty($user->middlename))
            if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]+$/u', $user->middlename))
                throw new e_model('Отчество пользователя задано не верно.');
    }

    /**
    * Верификация идентификатора.
    */
    public static function id(data_user $user){
        if($user->id < 1)
            throw new e_model('Идентификатор пользователя задан не верно.');
    }

    /**
    * Верификация фамилии.
    */
    public static function lastname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]+$/u', $user->lastname))
            throw new e_model('Фамилия пользователя задана не верно.');
    }

    /**
    * Верификация логина.
    */
    public static function login(data_user $user){
        if(!preg_match('/^[a-zA-Z0-9]{6,}$/u', $user->login))
            throw new e_model('Логин не удовлетворяет a-zA-Z0-9 или меньше 6 символов.');
    }

    /**
    * Верификация пароля.
    */
    public static function password(data_user $user){
        if(!preg_match('/^[a-zA-Z0-9]{8,20}$/u', $user->password))
            throw new e_model('Пароль не удовлетворяет a-zA-Z0-9 или меньше 8 или больше 20 символов.');
    }
    }

    /**
    * Верификация статуса.
    */
    public static function status(data_user $user){
        if(!in_array($user->status, ['true', 'false']))
            throw new e_model('Статус пользователя задан не верно.');
    }
 
    /**
    * Верификация телефона.
    */
    public static function telephone(data_user $user){
        if(!empty($user->telephone))
            if(!preg_match('/^[0-9]{2,11}$/', $user->celltelephonephone))
                throw new e_model('Номер телефона пользователя задан не верно.');
    }
}