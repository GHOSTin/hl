<?php
class mapper_number2meter{

  private $number;
  private $company;
  private $pdo;

  private static $alert = 'Проблема в мапере соотношения лицевого счета и счетчиков.';

  private static $get_meters = "SELECT `number2meter`.`meter_id`,
          `number2meter`.`status`,
          `meters`.`name`, `meters`.`rates`, `meters`.`capacity`,
          `meters`.`periods`, `number2meter`.`service`, `number2meter`.`serial`,
          `number2meter`.`date_release`, `number2meter`.`date_install`,
          `number2meter`.`date_checking`, `number2meter`.`period`,
          `number2meter`.`place`, `number2meter`.`comment`
      FROM `meters`, `number2meter`
      WHERE `number2meter`.`company_id` = :company_id
      AND `meters`.`company_id` = :company_id
      AND `meters`.`company_id` = :company_id
      AND `number2meter`.`number_id` = :number_id
      AND `meters`.`id` = `number2meter`.`meter_id`";

  private static $change = "UPDATE `number2meter` SET `meter_id` = :meter_id,
    `serial` = :serial, `period` = :period, `service` = :service
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :old_meter_id AND `serial` = :old_serial";

  private static $change_values = "UPDATE `meter2data`
    SET `meter_id` = :new_meter_id
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :old_meter_id AND `serial` = :old_serial";

  private static $change_values_serial = "UPDATE `meter2data`
    SET `serial` = :new_serial
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :old_meter_id AND `serial` = :old_serial";

  private static $delete = "DELETE FROM `number2meter`
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial";

  private static $delete_values = "DELETE FROM `meter2data`
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial";

