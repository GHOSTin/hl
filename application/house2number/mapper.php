<?php
class mapper_house2number{
  
  private $company;
  private $house;

  private static $sql_get_numbers = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
    `numbers`.`city_id`, `numbers`.`house_id`, `numbers`.`flat_id`, `numbers`.`number`,
    `numbers`.`type`, `numbers`.`status`, `numbers`.`fio`, `numbers`.`telephone`,
    `numbers`.`cellphone`, `numbers`.`password`, `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`, `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`, `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets` WHERE `numbers`.`house_id` = :house_id
    AND `numbers`.`company_id` = :company_id AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id` AND `houses`.`street_id` = `streets`.`id`
    ORDER BY (`flats`.`flatnumber` + 0)";
  private static $sql_get_number = "SELECT `numbers`.`id`, `numbers`.`company_id`, 
    `numbers`.`city_id`, `numbers`.`house_id`, `numbers`.`flat_id`, `numbers`.`number`,
    `numbers`.`type`, `numbers`.`status`, `numbers`.`fio`, `numbers`.`telephone`,
    `numbers`.`cellphone`, `numbers`.`password`, `numbers`.`contact-fio` as `contact_fio`,
    `numbers`.`contact-telephone` as `contact_telephone`, `numbers`.`contact-cellphone` as `contact_cellphone`,
    `flats`.`flatnumber` as `flat_number`, `houses`.`housenumber` as `house_number`,
    `streets`.`name` as `street_name`
    FROM `numbers`, `flats`, `houses`, `streets` WHERE `numbers`.`house_id` = :house_id
    AND `numbers`.`company_id` = :company_id AND `numbers`.`flat_id` = `flats`.`id`
    AND `numbers`.`house_id` = `houses`.`id` AND `houses`.`street_id` = `streets`.`id`
    AND `numbers`.`number` = :number";

  public function __construct(data_company $company, data_house $house){
    $this->company = $company;
    $this->house = $house;
    $this->company->verify('id');
    $this->house->verify('id');
  }

  public function create_object(array $row){
    $number = new data_number();
    $number->set_cellphone($row['cellphone']);
    $number->set_fio($row['fio']);
    $number->set_id($row['id']);
    $number->set_number($row['number']);
    $number->set_type($row['type']);
    $number->set_status($row['status']);
    $number->set_telephone($row['telephone']);
    $flat = new data_flat();
    $flat->set_id($row['flat_id']);
    $flat->set_number($row['flat_number']);
    $number->set_flat($flat);
    return $number;
  }

  public function get_number_by_number($number){
    $sql = new sql();
    $sql->query(self::$sql_get_number);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', $this->house->get_id(), PDO::PARAM_INT);
    $sql->bind(':number', (string) $number, PDO::PARAM_STR);
    $sql->execute('Проблемы при выборке номеров.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное колличество записей');
  }
  
  private function get_numbers(){
    $sql = new sql();
    $sql->query(self::$sql_get_numbers);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':house_id', $this->house->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблемы при выборке номеров.');
    $stmt = $sql->get_stm();
    $numbers = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $numbers[] = $this->create_object($row);
    return $numbers;
  }

  public function init_numbers(){
    $numbers = $this->get_numbers();
    if(!empty($numbers))
      foreach($numbers as $number)
        $this->house->add_number($number);
  }
}