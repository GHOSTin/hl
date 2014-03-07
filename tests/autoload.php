<?php
define('ROOT' , substr(__DIR__, 0, (strlen(__DIR__) - strlen('/tests'))));
date_default_timezone_set('Asia/Yekaterinburg');
require_once(ROOT.'/framework/di.php');
require_once(ROOT.'/framework/data_object.php');
require_once(ROOT.'/framework/mapper.php');
require_once(ROOT.'/framework/e_model.php');
require_once(ROOT.'/application/application_configuration.php');
# data
require_once(ROOT.'/application/client_query/data.php');
require_once(ROOT.'/application/street/data.php');
require_once(ROOT.'/application/company/data.php');
require_once(ROOT.'/application/number/data.php');
require_once(ROOT.'/application/house/data.php');
require_once(ROOT.'/application/flat/data.php');
require_once(ROOT.'/application/query/data.php');
require_once(ROOT.'/application/query_work_type/data.php');
require_once(ROOT.'/application/user/data.php');
require_once(ROOT.'/application/department/data.php');
require_once(ROOT.'/application/error/data.php');
# mapa
require_once(ROOT.'/application/client_query/mapper.php');
require_once(ROOT.'/application/number/mapper.php');
require_once(ROOT.'/application/query/mapper.php');
require_once(ROOT.'/application/user/mapper.php');
require_once(ROOT.'/application/error/mapper.php');
# model
require_once(ROOT.'/application/user/model.php');
require_once(ROOT.'/application/number/model.php');
require_once(ROOT.'/application/error/model.php');
# verify
require_once(ROOT.'/application/house/verify.php');
require_once(ROOT.'/application/flat/verify.php');
require_once(ROOT.'/application/number/verify.php');
require_once(ROOT.'/application/company/verify.php');
require_once(ROOT.'/application/query/verify.php');
require_once(ROOT.'/application/query_work_type/verify.php');
require_once(ROOT.'/application/street/verify.php');
require_once(ROOT.'/application/department/verify.php');
require_once(ROOT.'/application/user/verify.php');
# factory
require_once(ROOT.'/application/flat/factory.php');
require_once(ROOT.'/application/client_query/factory.php');
require_once(ROOT.'/application/house/factory.php');
require_once(ROOT.'/application/number/factory.php');
require_once(ROOT.'/application/street/factory.php');
require_once(ROOT.'/application/error/factory.php');
require_once(ROOT.'/application/department/factory.php');
require_once(ROOT.'/application/query/factory.php');

class pdo_mock extends PDO{

  public function __construct(){

  }
}