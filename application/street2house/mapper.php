<?php
class mapper_street2house{

  private $street;

  private static $houses = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
    `department_id`, `status`, `housenumber` as `number` FROM `houses`
    WHERE `street_id` = :street_id  ORDER BY (`houses`.`housenumber` + 0)";

  private static $one = "SELECT `id`, `company_id`, `city_id`, `street_id`, 
    `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `id` = :house_id AND `street_id` = :street_id";

  private static $one_by_number = "SELECT `id`, `company_id`, `city_id`,
    `street_id`, `department_id`, `status`, `housenumber` as `number`
    FROM `houses` WHERE `housenumber` = :number AND `street_id` = :street_id";

  private static $insert = "INSERT INTO `houses` (`id`, `company_id`, `city_id`,
    `street_id`, `department_id`, `status`, `housenumber`)
    VALUES (:house_id, 1, 1, :street_id, 1, :status, :number)";

  private static $id = "SELECT MAX(`id`) as `max_house_id` FROM `houses`";

  public function __construct(data_street $street){
    $this->street = $street;
    data_street::verify_id($this->street->get_id());
  }

  public function create_object(array $row){
    $house = new data_house();
    $house->set_id($row['id']);
    $house->set_company_id($row['company_id']);
    $house->set_street_id($row['street_id']);
    $house->set_department_id($row['department_id']);
    $house->set_status($row['status']);
    $house->set_number($row['number']);
    $city = new data_city();
    $city->set_id($row['city_id']);
    $house->set_city($city);
    return $house;
  }

  private function get_houses(){
    $sql = new sql();
    $sql->query(self::$houses);
    $sql->bind(':street_id', $this->street->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке домов.');
    $stmt = $sql->get_stm();
    $houses = [];
    if($stmt->rowCount() > 0)
      while($row = $stmt->fetch()){
        $houses[] = $this->create_object($row);
      }
    return $houses;
  }

  public function init_houses(){
    $houses = $this->get_houses();
    if(!empty($houses))
      foreach($houses as $house)
        $this->street->add_house($house);
  }

  public function insert(data_house $house){
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':house_id', (int) $house->get_id(), PDO::PARAM_INT);
    $sql->bind(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    $sql->bind(':status', (string) $house->get_status(), PDO::PARAM_STR);
    $sql->bind(':number', (string) $house->get_number(), PDO::PARAM_STR);
    $sql->execute('Проблемы при создании нового дома.');
    return $house;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':house_id', (int) $id, PDO::PARAM_INT);
    $sql->bind(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке домов из базы данных.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество домов');
  }

  public function find_by_number($number){
    $sql = new sql();
    $sql->query(self::$one_by_number);
    $sql->bind(':number', (string) $number, PDO::PARAM_STR);
    $sql->bind(':street_id', (int) $this->street->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке домов из базы данных.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество домов');
  }

  /**
  * Возвращает следующий для вставки идентификатор дома.
  * @return int
  */
  public static function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$id);
    $sql->execute('Проблема при опредении следующего house_id.');
    if($sql->count() !== 1)
      throw new e_model('Проблема при опредении следующего house_id.');
    $house_id = (int) $sql->row()['max_house_id'] + 1;
    $sql->close();
    return $house_id;
  }
}