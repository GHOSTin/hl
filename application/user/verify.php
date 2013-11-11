<?php
class verify_user{

  private static $cellphone = '/^\+7[0-9]{10}$/';
  private static $firstname = '/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}$/u';
  private static $middlename = '/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}$/u';
  private static $lastname = '/^[АБВГДЕЖЗИКЛМНОПРСТУФХЦЧШЩЭЮЯ][а-я]{0,19}+$/u';
  private static $login = '/^[a-zA-Z0-9]{6,20}$/u';
  private static $telephone = '/^[0-9]{2,11}$/';

  /**
  * Верификация сотового телефона.
  */
  public static function verify_cellphone($cellphone){
    // if(!empty($cellphone))
    //   if(!preg_match(self::$cellphone, $cellphone))
    //     throw new e_model('Номер сотового телефона пользователя задан не верно.');
  }

  /**
  * Верификация имени.
  */
  public static function verify_firstname($name){
    // if(!preg_match(self::$firstname, $name))
    //   throw new e_model('Имя пользователя задано не верно.');
  }

  /**
  * Верификация отчества.
  */
  public static function verify_middlename($name){
    // if(!empty($name))
    //   if(!preg_match(self::$middlename, $name))
    //     throw new e_model('Отчество пользователя задано не верно.');
  }

  /**
  * Верификация идентификатора.
  */
  public static function verify_id($id){
    if($id > 65535 OR $id < 1)
      throw new e_model('Идентификатор пользователя задан не верно.');
  }

  /**
  * Верификация фамилии.
  */
  public static function verify_lastname($name){
    // if(!preg_match(self::$lastname, $name))
    //   throw new e_model('Фамилия пользователя задана не верно.');
  }

  /**
  * Верификация логина.
  */
  public static function verify_login($login){
    // if(!preg_match(self::$login, $login))
    //   throw new e_model('Логин не удовлетворяет a-zA-Z0-9
    //   или меньше 6 символов.');
  }

  /**
  * Верификация пароля.
  */
  public static function verify_password($password){
    if(!preg_match('/^[a-zA-Z0-9]{8,20}$/', $password))
      throw new e_model('Пароль не удовлетворяет a-zA-Z0-9
      или меньше 8 или больше 20 символов.');
  }

  /**
  * Верификация статуса.
  */
  public static function verify_status($status){
    if(!in_array($status, data_user::$statuses, true))
        throw new e_model('Статус пользователя задан не верно.');
  }
 
  /**
  * Верификация телефона.
  */
  public static function verify_telephone($telephone){
    if(!empty($telephone))
      if(!preg_match(self::$telephone, $telephone))
        throw new e_model('Номер телефона пользователя задан не верно.');
  }
}