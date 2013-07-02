<?php
class verify_user{

    /**
    * Верификация сотового телефона пользователя.
    */
    public static function cellphone(data_user $number){
    }

    /**
    * Верификация идентификатора компании.
    */
    public static function company_id(data_user $number){
        if($number->company_id < 1)
            throw new e_model('Идентификатор компании задан не верно.');
    }

    /**
    * Верификация имени пользователя.
    */
    public static function firstname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛЬНОПРСТУФХЦЧШЩЭЮЯ][а-яА-Я]+$/u', $user->firstname))
            throw new e_model('Имя пользователя задано не верно.');
    }

    /**
    * Верификация отчества пользователя.
    */
    public static function middlename(data_user $user){
        if(!empty($user->middlename))
            if(!preg_match('/^[АБВГДЕЖЗИКЛЬНОПРСТУФХЦЧШЩЭЮЯ][а-яА-Я]+$/u', $user->middlename))
                throw new e_model('Отчество пользователя задано не верно.');
    }

    /**
    * Верификация идентификатора лицевого счета.
    */
    public static function id(data_user $number){
        if($number->id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
    }

    /**
    * Верификация фамилии пользователя.
    */
    public static function lastname(data_user $user){
         if(!preg_match('/^[АБВГДЕЖЗИКЛЬНОПРСТУФХЦЧШЩЭЮЯ][а-яА-Я]+$/u', $user->lastname))
            throw new e_model('Фамилия пользователя задана не верно.');
    }

    /**
    * Верификация логина пользователя.
    */
    public static function login(data_user $user){
         if(strlen($user->login) < 6)
                throw new e_model('Логин имеет меньше 6 букв.');
        if(!preg_match('/^[а-яА-Яa-zA-Z0-9]+$/u', $user->login))
            throw new e_model('Логин не удовлетворяет а-яА-Яa-zA-Z0-9.');
    }

    /**
    * Верификация пароля лицевого счета.
    */
    public static function password(data_user $number){
        if(empty($number->password))
            throw new e_model('Пароль лицевого счета задан не верно.');
    }
 
    /**
    * Верификация телефона владельца лицевого счета.
    */
    public static function telephone(data_user $number){
        if(empty($number->telephone))
            throw new e_model('Телефон владельца лицевого счета задан не верно.');
    }
}