<?php

class mapper_export extends mapper {

  private static $find_all = "SELECT `streets`.`name`,
  `houses`.`housenumber`, `flats`.`flatnumber`, `numbers`.`number`, `numbers`.`fio`
  FROM `numbers`, `flats`, `houses`, `streets`
  WHERE `numbers`.`flat_id` = `flats`.`id` AND `numbers`.`house_id` = `houses`.`id`
  AND `houses`.`street_id` = `streets`.`id` AND `numbers`.`company_id` = :company_id";

  public function find_all(data_company $company){
    $stmt = $this->pdo->prepare(self::$find_all);
    $stmt->bindValue(':company_id', $company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

} 