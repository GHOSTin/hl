<?php namespace domain;

use \DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="numbers")
*/
class number{

  /**
  * @OneToMany(targetEntity="\domain\accrual", mappedBy="number")
  */
  private $accruals;

  /**
  * @Column(name="cellphone", type="string")
  */
  private $cellphone;

  /**
  * @Column(name="fio", type="string")
  */
  private $fio;

  /**
  * @ManyToOne(targetEntity="\domain\flat")
  */
  private $flat;

  /**
  * @Column(name="password", type="string")
  */
  private $hash;

  /**
  * @ManyToOne(targetEntity="\domain\house")
  */
  private $house;

  /**
  * @Id
  * @Column(name="id", type="integer")
  */
  private $id;

  /**
  * @Column(name="number", type="string")
  */
  private $number;

  /**
  * @Column(name="status", type="string")
  */
  private $status;

  /**
  * @Column(name="telephone", type="string")
  */
  private $telephone;

  /**
  * @Column(name="email", type="string")
  */
  private $email;

  /**
  * @ManyToMany(targetEntity="\domain\query", mappedBy="numbers")
  */
  private $queries;

  public function __construct(){
    $this->queries = new ArrayCollection();
    $this->accruals = new ArrayCollection();
  }

  public static function generate_hash($password, $salt){
    return md5(md5(htmlspecialchars($password)).$salt);
  }

  public function get_accruals(){
    return $this->accruals;
  }

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_email(){
    return $this->email;
  }

  public function get_fio(){
    return $this->fio;
  }

  public function get_flat(){
    return $this->flat;
  }

  public function get_hash(){
    return $this->hash;
  }

  public function get_id(){
    return $this->id;
  }

  public function get_number(){
    return $this->number;
  }

  public function get_queries(){
    return $this->queries;
  }

  public function get_telephone(){
    return $this->telephone;
  }

  public function get_status(){
    return $this->status;
  }

  public function set_cellphone($cellphone){
    if(!empty($cellphone))
      if(!preg_match('/^[0-9]{10}$/', $cellphone))
        throw new DomainException('Номер сотового телефона задан не верно.');
  	$this->cellphone = $cellphone;
  }

  public function set_email($email){
    if(!preg_match('|[0-9A-Za-z.@-]{0,128}|', $email))
      throw new DomainException('Не валидный email.');
    $this->email = $email;
  }

  public function set_fio($fio){
    if(!preg_match('/^[А-ЯЁёа-я0-9\.,"№()* -<>]{1,255}$/u', $fio))
      throw new DomainException('Wrong number fio '.$fio);
  	$this->fio = $fio;
  }

  public function set_flat(\domain\flat $flat){
    $this->flat = $flat;
  }

  public function set_hash($hash){
    $this->hash = $hash;
  }

  public function set_id($id){
    if($id > 16777215 OR $id < 1)
      throw new DomainException('Идентификатор лицевого счета задан не верно.');
    $this->id = $id;
  }

  public function set_number($number){
    if(!preg_match('/^[0-9А-ЯA-Zа-яa-z]{0,20}$/u', $number))
      throw new DomainException('Wrong number number '.$number);
    $this->number = $number;
  }

  public function set_status($status){
    if(!in_array($status, ['true', 'false']))
      throw new DomainException('Статус лицевого счета задан не верно.');
    $this->status = $status;
  }

  public function set_telephone($telephone){
    if(!empty($telephone))
      if(!preg_match('/^[0-9]{2,11}$/', $telephone))
        throw new DomainException('Номер телефона пользователя задан не верно.');
  	$this->telephone = $telephone;
  }
}