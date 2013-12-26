<?php
class mapper_city2street{

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
  }

  /**
  * Возвращает следующий для вставки идентификатор улицы.
  * @return int
  */
  public function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$id);
    $sql->execute('Проблема при опредении следующего street_id.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего street_id.');
    $street_id = (int) $sql->row()['max_street_id'] + 1;
    $sql->close();
    return $street_id;
  }

  public function get_street($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':street_id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке улиц');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    $factory = new factory_street();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $factory->create($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function get_street_by_name($name){
    $sql = new sql();
    $sql->query(self::$one_by_name);
    $sql->bind(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $name, PDO::PARAM_STR);
    $sql->execute('Проблема при выборке улиц');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    $factory = new factory_street();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $factory->create($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function insert(data_street $street){
    $this->verify($street);
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':street_id', $street->get_id(), PDO::PARAM_INT);
    $sql->bind(':city_id', $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':status', $street->get_status(), PDO::PARAM_STR);
    $sql->bind(':name', $street->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблемы при вставке улицы в базу данных.');
    return $street;
  }

  private function verify(data_street $street){
    data_street::verify_id($street->get_id());
    data_street::verify_name($street->get_name());
    data_street::verify_status($street->get_status());
  }
}