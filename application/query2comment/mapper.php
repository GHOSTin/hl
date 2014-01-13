<?php

class mapper_query2comment{

  private $pdo;
  private $company;
  private $query;

  private static $find_all = "SELECT `company_id`, `query_id`, `user_id`, `time`,
    `message` FROM `query2comment`";

  public function __construct(PDO $pdo, data_company $company, data_query $query){
    $this->pdo = $pdo;
    $this->company = $company;
    $this->query = $query;
  }

  public function init_comments(){
    return $this->query;
  }
}