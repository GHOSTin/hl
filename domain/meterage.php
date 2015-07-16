<?php namespace domain;

/**
* @Entity
*/
class meterage{

  /**
  * @Id
  * @Column
  */
  private $id;

  /**
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @Column(type="integer")
  */
  private $time;

  /**
  * @Column
  */
  private $service;

  /**
  * @Column(type="integer")
  */
  private $tarif;

  /**
  * @Column(type="json_array")
  */
  private $params = [];

  public function get_time(){
    return $this->time;
  }

  public function get_service(){
    return $this->service;
  }

  public function get_first(){
    return $this->params[0];
  }

  public function get_second(){
    if($this->tarif == 1)
      return 0;
    else
      return $this->params[1];
  }

  public static function new_instance(number $number, $time, $service, $tarif, $first, $second){
    $meterage = new self();
    $meterage->number = $number;
    $meterage->time = strtotime('12:00 first day of this month', $time);
    $meterage->id = date('Ymd', $meterage->time).sha1(implode([$time, $service, $tarif, $first, $second, uniqid(), memory_get_usage()]));
    $meterage->service = $service;
    $meterage->tarif = (int) $tarif;
    $meterage->params[0] = (int) $first;
    if($tarif == 2)
      $meterage->params[1] = (int) $second;
    return $meterage;
  }
}