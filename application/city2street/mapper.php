<?php
class mapper_city2street{

  private $city;
  private $pdo;

  private static $one = "SELECT `id`, `company_id`, `city_id`, `status`, `name`
    FROM `streets` WHERE `city_id` = :city_id AND `id` = :street_id";

  private static $one_by_name = "SELECT `id`, `company_id`, `city_id`, `status`,
   `name` FROM `streets` WHERE `city_id` = :city_id AND `name` = :name";

  private static $id = "SELECT MAX(`id`) as `max_street_id` FROM `streets`";

  private static $insert = "INSERT INTO `streets` (`id`, `company_id`,
    `city_id`, `status`, `name`) VALUES
    (:street_id, 2, :city_id, :status, :name)";

  public function __construct(data_city $city){
    $this->city = $city;
    data_city::verify_id($this->city->get_id());
    $this->pdo = di::get('pdo');
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $street_id = (int) $stmt->fetch()['max_street_id'] + 1;
    return $street_id;
  }

  public function get_street($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':street_id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    $factory = new factory_street();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $factory->create($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function get_street_by_name($name){
    $stmt = $this->pdo->prepare(self::$one_by_name);
    $stmt->bindValue(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $name, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    $factory = new factory_street();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $factory->create($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function insert(data_street $street){
    $this->verify($street);
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':street_id', $street->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':city_id', $this->city->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':status', $street->get_status(), PDO::PARAM_STR);
    $stmt->bindValue(':name', $street->get_name(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $street;
  }

  private function verify(data_street $street){
    data_street::verify_id($street->get_id());
    data_street::verify_name($street->get_name());
    data_street::verify_status($street->get_status());
  }
}