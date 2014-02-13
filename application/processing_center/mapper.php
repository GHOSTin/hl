<?php
class mapper_processing_center{

  private $pdo;

  private static $all = "SELECT `id`, `name`
    FROM `processing_centers` ORDER BY `name`";

  private static $one = "SELECT `id`, `name`
    FROM `processing_centers` WHERE `id` = :id";

  private static $update = "UPDATE `processing_centers` SET `name` = :name
    WHERE `id` = :id";

  private static $insert = "INSERT INTO `processing_centers` (`id`, `name`)
    VALUES (:id, :name)";

  private static $insert_id = "SELECT MAX(`id`) as `max_id`
    FROM `processing_centers`";


  public function __construct(){
    $this->pdo = di::get('pdo');
  }

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['id']);
    $center->set_name($row['name']);
    return $center;
  }

  public function insert(data_processing_center $center){
    $this->verify($center);
    $stmt = $this->pdo->prepare(self::$insert);
    $stmt->bindValue(':id', (int) $center->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $center->get_name(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $center;
  }

  public function get_insert_id(){
    $stmt = $this->pdo->prepare(self::$insert_id);
    if(!$stmt->execute())
      throw new RuntimeException();
    if($stmt->rowCount() !== 1)
        throw new RuntimeException();
    $user_id = (int) $stmt->fetch()['max_id'] + 1;
    return $user_id;
  }

  public function find($id){
    $stmt = $this->pdo->prepare(self::$one);
    $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
    if(!$stmt->execute())
      throw new RuntimeException();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new RuntimeException();
  }

  public function get_processing_centers(){
    $stmt = $this->pdo->prepare(self::$all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $centers = [];
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    return $centers;
  }

  public function update(data_processing_center $center){
    $this->verify($center);
    $stmt = $this->pdo->prepare(self::$update);
    $stmt->bindValue(':id', (int) $center->get_id(), PDO::PARAM_INT);
    $stmt->bindValue(':name', (string) $center->get_name(), PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $center;
  }

  private function verify(data_processing_center $center){
    data_processing_center::verify_id($center->get_id());
    data_processing_center::verify_name($center->get_name());
  }
}