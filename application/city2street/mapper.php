<?php
class mapper_city2street{

  private static $sql_one = "SELECT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` WHERE `city_id` = :city_id
    AND `id` = :street_id";

  private static $sql_one_by_name = "SELECT `id`, `company_id`, 
    `city_id`, `status`, `name` FROM `streets` WHERE `city_id` = :city_id
    AND `name` = :name";

  public function __construct(data_city $city){
    $this->city = $city;
    $this->city->verify('id');
  }

  public function create_object(array $row){
    $street = new data_street();
    $street->set_id($row['id']);
    $street->set_company_id($row['company_id']);
    $street->set_city_id($row['city_id']);
    $street->set_status($row['status']);
    $street->set_name($row['name']);
    return $street;
  }

  /**
  * Возвращает следующий для вставки идентификатор улицы.
  * @return int
  */
  public function get_insert_id(){
    $sql = new sql();
    $sql->query("SELECT MAX(`id`) as `max_street_id` FROM `streets`");
    $sql->execute('Проблема при опредении следующего street_id.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего street_id.');
    $street_id = (int) $sql->row()['max_street_id'] + 1;
    $sql->close();
    return $street_id;
  }

  public function get_street($id){
    $sql = new sql();
    $sql->query(self::$sql_one);
    $sql->bind(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':street_id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке улиц');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function get_street_by_name($name){
    $sql = new sql();
    $sql->query(self::$sql_one_by_name);
    $sql->bind(':city_id', (int) $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $name, PDO::PARAM_STR);
    $sql->execute('Проблема при выборке улиц');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное чисто записей');
  }

  public function insert(data_street $street){
    $street->verify('id', 'name');
    $sql = new sql();
    $sql->query("INSERT INTO `streets` (`id`, `company_id`, `city_id`, `status`, `name`)
          VALUES (:street_id, 2, :city_id, :status, :name)");
    $sql->bind(':street_id', $street->get_id(), PDO::PARAM_INT);
    $sql->bind(':city_id', $this->city->get_id(), PDO::PARAM_INT);
    $sql->bind(':status', $street->get_status(), PDO::PARAM_STR);
    $sql->bind(':name', $street->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблемы при вставке улицы в базу данных.');
    return $street;
  }
}