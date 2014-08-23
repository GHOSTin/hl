<?php

class mapper_metrics extends mapper {

  private static $find_all = "SELECT * FROM metrics";

  private static $delete = "DELETE FROM metrics WHERE id = :id";

  public function find_all(){
    $stmt = $this->pdo->prepare(self::$find_all);
    if(!$stmt->execute())
      throw new RuntimeException();
    $metrics = [];
    while($row = $stmt->fetch()){
      $metrics[] = di::get('factory_metrics')->build($row);
    }
    return $metrics;
  }

  public function delete($id){
    $stmt = $this->pdo->prepare(self::$delete);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    if(!$stmt->execute())
      throw new RuntimeException();
    return $id;
  }

} 