  private static $insert = "INSERT INTO `number2meter` (`company_id`, `number_id`,
    `meter_id`, `serial`, `status`, `service`, `place`, `date_release`,
    `date_install`, `date_checking`, `period`, `comment`) VALUES (:company_id,
    :number_id, :meter_id, :serial, :status, :service, :place, :date_release,
    :date_install, :date_checking, :period, :comment)";

  private static $find = "SELECT `number2meter`.`meter_id`,
    `number2meter`.`status`, `number2meter`.`number_id`,
    `meters`.`name`, `meters`.`rates`, `meters`.`capacity`, `meters`.`periods`,
    `number2meter`.`service`, `number2meter`.`serial`,
    `number2meter`.`date_release`, `number2meter`.`date_install`,
    `number2meter`.`date_checking`, `number2meter`.`period`,
    `number2meter`.`place`, `number2meter`.`comment`, `number2meter`.`service`
    FROM `meters`, `number2meter`
    WHERE `number2meter`.`company_id` = :company_id
    AND `meters`.`company_id` = :company_id
    AND `number2meter`.`meter_id` = :meter_id
    AND `number2meter`.`serial` = :serial
    AND `number2meter`.`number_id` = :number_id
    AND `meters`.`id` = `number2meter`.`meter_id`";

  public static $update = "UPDATE `number2meter` SET `period` = :period,
    `status` = :status, `place` = :place, `comment` = :comment,
    `date_release` = :date_release, `date_install` = :date_install,
    `date_checking` = :date_checking WHERE `company_id` = :company_id
    AND `number_id` = :number_id AND `meter_id` = :meter_id
    AND `serial` = :serial";

  private static $update_serial = "UPDATE `number2meter`
    SET `period` = :period, `status` = :status, `serial` = :new_serial,
    `place` = :place, `comment` = :comment, `date_release` = :date_release,
    `date_install` = :date_install, `date_checking` = :date_checking
    WHERE `company_id` = :company_id AND `number_id` = :number_id
    AND `meter_id` = :meter_id AND `serial` = :serial";

  public function __construct(data_company $company, data_number $number){
    $this->company = $company;
    $this->number = $number;
    data_company::verify_id($this->company->get_id());
    data_number::verify_id($this->number->get_id());
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $meter = new data_meter();
    $meter->set_id($row['meter_id']);
    $meter->set_name($row['name']);
    $meter->set_capacity($row['capacity']);
    $meter->set_rates($row['rates']);
    if(!empty($row['periods'])){
      $periods = explode(';', $row['periods']);
      if(!empty($periods))
        foreach($periods as $period)
          $meter->add_period($period);
    }
    $n2m = new data_number2meter($meter);
    $n2m->set_serial($row['serial']);
    $n2m->set_service($row['service']);
    $n2m->set_status($row['status']);
    $n2m->set_date_release($row['date_release']);
    $n2m->set_date_install($row['date_install']);
    $n2m->set_date_checking($row['date_checking']);
    $n2m->set_period($row['period']);
    $n2m->set_place($row['place']);
    $n2m->set_comment($row['comment']);
    return $n2m;
  }

  public function delete(data_number2meter $meter){
    $this->verify($meter);
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':number_id', $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', $meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', $meter->get_serial(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $stmt = $this->pdo->prepare(self::$delete_values);
    $stmt->bindValue(':number_id', $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', $meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', $meter->get_serial(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
  }

  public function init_meters(){
    $meters = $this->get_meters();
    if(!empty($meters))
      foreach($meters as $meter)
        $this->number->add_meter($meter);
  }

  /*
  * Возвращает список счетчиков лицевого счета
  */
  private function get_meters(){
    $stmt = $this->pdo->prepare(self::$get_meters);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $meters = [];
    while($row = $stmt->fetch())
        $meters[] = $this->create_object($row);
    return $meters;
  }

  public function find($meter_id, $serial){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $meter_id, PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $serial, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $count = $stmt->rowCount();
    if($count === 0)
        return null;
    elseif($count === 1)
        return $this->create_object($stmt->fetch());
    else
        throw new e_model('Неожиданное количество возвращаемых счетчиков.');
  }

  public function insert(data_number2meter $meter){
    $this->verify($meter);
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':service', (string) $meter->get_service(), PDO::PARAM_STR);
    $stmt->bindValue(':status', (string) $meter->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':place', (string) $meter->get_place(), PDO::PARAM_STR);
    $stmt->bindValue(':date_release', (int) $meter->get_date_release(), PDO::PARAM_INT);
    $stmt->bindValue(':date_install', (int) $meter->get_date_install(), PDO::PARAM_INT);
    $stmt->bindValue(':date_checking', (int) $meter->get_date_checking(), PDO::PARAM_INT);
    $stmt->bindValue(':period', (int) $meter->get_period(), PDO::PARAM_INT);
    $stmt->bindValue(':comment', (string) $meter->get_comment(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    return $meter;
  }

  public function update(data_number2meter $meter){
    $this->verify($meter);
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':meter_id', (int) $meter->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':serial', (string) $meter->get_serial(), PDO::PARAM_STR);
    $stmt->bindValue(':period', (int) $meter->get_period(), PDO::PARAM_INT);
    $stmt->bindValue(':status', (string) $meter->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':date_release', (int) $meter->get_date_release(), PDO::PARAM_INT);
    $stmt->bindValue(':date_install', (int) $meter->get_date_install(), PDO::PARAM_INT);
    $stmt->bindValue(':date_checking', (int) $meter->get_date_checking(), PDO::PARAM_INT);
    $stmt->bindValue(':place', (string) $meter->get_place(), PDO::PARAM_STR);
    $stmt->bindValue(':comment', (string) $meter->get_comment(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    return $meter;
  }

  public function update_serial(data_number2meter $meter, $serial){
    try{
      $this->pdo->beginTransaction();
      $this->verify($meter);
      $old_serial = $meter->get_serial();
      $meter->set_serial($serial);
      data_number2meter::verify_serial($meter->get_serial());
      $stmt = $this->pdo->prepare(self::$update_serial);
      $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':meter_id', (int) $meter->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':serial', (string) $old_serial, PDO::PARAM_STR);
      $stmt->bindValue(':period', (int) $meter->get_period(), PDO::PARAM_INT);
      $stmt->bindValue(':status', (string) $meter->get_status(), PDO::PARAM_STR);
      $stmt->bindValue(':date_release', (int) $meter->get_date_release(), PDO::PARAM_INT);
      $stmt->bindValue(':date_install', (int) $meter->get_date_install(), PDO::PARAM_INT);
      $stmt->bindValue(':date_checking', (int) $meter->get_date_checking(), PDO::PARAM_INT);
      $stmt->bindValue(':place', (string) $meter->get_place(), PDO::PARAM_STR);
      $stmt->bindValue(':new_serial', (string) $meter->get_serial(), PDO::PARAM_STR);
      $stmt->bindValue(':comment', (string) $meter->get_comment(), PDO::PARAM_STR);
      if(!$stmt->execute())
        throw new e_model(self::$alert);
      $stmt = $this->pdo->prepare(self::$change_values_serial);
      $stmt->bindValue(':number_id', $this->number->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':new_serial', $meter->get_serial(), PDO::PARAM_INT);
      $stmt->bindValue(':old_meter_id', $meter->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':old_serial', $old_serial, PDO::PARAM_STR);
      if(!$stmt->execute())
        throw new e_model(self::$alert);
      $this->pdo->commit();
      return $meter;
    }catch(exception $e){
      $this->pdo->rollBack();
      throw new e_model('Проблемы при изменении лицевого счета.');
    }
  }

  public function change(data_number2meter $meter, $old_meter_id, $old_serial){
    try{
      $this->pdo->beginTransaction();
      $this->verify($meter);
      $stmt = $this->pdo->prepare(self::$change);
      $stmt->bindValue(':number_id', (int) $this->number->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':meter_id', (int) $meter->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':serial', (string) $meter->get_serial(), PDO::PARAM_STR);
      $stmt->bindValue(':period', (int) $meter->get_period(), PDO::PARAM_INT);
      $stmt->bindValue(':service', (string) $meter->get_service(), PDO::PARAM_STR);
      $stmt->bindValue(':old_meter_id', (int) $old_meter_id, PDO::PARAM_INT);
      $stmt->bindValue(':old_serial', (string) $old_serial, PDO::PARAM_STR);
      if(!$stmt->execute())
        throw new e_model(self::$alert);
      $stmt = $this->pdo->prepare(self::$change_values);
      $stmt->bindValue(':number_id', $this->number->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':old_meter_id', $old_meter_id, PDO::PARAM_INT);
      $stmt->bindValue(':new_meter_id', $meter->get_id(), PDO::PARAM_INT);
      $stmt->bindValue(':old_serial', $meter->get_serial(), PDO::PARAM_STR);
      if(!$stmt->execute())
        throw new e_model(self::$alert);
      $this->pdo->commit();
      return $meter;
    }catch(exception $e){
      $this->pdo->rollBack();
      throw new e_model('Проблема при изменении значений счетчика');
    }
  }

  public function update_meter_list(){
    try{
      $this->pdo->beginTransaction();
      $new = $this->number->get_meters();
      $old = [];
      $meters = $this->get_meters();
      if(!empty($meters))
        foreach($meters as $meter)
          $old[$meter->get_id().'_'.$meter->get_serial()] = $meter;
      $deleted = array_diff_key($old, $new);
      $inserted = array_diff_key($new, $old);
      if(!empty($inserted))
        foreach($inserted as $meter)
          $this->insert($meter);
      if(!empty($deleted))
        foreach($deleted as $meter)
          $this->delete($meter);
        $this->pdo->commit();
      return $this->number;
    }catch(exception $e){
      $this->pdo->rollBack();
      throw new e_model('Проблема при обновлении списка счетчиков.');
    }
  }

  private function verify(data_number2meter $meter){
    data_meter::verify_id($meter->get_id());
    data_meter::verify_period($meter->get_period());
    data_number2meter::verify_serial($meter->get_serial());
    data_number2meter::verify_service($meter->get_service());
    data_number2meter::verify_status($meter->get_status());
    data_number2meter::verify_place($meter->get_place());
    data_number2meter::verify_comment($meter->get_comment());
    data_number2meter::verify_date_release($meter->get_date_release());
    data_number2meter::verify_date_install($meter->get_date_install());
    data_number2meter::verify_date_checking($meter->get_date_checking());
  }
}