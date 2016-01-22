<?php namespace domain;

use DomainException;
use JsonSerializable;

/**
 * Запрос на получение доступа к системе.
 * Подается в анонимно из личного кабинета.
 * Отвергается или выдаётся доступ из интерфейса основной системы.
 *
 * @Entity
 */
class registration_request implements JsonSerializable{

  /**
   * Идентификатор запроса
   *
   * @Column
   * @Id
   */
  private $id;

  /**
   * ФИО лицевого счета
   *
   * @Column
   */
  private $fio;

  /**
   * Объект лицевого счета
   *
   * @ManyToOne(targetEntity="domain\number")
   *
   * @var object domain\number
   */
  private $number;

  /**
   * @Column
   * @var string
   */
  private $address;

  /**
   * @Column
   *
   * @var string
   */
  private $email;

  /**
   * @Column(nullable=true)
   *
   * @var string
   */
  private $telephone;

  /**
   * @Column(nullable=true)
   *
   * @var string
   */
  private $cellphone;

  public function __construct(number $number, $fio, $address, $email, $telephone, $cellphone){
    $this->id = self::generate_id($number, $fio, $address, $email);
    $this->number = $number;
    $this->fio = $fio;
    $this->address = $address;
    $this->email = $email;
    $this->telephone = $telephone;
    $this->cellphone = $cellphone;
  }

  public static function generate_id(number $number, $fio, $address, $email){
    return sha1(implode('_', [$number->get_number(), $fio, $address, $email, microtime()]));
  }

  public function get_fio(){
    return $this->fio;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_address(){
    return $this->address;
  }

  public function get_email(){
    return $this->email;
  }

  public function get_telephone(){
    return $this->telephone;
  }

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function JsonSerialize(){
    return [
             'id' => $this->id,
             'number' => $this->number,
             'address' => $this->address,
             'fio' => $this->fio,
             'email' => $this->email,
             'telephone' => $this->telephone,
             'cellphone' => $this->cellphone
           ];
  }
}