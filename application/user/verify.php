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
    * Верификация идентификатора лицевого счета.
    */
    public static function id(data_user $number){
        if($number->id < 1)
            throw new e_model('Идентификатор лицевого счета задан не верно.');
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