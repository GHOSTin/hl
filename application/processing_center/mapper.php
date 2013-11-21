<?php
class mapper_processing_center{

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

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['id']);
    $center->set_name($row['name']);
    return $center;
  }

  public function insert(data_processing_center $center){
    $this->verify($center);
    $sql = new sql();
    $sql->query(self::$insert);
    $sql->bind(':id', (int) $center->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $center->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблемы при создании процессингового центра.');
    return $center;
  }

  /**
  * Возвращает следующий для вставки processing_center_id.
  * @return int
  */
  public function get_insert_id(){
    $sql = new sql();
    $sql->query(self::$insert_id);
    $sql->execute('Проблема при опредении следующего processing_center_id.');
    if($sql->count() !== 1)
        throw new e_model('Проблема при опредении следующего processing_center_id.');
    $user_id = (int) $sql->row()['max_id'] + 1;
    $sql->close();
    return $user_id;
  }

  /**
  * Возвращает список процессинговых центров
  * @return list object data_processing_center
  */
  public function find($id){
    $sql = new sql();
    $sql->query(self::$one);
    $sql->bind(':id', (int) $id, PDO::PARAM_INT);
    $sql->execute('Проблема при выборке расчетных центров.');
    $stmt = $sql->get_stm();
    $count = $stmt->rowCount();
    if($count === 0)
      return null;
    elseif($count === 1)
      return $this->create_object($stmt->fetch());
    else
      throw new e_model('Неожиданное количество процессинговых центров.');
  }

  /**
  * Возвращает список процессинговых центров
  * @return list object data_processing_center
  */
  public function get_processing_centers(){
    $sql = new sql();
    $sql->query(self::$all);
    $sql->execute('Проблема при выборке расчетных центров.');
    $stmt = $sql->get_stm();
    $centers = [];
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    return $centers;
  }

  public function update(data_processing_center $center){
    $this->verify($center);
    $sql = new sql();
    $sql->query(self::$update);
    $sql->bind(':id', (int) $center->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $center->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблемы при переименовании процессингового центра.');
    return $center;
  }

  private function verify(data_processing_center $center){
    data_processing_center::verify_id($center->get_id());
    data_processing_center::verify_name($center->get_name());
  }
}