<?php

class mapper_accrual extends mapper{

  private static $find_all = "SELECT * FROM accruals
    WHERE company_id = :company_id AND number_id = :number_id ORDER BY time DESC";

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
}