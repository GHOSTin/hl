<?php

class mapper_accrual extends mapper{

  private static $find_all = "SELECT * FROM accruals
    WHERE company_id = :company_id AND number_id = :number_id ORDER BY time DESC";

  private static $insert = 'INSERT INTO accruals
      SET company_id = :company_id, number_id = :number_id, time = :time,
      service = :service, unit = :unit, tarif = :tarif, ind = :ind, odn = :odn,
      sum_ind = :sum_ind, sum_odn = :sum_odn, sum_total = :sum_total,
      recalculation = :recalculation, facilities = :facilities,
      total = :total';

  public function find_all(data_company $company, data_number $number){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', $number->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $accruals = [];
    while($row = $stmt->fetch()){
      $row['number'] = $number;
      $row['company'] = $company;
      $accruals[] = di::get('factory_accrual')->build($row);
    }
    return $accruals;
  }

  public function insert(data_accrual $accrual){
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':company_id', $accrual->get_company()->get_id(), PDO::PARAM_STR);
    $stmt->bindValue(':number_id', $accrual->get_number()->get_id(), PDO::PARAM_STR);
    $stmt->bindValue(':time', $accrual->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':service', $accrual->get_service(), PDO::PARAM_STR);
    $stmt->bindValue(':unit', $accrual->get_unit(), PDO::PARAM_STR);
    $stmt->bindValue(':tarif', $accrual->get_tarif(), PDO::PARAM_STR);
    $stmt->bindValue(':ind', $accrual->get_ind(), PDO::PARAM_STR);
    $stmt->bindValue(':odn', $accrual->get_odn(), PDO::PARAM_STR);
    $stmt->bindValue(':sum_ind', $accrual->get_sum_ind(), PDO::PARAM_STR);
    $stmt->bindValue(':sum_odn', $accrual->get_sum_odn(), PDO::PARAM_STR);
    $stmt->bindValue(':sum_total', $accrual->get_sum_total(), PDO::PARAM_STR);
    $stmt->bindValue(':facilities', $accrual->get_facilities(), PDO::PARAM_STR);
    $stmt->bindValue(':recalculation', $accrual->get_recalculation(), PDO::PARAM_STR);
    $stmt->bindValue(':total', $accrual->get_total(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
  }
}