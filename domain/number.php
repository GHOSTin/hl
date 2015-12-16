<?php namespace domain;

use DomainException;
use Doctrine\Common\Collections\ArrayCollection;

/**
* @Entity
* @Table(name="numbers")
*/
class number{

  use traits\cellphone;

  /**
  * @OneToMany(targetEntity="domain\accrual", mappedBy="number")
  * @OrderBy({"time" = "DESC"})
  */
  private $accruals;

  /**
  * @Column(nullable=true)
  */
  private $cellphone;

  /**
  * @Column(type="decimal", precision=12, scale=2)
  */
  private $debt = 0;

  /**
  * @Column
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
  * @Column
  */
  private $number;

  /**
  * @Column
  */
  private $status;

  /**
  * @Column(nullable=true)
  */
  private $telephone;

  /**
  * @Column(nullable=true)
  */
  private $email;

  /**
  * @ManyToMany(targetEntity="domain\query", mappedBy="numbers")
  * @OrderBy({"time_open" = "DESC"})
  */
  private $queries;

  /**
  * @Column(name="notification_rules", type="json_array")
  */
  private $notification_rules = [
                                  'email' => true,
                                  'cellphone' => true
                                ];
  /**
  * @Column(type="json_array")
  */
  private $relevance = [];


  /**
  * @OneToMany(targetEntity="domain\number2event", mappedBy="number", cascade="all")
  * @OrderBy({"time" = "DESC"})
  */
  private $events;

  /**
  * @OneToMany(targetEntity="domain\meterage", mappedBy="number")
  * @OrderBy({"time" = "DESC"})
  */
  private $meterages;

  public function __construct(){
    $this->queries = new ArrayCollection();
    $this->accruals = new ArrayCollection();
    $this->events = new ArrayCollection();
    $this->meterages = new ArrayCollection();
    $this->status = 'true';
  }

  public function add_event(event $event, $date, $comment){
    $n2e = new number2event($this, $event, $date, $comment);
    if($this->events->contains($n2e))
      throw new DomainException('Событие уже добавлено');
    $this->events->add($n2e);
    return $n2e;
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

  public function get_meterages_by_month(){
    $month = [];
    foreach($this->meterages as $meterage){
      $month[$meterage->get_time()][] = $meterage;
    }
    return $month;
  }

  public function get_last_month_meterages(){
    $time = strtotime('12:00 first day of last month');
    return $this->meterages->filter(function($element) use ($time){
      return $element->get_time() == $time;
    });
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

  public function get_queries_for_lk(){
    return $this->queries->filter(function($element){
      return $element->get_initiator() == 'number' AND $element->is_visible() OR $element->get_request();
    });
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
  	$this->cellphone = $this->prepare_cellphone($cellphone);
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
        throw new DomainException('Номер телефона пользователя задан не верно '. $telephone);
  	$this->telephone = $telephone;
  }

  public function update_contacts(user $user, $fio, $telephone, $cellphone, $email){
    $this->set_fio($fio);
    $this->set_telephone($telephone);
    $this->set_cellphone($cellphone);
    $this->set_email($email);
    $this->relevance[] = [
                            'time' => time(),
                            'fio' => $fio,
                            'telephone' => $telephone,
                            'cellphone' => $cellphone,
                            'email' => $email,
                            'user' => $user->get_fio().' (id = '.$user->get_id().')'
                          ];
    $this->relevance = array_slice($this->relevance, -10);
  }

  public function get_relevance_time(){
    if(count($this->relevance) > 0)
      return array_reverse($this->relevance)[0]['time'];
  }

  public function get_relevance(){
    return $this->relevance;
  }

  public function get_full_number(){
    return 'кв. №'.$this->flat->get_number().' '.$this->fio.' (л/с №'.$this->number.')' ;
  }

  public function get_address(){
    return $this->house->get_street()->get_name().', дом №'.$this->house->get_number().', кв. №'.$this->flat->get_number();
  }
}