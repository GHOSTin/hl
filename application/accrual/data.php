<?php

class data_accrual{

  private $company;
  private $number;
  private $time;
  private $service;
  private $tarif;
  private $ind;
  private $odn;
  private $sum_odn;
  private $sum_ind;
  private $sum_total;
  private $recalculation;
  private $facilities;
  private $total;
  private $unit;

  public function get_company(){
    return $this->company;
  }

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

  public function set_company(data_company $company){
    $this->company = $company;
  }

  public function set_number(data_number $number){
    $this->number = $number;
  }

  public function set_time($time){
    $this->time = strtotime('first day of this month + 12 hours', $time);
  }

  public function set_service($service){
    $this->service = $service;
  }

  public function set_tarif($tarif){
    $this->tarif = $tarif;
  }

  public function set_ind($ind){
    $this->ind = $ind;
  }

  public function set_odn($odn){
    $this->odn = $odn;
  }

  public function set_sum_ind($sum_ind){
    $this->sum_ind = $sum_ind;
  }

  public function set_sum_odn($sum_odn){
    $this->sum_odn = $sum_odn;
  }

  public function set_sum_total($sum_total){
    $this->sum_total = $sum_total;
  }

  public function set_recalculation($recalculation){
    $this->recalculation = $recalculation;
  }

  public function set_facilities($facilities){
    $this->facilities = $facilities;
  }

  public function set_total($total){
    $this->total = $total;
  }

  public function set_unit($unit){
    $this->unit = $unit;
  }
}