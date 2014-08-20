<?php

class model_metrics {

  public function remove(array $ids){
    $pdo = di::get('pdo');
    $pdo->beginTransaction();
    $deleted = [];
    for($i=0;$i < count($ids);$i++){
      $deleted[] = di::get('mapper_metrics')->delete($ids[$i]);
    }
    $pdo->commit();
    return $deleted;
  }
} 