<?php
class mapper_processing_center{

  private static $sql_get_centers = "SELECT `id`, `name`
    FROM `processing_centers` ORDER BY `name`";

  private static $sql_get_center = "SELECT `id`, `name`
    FROM `processing_centers` WHERE `id` = :id";

  public function create_object(array $row){
    $center = new data_processing_center();
    $center->set_id($row['id']);
    $center->set_name($row['name']);
    return $center;
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
    $sql->bind(':id', $id, PDO::PARAM_STR);
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
}