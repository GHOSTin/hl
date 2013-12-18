<?php
class mapper_meter{

  private $company;

  private static $one = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`,
   `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND `id` = :id";

  private static $all_by_service = "SELECT `id`, `company_id`, `name`,
    `capacity`, `rates`, `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id AND FIND_IN_SET(:service, `service`) > 0
    ORDER BY `name`";

  private static $all = "SELECT `id`, `company_id`, `name`, `capacity`, `rates`,
    `service`, `periods` FROM `meters`
    WHERE `company_id` = :company_id ORDER BY `name`";

  public function __construct(data_company $company){
    $this->company = $company;
    data_company::verify_id($this->company->get_id());
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

  /**
  * Возвращает список счетчиков.
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

  /**
  * Возвращает список счетчиков.
  */
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