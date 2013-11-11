<?php
class verify_query{

  /**
  * Верификация идентификатора заявки.
  */
  public static function verify_id($id){
    if($id > 4294967295 OR $id < 1)
      throw new e_model('Идентификатор заявки задан не верно.');
  }

  /**
  * Верификация статуса заявки.
  */
  public static function verify_status($status){
    if(!in_array($status, ['open', 'close', 'working', 'reopen']))
      throw new e_model('Статус заявки задан не верно.');
  }

  /**
  * Верификация инициатора заявки.
  */
  public static function verify_initiator($initiator){
    if(!in_array($initiator, ['number', 'house']))
      throw new e_model('Инициатор заявки задан не верно.');
  }

  /**
  * Верификация статуса оплаты заявки.
  */
  public static function verify_payment_status($status){
    if(!in_array($status, ['paid', 'unpaid', 'recalculation']))
      throw new e_model('Статус оплаты заявки задан не верно.');
  }

  /**
  * Верификация ворнинга заявки.
  */
  public static function verify_warning_status($status){
    if(!in_array($status, ['hight', 'normal', 'planned']))
      throw new e_model('Статус ворнинга заявки задан не верно.');
  }

  /**
  * Верификация идентификатора причины закрытия.
  */
  public static function verify_close_reason_id($reason){
    if($reason < 1)
      throw new e_model('Идентификатор причины закрытия задан не верно.');
  }

  /**
  * Верификация времени открытия заявки.
  */
  public static function verify_time_open($time){
    if($time < 1)
        throw new e_model('Время открытия заявки задано не верно.');
  }

  /**
  * Верификация времени передачи в работу заявки.
  */
  public static function verify_time_work($time){
    if($time < 1)
        throw new e_model('Время передачи в работу заявки задано не верно.');
  }

  /**
  * Верификация времени закрытия заявки.
  */
  public static function verify_time_close($time){
    if(!empty($time))
      if($time < 1)
        throw new e_model('Время закрытия заявки задано не верно.');
  }

  /**
  * Верификация ФИО контакта заявки.
  */
  public static function verify_contact_fio($fio){
    // if(!empty($fio))
    //   if(!preg_match('/^[А-Яа-я\. ]{0,255}$/u', $fio))
    //     throw new e_model('ФИО контактного лица задано не верно.');
  }

  /**
  * Верификация телефона контакта заявки.
  */
  public static function verify_contact_telephone($telephone){
    // if(!empty($telephone))
    //   if(!preg_match('/^[0-9]{2,11}$/', $telephone))
    //     throw new e_model('Номер телефона пользователя задан не верно.');
  }

  /**
  * Верификация сотового телефона контакта заявки.
  */
  public static function verify_contact_cellphone($cellphone){
    // if(!empty($cellphone))
    //   if(!preg_match('/^\+7[0-9]{10}$/', $cellphone))
    //     throw new e_model('Номер сотового телефона пользователя задан не верно.');
  }

  /**
  * Верификация описания заявки.
  */
  public static function verify_description($text){
    // if(!preg_match('/^[А-Яа-яA-Za-z0-9\.,\?\'":;№\- ]{1,65535}$/u', $text))
    //   throw new e_model('Описание заявки заданы не верно.');
  }

  /**
  * Верификация причины закрытия заявки.
  */
  public static function verify_close_reason(data_query $text){
    // if(!preg_match('/^[А-Яа-яA-Za-z0-9\.,\?\'":;№\- ]{0,65535}$/u', $text)))
    //   throw new e_model('Описание заявки заданы не верно.');
  }

  /**
  * Верификация номера заявки.
  */
  public static function verify_number($number){
    if(!preg_match('/^[0-9]{1,6}$/', $numbers))
        throw new e_model('Номер заявки задан не верно.');
  }

  /**
  * Верификация инспеции заявки.
  */
  public static function verify_inspection(data_query $query){
  }
}