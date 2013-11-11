<?php
class mapper_meter{

  private $company;

  private static $one = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`,
   `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND `id` = :id";

  private static $one_by_name = "SELECT `id`, `company_id`, `name`, `capacity`,
    `rates`, `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND `name` = :name"

  private static $insert = "INSERT INTO `meters` (`id`, `company_id`, `name`,
    `rates`, `capacity`,`periods`) VALUES (:id, :company_id, :name, :rates,
    :capacity, :periods)";

  private static $insert_id = "SELECT MAX(`id`) as `max_id` FROM `meters`
    WHERE `company_id` = :company_id";

  private static $all_by_service = "SELECT `id`, `company_id`, `name`,
    `capacity`, `rates`, `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND FIND_IN_SET(:service, `service`) > 0
    ORDER BY `name`";

  private static $all = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`,
    `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id ORDER BY `name`";

  private static $update = 'UPDATE `meters` SET `name` = :name,
    `capacity` = :capacity, `rates` = :rates, `periods` = :periods,
    `service` = :services WHERE `company_id` = :company_id AND `id` = :id'

  public function __construct(data_company $company){
    $this->company = $company;
    data_company::verify_id($this->company->get_id());
  }

  public function create(data_meter $meter){
    $meter->set_id($this->get_insert_id());
    $meter->set_company_id($this->company->get_id());
    $meter->verify('id', 'company_id', 'name', 'rates', 'capacity');
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':id', (int) $meter->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $meter->get_company_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $meter->get_name(), PDO::PARAM_STR);
    $sql->bind(':rates', (int) $meter->get_rates(), PDO::PARAM_INT);
    $sql->bind(':capacity', (int) $meter->get_capacity(), PDO::PARAM_INT);
    $sql->bind(':periods', (string) implode(';', $meter->get_periods()), PDO::PARAM_STR);
    $sql->execute('Проблемы при создании счетчика.');
    return $meter;
  }

  public function create_object(array $row){
    $meter = new data_meter();
    $meter->set_id($row['id']);
    $meter->set_name($row['name']);
    $meter->set_capacity($row['capacity']);
    $meter->set_rates($row['rates']);
    if(!empty($row['periods'])){
      $periods = explode(';', $row['periods']);
      if(!empty($periods))
        foreach($periods as $period)
          $meter->add_period($period);
    }
    if(!empty($row['service'])){
      $services = explode(',', $row['service']);
      if(!empty($services))
        foreach($services as $service)
          $meter->add_service($service);
    }
    return $meter;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при запросе счетчика.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество возвращаемых счетчиков.');
  }

  public function find_by_name($name){
    $sql = new sql();
    $sql->query(self::$one_by_name);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $name, PDO::PARAM_STR);
    $sql->execute('Проблема при запросе счетчика.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество возвращаемых счетчиков.');
  }

  /**
  * Возвращает следующий для вставки идентификатор счетчика.
  * @return int
  */
  private function get_insert_id(){
      $this->company->verify('id');
      $sql = new sql();
      $sql->query(self::$insert_id);
      $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
      $sql->execute('Проблема при опредении следующего meter_id.');
      if($sql->count() !== 1)
          throw new e_model('Проблема при опредении следующего meter_id.');
      $id = (int) $sql->row()['max_id'] + 1;
      $sql->close();
      return $id;
  }

  /**
  * Возвращает список счетчиков
  * @return array data_meter
  */
  public function get_meters_by_service($service){
    $sql = new sql();
    $sql->query(self::$all_by_service);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':service', (string) $service, PDO::PARAM_STR);
    $sql->execute('Проблема при выборке счетчиков.');
    $stmt = $sql->get_stm();
    $meters = [];
    while($row = $stmt->fetch())
      $meters[] = $this->create_object($row);
    return $meters;
  }

  public function update(data_meter $meter){
    $this->verify($meter);
    $sql = new sql();
    $sql->query(self::$update);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':id', (int) $meter->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (int) $meter->get_name(), PDO::PARAM_STR);
    $sql->bind(':capacity', (int) $meter->get_capacity(), PDO::PARAM_INT);
    $sql->bind(':rates', (int) $meter->get_rates(), PDO::PARAM_INT);
    $sql->bind(':periods', (string) implode(';', $meter->get_periods()), PDO::PARAM_STR);
    $sql->bind(':services', (string) implode(',', $meter->get_services()), PDO::PARAM_STR);
    $sql->execute('Проблема при обновлении счетчика.');
    return $meter;
  }

  private function verify(data_meter $meter){
    data_meter::verify_id($meter->get_id());
    data_meter::verify_name($meter->get_name());
    data_meter::verify_capacity($meter->get_capacity());
    data_meter::verify_rates($meter->get_rates());
    if(!empty($meter->get_periods()))
      foreach($meter->get_periods() as $period)
        data_meter::verify_period($period);
    if(!empty($meter->get_services()))
      foreach($meter->get_services() as $service)
        data_meter::verify_service($service);
  }

  public function get_meters(){
    $sql = new sql();
    $sql->query(self::$all);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке счетчиков.');
    $stmt = $sql->get_stm();
    $meters = [];
    while($row = $stmt->fetch())
      $meters[] = $this->create_object($row);
    return $meters;    
  }
}