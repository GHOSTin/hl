<?php
class mapper_meter2data{

  private $pdo;
  private $company;
  private $number;
  private $meter;

  private static $all = "SELECT `time`, `value`, `comment`, `way`, `timestamp`
    FROM `meter2data` WHERE `meter2data`.`company_id` = :company_id
    AND `meter2data`.`number_id` = :number_id AND `meter2data`.`meter_id` = :meter_id
    AND `meter2data`.`serial` = :serial AND `meter2data`.`time` = :time";

  private static $values = "SELECT `time`, `value`, `comment`, `way`, `timestamp`
    FROM `meter2data` WHERE `meter2data`.`company_id` = :company_id
    AND `meter2data`.`number_id` = :number_id AND `meter2data`.`meter_id` = :meter_id
    AND `meter2data`.`serial` = :serial AND `meter2data`.`time` >= :time_begin
    AND `meter2data`.`time` <= :time_end";

  private static $update = "UPDATE`meter2data` SET `timestamp` = :timestamp, 
    `value` = :value, `way` = :way, `comment` = :comment
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial AND `time` = :time";

  private static $insert = "INSERT INTO `meter2data` (`company_id`, `number_id`,
    `meter_id`, `serial`, `time`, `timestamp`, `value`, `way`, `comment`)
    VALUES (:company_id, :number_id, :meter_id, :serial, :time, :timestamp,
    :value, :way, :comment)";

  private static $last = "SELECT`company_id`, `number_id`, `meter_id`, `serial`,
    `time`, `timestamp`, `value`, `way`, `comment` FROM `meter2data`
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial
    AND `time` = (SELECT MAX(`time`) FROM `meter2data` WHERE 
    `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial AND `time` < :time)";

  public function __construct(data_company $company, data_number $number,
    data_number2meter $meter){
    $this->company = $company;
    $this->number = $number;
    $this->meter = $meter;
    data_company::verify_id($this->company->get_id());
    data_number::verify_id($this->number->get_id());
    data_meter::verify_id($this->meter->get_id());
    data_meter::verify_rates($this->meter->get_rates());
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $value = new data_meter2data();
    $value->set_way($row['way']);
    $value->set_comment($row['comment']);
    if(!empty($row['value'])){
      $values = explode(';', $row['value']);
      $limit = $this->meter->get_rates();
      for($i = 0; $i < $limit; $i++)
        $value->set_value($i, $values[$i]);
    }
    $value->set_time($row['time']);
    $value->set_timestamp($row['timestamp']);
    return $value;
  }

  public function insert(data_meter2data $value){
    $this->verify($value);
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $this->meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $this->meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':time', (int) $value->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':timestamp', (int) $value->get_timestamp(), PDO::PARAM_INT);
    $stmt->bindValue(':value', (string) implode(';', $value->get_values()), PDO::PARAM_STR);
    $stmt->bindValue(':way', (string) $value->get_way(), PDO::PARAM_STR);
    $stmt->bindValue(':comment', (string) $value->get_comment(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    return $value;
  }

  public function find($time){
    $time = getdate($time);
    $time = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
    $stmt = $this->pdo->prepare(self::$all);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $this->meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (int) $this->meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':time', (int) $time, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $count = $stmt->rowCount();
    if($count === 0)
        return null;
    elseif($count === 1)
        return $this->create_object($stmt->fetch());
    else
        throw new e_model(self::$alert);
  }

  public function get_values($begin, $end){
    $stmt = $this->pdo->prepare(self::$values);
    $stmt->bindValue(':meter_id', (int) $this->meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $this->meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':time_begin', (int) $begin, PDO::PARAM_INT);
    $stmt->bindValue(':time_end', (int) $end, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $values = [];
    while($row = $stmt->fetch())
      $values[] = $this->create_object($row);
    $stmt->closeCursor();
    return $values;
  }

  public function update(data_meter2data $data){
    $this->verify($data);
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $this->meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $this->meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':time', (int) $data->get_time(), PDO::PARAM_INT);
    $stmt->bindValue(':timestamp', (int) $data->get_timestamp(), PDO::PARAM_INT);
    $stmt->bindValue(':value', (string) implode(';', $data->get_values()), PDO::PARAM_STR);
    $stmt->bindValue(':way', (string) $data->get_way(), PDO::PARAM_STR);
    $stmt->bindValue(':comment', (string) $data->get_comment(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    return $data;
  }

  private function verify(data_meter2data $data){
    verify_environment::verify_time($data->get_time());
    data_meter2data::verify_comment($data->get_comment());
    data_meter2data::verify_way($data->get_way());
  }
}