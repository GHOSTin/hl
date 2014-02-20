<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/tests'))));
require_once(ROOT.'/framework/di.php');
require_once(ROOT.'/framework/data_object.php');
require_once(ROOT.'/framework/mapper.php');
require_once(ROOT.'/framework/e_model.php');
require_once(ROOT.'/application/application_configuration.php');
# data
require_once(ROOT.'/application/client_query/data.php');
require_once(ROOT.'/application/company/data.php');
require_once(ROOT.'/application/number/data.php');
require_once(ROOT.'/application/house/data.php');
require_once(ROOT.'/application/flat/data.php');
# mapa
require_once(ROOT.'/application/client_query/mapper.php');
require_once(ROOT.'/application/number/mapper.php');
# verify
require_once(ROOT.'/application/house/verify.php');
require_once(ROOT.'/application/flat/verify.php');
require_once(ROOT.'/application/number/verify.php');
require_once(ROOT.'/application/company/verify.php');
# factory
require_once(ROOT.'/application/flat/factory.php');
require_once(ROOT.'/application/client_query/factory.php');
require_once(ROOT.'/application/number/model.php');

class pdo_mock extends PDO{

  public function __construct(){

  }
}