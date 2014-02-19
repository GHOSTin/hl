<?php 
class mapper{

  protected $pdo;

  public function __construct(PDO $pdo){
    $this->pdo = $pdo;
  }
}