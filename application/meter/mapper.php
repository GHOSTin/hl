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

  public function __construct(PDO $pdo, data_company $company){
    $this->pdo = $pdo;
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
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model('Проблема при запросе счетчика.');
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
    $stmt = $this->pdo->prepare(self::$all_by_service);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':service', (string) $service, PDO::PARAM_STR);
    if($stmt->execute())
      throw new e_model('Проблема при выборке счетчиков.');
    $meters = [];
    while($row = $stmt->fetch())
      $meters[] = $this->create_object($row);
    return $meters;
  }

  /**
  * Возвращает список счетчиков.
  */
  public function get_meters(){
    $stmt = $this->pdo->prepare(self::$all);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model('Проблема при выборке счетчиков.');
    $meters = [];
    while($row = $stmt->fetch())
      $meters[] = $this->create_object($row);
    return $meters;    
  }
}