<?php namespace domain;
/**
* @Entity
* @Table(name="accruals")
*/
class accrual{

  /**
  * @ManyToOne(targetEntity="domain\number")
  */
  private $number;

  /**
  * @Id
  * @Column(type="integer")
  */
  private $time;

  /**
  * @Id @Column
  */
  private $service;

  /**
  * @Column
  */
  private $tarif;

  /**
  * @Column
  */
  private $ind;

  /**
  * @Column
  */
  private $odn;

  /**
  * @Column
  */
  private $sum_odn;

  /**
  * @Column
  */
  private $sum_ind;

  /**
  * @Column
  */
  private $sum_total;

  /**
  * @Column
  */
  private $recalculation;

  /**
  * @Column
  */
  private $facilities;

  /**
  * @Column
  */
  private $total;

  /**
  * @Column
  */
  private $unit;

  public function get_number(){
    return $this->number;
  }

  public function get_time(){
    return $this->time;
  }

  public function get_tarif(){
    return $this->tarif;
  }

  public function get_service(){
    return $this->service;
  }

  public function get_ind(){
    return $this->ind;
  }

  public function get_odn(){
    return $this->odn;
  }

  public function get_sum_ind(){
    return $this->sum_ind;
  }

  public function get_sum_total(){
    return $this->sum_total;
  }

  public function get_sum_odn(){
    return $this->sum_odn;
  }

  public function get_recalculation(){
    return $this->recalculation;
  }

  public function get_facilities(){
    return $this->facilities;
  }

  public function get_total(){
    return $this->total;
  }

  public function get_unit(){
    return $this->unit;
  }

  public static function new_instance(number $number, $time, $service,
                                      $unit, $tarif, $ind, $odn, $sum_ind,
                                      $sum_odn, $sum_total, $facilities,
                                      $recalculation, $total){
    $accrual = new self();
    $accrual->number = $number;
    $accrual->time = strtotime('12:00 first day of this month', $time);
    $accrual->service = $service;
    $accrual->unit = $unit;
    $accrual->tarif = $tarif;
    $accrual->ind = $ind;
    $accrual->odn = $odn;
    $accrual->sum_ind = $sum_ind;
    $accrual->sum_odn = $sum_odn;
    $accrual->sum_total = $sum_total;
    $accrual->facilities = $facilities;
    $accrual->recalculation = $recalculation;
    $accrual->total = $total;
    return $accrual;
  }
}