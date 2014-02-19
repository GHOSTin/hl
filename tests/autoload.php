<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/tests'))));
require_once(ROOT.'/application/client_query/data.php');
require_once(ROOT.'/application/company/data.php');
require_once(ROOT.'/application/number/data.php');
require_once(ROOT.'/application/client_query/factory.php');
require_once(ROOT.'/application/client_query/mapper.php');

class pdo_mock extends PDO{

  public function __construct(){

  }
}

class mapper{

  protected $pdo;

  public function __construct(PDO $pdo){
    $this->pdo = $pdo;
  }
}

class data_object{
}