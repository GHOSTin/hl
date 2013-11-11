<?php
class mapper_group{

  private $company;

  private static $find = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id AND `id` = :id";

  private static $find_by_name = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id AND `name` = :name";

  private static $id = "SELECT MAX(`id`) as `max_id` FROM `groups`
    WHERE `company_id` = :company_id";

  private static $insert = 'INSERT INTO `groups` (`id`, `company_id`, `name`, `status`)
          VALUES (:id, :company_id, :name, :status)';

  private static $groups = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id  ORDER BY `name`";

  private static $update = "UPDATE `groups` SET `name` = :name
    WHERE `company_id` = :company_id AND `id` = :id";

  public function __construct(data_company $company){
    $this->company = $company;
    $this->company->verify('id');
  }

  private function create_object(array $row){
    $group = new data_group();
    $group->set_id($row['id']);
    $group->set_company_id($row['company_id']);
    $group->set_status($row['status']);
    $group->set_name($row['name']);
    return $group;
  }

  public function find($id){
    $sql = new sql();
    $sql->query(self::$find);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке группы.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0){
      $stmt->closeCursor();
      return null;
    }elseif($count === 1){
      $user = $this->create_object($stmt->fetch());
      $stmt->closeCursor();
      return $user;
    }else{
      $stmt->closeCursor();
      throw new e_model('Неожиданное количество записей.');
    }
  }

  public function find_by_name($name){
    $sql = new sql();
    $sql->query(self::$find_by_name);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $name, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке группы.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0){
      $stmt->closeCursor();
      return null;
    }elseif($count === 1){
      $user = $this->create_object($stmt->fetch());
      $stmt->closeCursor();
      return $user;
    }else{
      $stmt->closeCursor();
      throw new e_model('Неожиданное количество записей.');
    }
  }

  public function get_company_id(){
    return $this->company->get_id();
  }

  /**
  * Возвращает список групп.
  * @return array
  */
  public function get_groups(){
    $sql = new sql();
    $sql->query(self::$groups);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при выборке групп.');
    $stmt = $sql->get_stm();
    $groups = [];
    while($row = $stmt->fetch())
      $groups[] = $this->create_object($row);
    return $groups;
  }

  /**
  * Возвращает следующий для вставки идентификатор группы.
  * @return int
  */
  public function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$id);
    $sql->bind(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $sql->execute('Проблема при опредении следующего group_id.');
    if($sql->count() !== 1)
        throw new e_model('Проблема при опредении следующего group_id.');
    $id = (int) $sql->row()['max_id'] + 1;
    $sql->close();
    return $id;
  }

  public function insert(data_group $group){
    $group->verify('id', 'name', 'status', 'company_id');
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':id', (int) $group->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $group->get_company_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $group->get_name(), PDO::PARAM_STR);
    $sql->bind(':status', (string) $group->get_status(), PDO::PARAM_STR);
    $sql->execute('Проблемы при создании группы.');
    return $group;
  }

  public function update(data_group $group){
    $group->verify('id', 'name', 'status', 'company_id');
    $sql = new sql();
    $sql->query(self::$update);
    $sql->bind(':id', (int) $group->get_id(), PDO::PARAM_INT);
    $sql->bind(':company_id', (int) $group->get_company_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $group->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблема при изменении группы.');
    return $group;
  }
}