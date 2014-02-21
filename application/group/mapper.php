<?php
class mapper_group extends mapper{

  private $company;

  private static $find = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id AND `id` = :id";

  private static $find_by_name = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id AND `name` = :name";

  private static $id = "SELECT MAX(`id`) as `max_id` FROM `groups`
    WHERE `company_id` = :company_id";

  private static $insert = 'INSERT INTO `groups` (`id`, `company_id`, `name`,
    `status`) VALUES (:id, :company_id, :name, :status)';

  private static $groups = "SELECT `id`, `company_id`, `status`, `name`
    FROM `groups` WHERE `company_id` = :company_id  ORDER BY `name`";

  private static $update = "UPDATE `groups` SET `name` = :name
    WHERE `company_id` = :company_id AND `id` = :id";

  public function __construct(PDO $pdo, data_company $company){
    parent::__construct($pdo);
    $this->company = $company;
  }

  private function create_object(array $row){
    $group = new data_group();
    $group->set_id($row['id']);
    $group->set_status($row['status']);
    $group->set_name($row['name']);
    return $group;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$find);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0){
      $stmt->closeCursor();
      return null;
    }elseif($count === 1){
      $user = $this->create_object($stmt->fetch());
      $stmt->closeCursor();
      return $user;
    }else{
      throw new RuntimeException();
    }
  }

  public function find_by_name($name){
    $stmt = $this->pdo->prepare(self::$find_by_name);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $name, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0){
      $stmt->closeCursor();
      return null;
    }elseif($count === 1){
      $user = $this->create_object($stmt->fetch());
      $stmt->closeCursor();
      return $user;
    }else{
      throw new RuntimeException();
    }
  }

  public function get_company_id(){
    return $this->company->get_id();
  }

  public function get_groups(){
    $stmt = $this->pdo->prepare(self::$groups);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $groups = [];
    while($row = $stmt->fetch())
      $groups[] = $this->create_object($row);
    return $groups;
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$id);
    $stmt->bindValue(':company_id', (int) $this->company->get_id(), PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
      throw new RuntimeException();
    $id = (int) $stmt->fetch()['max_id'] + 1;
    return $id;
  }

  public function insert(data_group $group){
    $group->verify('id', 'name', 'status', 'company_id');
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', (int) $group->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $group->get_company_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $group->get_name(), PDO::PARAM_STR);
    $stmt->bindValue(':status', (string) $group->get_status(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $group;
  }

  public function update(data_group $group){
    $group->verify('id', 'name', 'status', 'company_id');
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':id', (int) $group->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':company_id', (int) $group->get_company_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $group->get_name(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $group;
  }
}