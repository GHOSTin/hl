<?php
class mapper_street{

  private $pdo;

  private static $alert = 'Проблема в мапере улицы.';

  private static $many = "SELECT DISTINCT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` ORDER BY `name`";

  private static $one = "SELECT DISTINCT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` WHERE `id` = :id";

  public function __construct(){
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_status($row['status']);
    $street->set_name($row['name']);
    return $street;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new e_model(self::$alert);;
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function get_streets(){
    $stmt = $this->pdo->prepare(self::$many);
    if(!$stmt->execute())
      throw new e_model(self::$alert);
    $streets = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch())
        $streets[] = $this->create_object($row);
    return $streets;
  }
}