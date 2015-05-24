<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="numbers")
*/
class number{

  /**
  * @OneToMany(targetEntity="domain\accrual", mappedBy="number")
  * @OrderBy({"time" = "DESC"})
  */
  private $accruals;

  /**
  * @Column(type="string")
  */
  private $cellphone;

  /**
  * @Column(type="decimal", precision=12, scale=2)
  */
  private $debt = 0;

  /**
  * @Column(type="string")
  */
  private $fio;

  /**
  * @ManyToOne(targetEntity="domain\flat")
  */
  private $flat;

  /**
  * @Column(name="password", type="string")
  */
  private $hash = '';

  /**
  * @ManyToOne(targetEntity="domain\house")
  */
  private $house;

  /**
  * @Id
  * @Column(type="integer")
  * @GeneratedValue
  */
  private $id;

  /**
  * @Column(type="string")
  */
  private $number;

  /**
  * @Column(type="string")
  */
  private $status;

  /**
  * @Column(type="string")
  */
  private $telephone;

  /**
  * @Column(type="string")
  */
  private $email;

  /**
  * @ManyToMany(targetEntity="domain\query", mappedBy="numbers")
  */
  private $queries;

  /**
  * @Column(name="notification_rules", type="json_array")
  */
  private $notification_rules = [];

  /**
  * @OneToMany(targetEntity="domain\number2event", mappedBy="number", cascade="all")
  * @OrderBy({"time" = "DESC"})
  */
  private $events;

  public function __construct(){
    $this->queries = new ArrayCollection();
    $this->accruals = new ArrayCollection();
    $this->events = new ArrayCollection();
    $this->status = 'true';
  }

  public function add_event(number2event $event){
    if($this->events->contains($event))
      throw new DomainException('Событие уже добавлено');
    $this->events->add($event);
  }

  public function exclude_event(number2event $event){
    $this->events->removeElement($event);
  }

  public static function generate_hash($password, $salt){
    return md5(md5(htmlspecialchars($password)).$salt);
  }

  public function get_accruals(){
    return $this->accruals;
  }

  public function get_sort_accruals(){
    $month = [];
    foreach($this->accruals as $accrual){
      $month[$accrual->get_time()][] = $accrual;
    }
    return $month;
  }

  public function get_cellphone(){
    return $this->cellphone;
  }

  public function get_debt(){
    return $this->debt;
  }

  public function get_email(){
    return $this->email;
  }

  public function get_events(){
    return $this->events;
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

  public function get_notification_rules(){
    return $this->notification_rules;
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

  public static function new_instance(house $house, flat $flat,  $num, $fio){
    $number = new number();
    $number->set_house($house);
    $number->set_flat($flat);
    $number->set_number($num);
    $number->set_fio($fio);
    return $number;
  }

  public function set_cellphone($cellphone){
    if(!empty($cellphone))
      if(!preg_match('/^[0-9]{10}$/', $cellphone))
        throw new DomainException('Номер сотового телефона задан не верно.');
  	$this->cellphone = $cellphone;
  }

  public function set_email($email){
    if(!preg_match('|[0-9A-Za-z.@\-_]{0,128}|', $email))
      throw new DomainException('Не валидный email.');
    $this->email = $email;
  }

  public function set_cellphone_notification_rule($status){
    $this->notification_rules['cellphone'] = ($status === 'on')? true: false;
  }

  public function set_debt($value){
    $this->debt = (float) str_replace(',', '.', $value);
  }

  public function set_email_notification_rule($status){
    $this->notification_rules['email'] = ($status === 'on')? true: false;
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

  public function set_house(house $house){
    $this->house = $house;
  }

  public function set_number($number){
    $number = trim($number);
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