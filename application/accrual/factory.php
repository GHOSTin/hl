<?php

class factory_accrual{

  public function build(array $row){
    $accrual = new data_accrual();
    $accrual->set_company($row['company']);
    $accrual->set_number($row['number']);
    $accrual->set_time($row['time']);
    $accrual->set_service($row['service']);
    $accrual->set_tarif($row['tarif']);
    $accrual->set_ind($row['ind']);
    $accrual->set_odn($row['odn']);
    $accrual->set_sum_ind($row['sum_ind']);
    $accrual->set_sum_odn($row['sum_odn']);
    $accrual->set_recalculation($row['recalculation']);
    $accrual->set_facilities($row['facilities']);
    $accrual->set_total($row['total']);
    return $accrual;
  }
}