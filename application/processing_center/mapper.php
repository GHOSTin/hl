<?php
class mapper_processing_center{

  private static $sql_get_centers = "SELECT `id`, `name`
    FROM `processing_centers` ORDER BY `name`";

  private static $sql_get_center = "SELECT `id`, `name`
    FROM `processing_centers` WHERE `id` = :id";

  private static $sql_update = "UPDATE `processing_centers` SET `name` = :name
    WHERE `id` = :id";

  private static $sql_insert = "INSERT INTO `processing_centers` (`id`, `name`)
    VALUES (:id, :name)";

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['id']);
    $center->set_name($row['name']);
    return $center;
  }

  public function insert(data_processing_center $center){
    $center->verify('id', 'name');
    $sql = new sql();
    $sql->query(self::$sql_insert);
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
    $sql->query("SELECT MAX(`id`) as `max_id` FROM `processing_centers`");
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
    $c = new data_processing_center();
    $c->set_id($id);
    $c->verify('id');
    $sql = new sql();
    $sql->query(self::$sql_get_center);
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
    $sql->query(self::$sql_get_centers);
    $sql->execute('Проблема при выборке расчетных центров.');
    $stmt = $sql->get_stm();
    $centers = [];
    while($row = $stmt->fetch())
      $centers[] = $this->create_object($row);
    return $centers;
  }

  public function update(data_processing_center $center){
    $center->verify('id', 'name');
    $sql = new sql();
    $sql->query(self::$sql_update);
    $sql->bind(':id', (int) $center->get_id(), PDO::PARAM_INT);
    $sql->bind(':name', (string) $center->get_name(), PDO::PARAM_STR);
    $sql->execute('Проблемы при переименовании процессингового центра.');
    return $center;
  }
}