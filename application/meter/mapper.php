<?php
class mapper_meter{

  private $company;

  private static $sql_find = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
    FROM `meters` WHERE `company_id` = :company_id AND `id` = :id";

  private static $sql_get_met_by_service = "SELECT `id`, `company_id`, `name`,
    `capacity`, `rates`, `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND FIND_IN_SET(:service, `service`) > 0
    ORDER BY `name`";

  private static $sql_get_meters = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
    FROM `meters` WHERE `company_id` = :company_id ORDER BY `name`";

  public function __construct(data_company $company){
    $this->company = $company;
    $this->company->verify('id');
  }

  public function create(data_meter $meter){
    $meter->set_id($this->get_insert_id());
    $meter->set_company_id($this->company->get_id());
    $meter->verify('id', 'company_id', 'name', 'rates', 'capacity');
    $sql = new sql();
    $sql->query("INSERT INTO `meters` (`id`, `company_id`, `name`, `rates`, `capacity`,`periods`)
                VALUES (:id, :company_id, :name, :rates, :capacity, :periods)");
    $sql->bind(':id', (int) $meter->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $meter->get_company_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $meter->get_name(), PDO::PARAM_STR);
    $sql->bind(':rates', (int) $meter->get_rates(), PDO::PARAM_INT);
    $sql->bind(':capacity', (int) $meter->get_capacity(), PDO::PARAM_INT);
    $sql->bind(':periods', implode(';', $meter->get_periods()), PDO::PARAM_STR);
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
    $meter = new data_meter();
    $meter->set_id($id);
    $meter->verify('id');
    $sql = new sql();
    $sql->query(self::$sql_find);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
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
    $meter = new data_meter();
    $meter->set_name($name);
    $meter->verify('name');
    $sql = new sql();
    $sql->query("SELECT `id`, `company_id`, `name`, `capacity`, `rates`, `service`, `periods`
                FROM `meters` WHERE `company_id` = :company_id AND `name` = :name");
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', $meter->get_name(), PDO::PARAM_STR);
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
      $sql->query("SELECT MAX(`id`) as `max_id` FROM `meters`
                  WHERE `company_id` = :company_id");
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
    $sql->query(self::$sql_get_met_by_service);
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
    $meter->verify('id', 'name', 'capacity', 'rates', 'periods');
    $sql = new sql();
    $sql->query('UPDATE `meters` SET `name` = :name, `capacity` = :capacity,
                `rates` = :rates, `periods` = :periods, `service` = :services
                WHERE `company_id` = :company_id AND `id` = :id');
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':id', $meter->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', $meter->get_name(), PDO::PARAM_STR);
    $sql->bind(':capacity', $meter->get_capacity(), PDO::PARAM_INT);
    $sql->bind(':rates', $meter->get_rates(), PDO::PARAM_INT);
    $sql->bind(':periods', implode(';', $meter->get_periods()), PDO::PARAM_STR);
    $sql->bind(':services', implode(',', $meter->get_services()), PDO::PARAM_STR);
    $sql->execute('Проблема при обновлении счетчика.');
    return $meter;
  }

  public function get_meters(){
    $sql = new sql();
    $sql->query(self::$sql_get_meters);
    $sql->bind(':company_id', $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке счетчиков.');
    $stmt = $sql->get_stm();
    $meters = [];
    while($row = $stmt->fetch())
      $meters[] = $this->create_object($row);
    return $meters;    
  }
}