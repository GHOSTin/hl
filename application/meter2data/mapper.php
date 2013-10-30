<?php
class mapper_meter2data{

  private $company;
  private $number;
  private $meter;

  private static $sql_get_value = "SELECT `time`, `value`, `comment`, `way`, `timestamp`
    FROM `meter2data` WHERE `meter2data`.`company_id` = :company_id
    AND `meter2data`.`number_id` = :number_id AND `meter2data`.`meter_id` = :meter_id
    AND `meter2data`.`serial` = :serial AND `meter2data`.`time` = :time";

  private static $values = "SELECT `time`, `value`, `comment`, `way`, `timestamp`
    FROM `meter2data` WHERE `meter2data`.`company_id` = :company_id
    AND `meter2data`.`number_id` = :number_id AND `meter2data`.`meter_id` = :meter_id
    AND `meter2data`.`serial` = :serial AND `meter2data`.`time` >= :time_begin
    AND `meter2data`.`time` <= :time_end";

  public function __construct(data_company $company, data_number $number,
   data_number2meter $meter){
    $this->company = $company;
    $this->number = $number;
    $this->meter = $meter;
  }

  public function create_object(array $row){
    $value = new data_meter2data();
    $value->set_way($row['way']);
    $value->set_comment($row['comment']);
    $value->set_value(explode(';', $row['value']));
    $value->set_time($row['time']);
    $value->set_timestamp($row['timestamp']);
    return $value;
  }

  public function insert(data_meter2data $value){
      $value->verify('time', 'timestamp', 'value', 'way', 'comment');
      $sql = new sql();
      $sql->query("INSERT INTO `meter2data` (`company_id`, `number_id`, `meter_id`,
              `serial`, `time`, `timestamp`, `value`, `way`, `comment`)
              VALUES (:company_id, :number_id, :meter_id, :serial, :time, :timestamp,
              :value, :way, :comment)");
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->bind(':number_id', $this->n2m->get_number()->get_id(), PDO::PARAM_INT);
      $sql->bind(':meter_id', $this->n2m->get_meter()->get_id(), PDO::PARAM_INT);
      $sql->bind(':serial', $this->n2m->get_serial(), PDO::PARAM_STR);
      $sql->bind(':time', $value->get_time(), PDO::PARAM_INT);
      $sql->bind(':timestamp', $value->get_timestamp(), PDO::PARAM_INT);
      $sql->bind(':value', implode(';', $value->get_value()), PDO::PARAM_STR);
      $sql->bind(':way', $value->get_way(), PDO::PARAM_STR);
      $sql->bind(':comment', $value->get_comment(), PDO::PARAM_STR);
      $sql->execute('Проблемы при создании показания.');
      return $value;
  }

  public function find($time){
      $time = getdate($time);
      $time = mktime(12, 0, 0, $time['mon'], 1, $time['year']);
      $sql = new sql();
      $sql->query(self::$sql_get_value);
      $sql->bind(':number_id', $this->n2m->get_number()->get_id(), PDO::PARAM_INT);
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->bind(':meter_id', $this->n2m->get_meter()->get_id(), PDO::PARAM_INT);
      $sql->bind(':serial', $this->n2m->get_serial(), PDO::PARAM_STR);
      $sql->bind(':time', $time, PDO::PARAM_INT);
      $sql->execute('Проблема при запросе показания счетчика.');
      $stmt = $sql->get_stm();
      $count = $stmt->rowCount();
      if($count === 0)
          return null;
      elseif($count === 1)
          return $this->create_object($stmt->fetch());
      else
          throw new e_model('Неожиданное количество возвращаемых строк.');
  }

  public function get_values($begin, $end){
    $sql = new sql();
    $sql->query(self::$values);
    $sql->bind(':meter_id', (int) $this->meter->get_id(), PDO::PARAM_INT);
    $sql->bind(':serial', (string) $this->meter->get_serial(), PDO::PARAM_STR);
    $sql->bind(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':time_begin', (int) $begin, PDO::PARAM_INT);
    $sql->bind(':time_end', (int) $end, PDO::PARAM_INT);
    $sql->execute( 'Проблема при при выборки данных счетчика.');
    $stmt = $sql->get_stm();
    $values = [];
    while($row = $stmt->fetch())
      $values[] = $this->create_object($row);
    $stmt->closeCursor();
    return $values;
  }

  /*
  * Возвращает предидущее показание
  */
  public function last($time){
      $time = getdate($time);
      $time = mktime(12, 0, 0, $time['mon'], $time['mday'], $time['year']);
      $sql = new sql();
      $sql->query("SELECT`company_id`, `number_id`, `meter_id`, `serial`,
                  `time`, `timestamp`, `value`, `way`, `comment`
                  FROM `meter2data`
                  WHERE `company_id` = :company_id AND `number_id` = :number_id
                  AND `meter_id` = :meter_id AND `serial` = :serial
                  AND `time` = (SELECT MAX(`time`) FROM `meter2data` WHERE 
                  `company_id` = :company_id AND `number_id` = :number_id
                  AND `meter_id` = :meter_id AND `serial` = :serial AND `time` < :time)");
      $sql->bind(':number_id', $this->number_id, PDO::PARAM_INT);
      $sql->bind(':company_id', $this->company->id, PDO::PARAM_INT);
      $sql->bind(':meter_id', $this->meter_id, PDO::PARAM_INT);
      $sql->bind(':serial', $this->serial, PDO::PARAM_STR);
      $sql->bind(':time', $time, PDO::PARAM_INT);
      $data = $sql->map(new data_meter2data(), 'Проблема при запросе показания счетчика.');
      $count = count($data);
      if($count === 0)
          return null;
      if($count !== 1)
          throw new e_model('Неожиданное количество возвращаемых строк.');
      return  $data[0];
  }

  public function update(data_meter2data $data){
      $data->verify('time','timestamp', 'value', 'way', 'comment');
      $sql = new sql();
      $sql->query("UPDATE`meter2data` SET `timestamp` = :timestamp, 
              `value` = :value, `way` = :way, `comment` = :comment
              WHERE `company_id` = :company_id AND `number_id` = :number_id
              AND `meter_id` = :meter_id AND `serial` = :serial AND `time` = :time");
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->bind(':number_id', $this->n2m->get_number()->get_id(), PDO::PARAM_INT);
      $sql->bind(':meter_id', $this->n2m->get_meter()->get_id(), PDO::PARAM_INT);
      $sql->bind(':serial', $this->n2m->get_serial(), PDO::PARAM_STR);
      $sql->bind(':time', $data->get_time(), PDO::PARAM_INT);
      $sql->bind(':timestamp', $data->get_timestamp(), PDO::PARAM_INT);
      $sql->bind(':value', implode(';', $data->get_value()), PDO::PARAM_STR);
      $sql->bind(':way', $data->get_way(), PDO::PARAM_STR);
      $sql->bind(':comment', $data->get_comment(), PDO::PARAM_STR);
      $sql->execute('Проблемы при обновлении показания.');
      return $data;
  }
}