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
require_once(ROOT.'/application/session/data.php');
require_once(ROOT.'/application/group/data.php');
require_once(ROOT.'/application/work/data.php');
require_once(ROOT.'/application/workgroup/data.php');
require_once(ROOT.'/application/query2comment/data.php');
require_once(ROOT.'/application/query2work/data.php');
require_once(ROOT.'/application/city/data.php');
require_once(ROOT.'/application/accrual/data.php');
require_once(ROOT.'/application/number2meter/data.php');
require_once(ROOT.'/application/number2processing_center/data.php');
require_once(ROOT.'/application/house2processing_center/data.php');
# mapa
require_once(ROOT.'/application/client_query/mapper.php');
require_once(ROOT.'/application/number/mapper.php');
require_once(ROOT.'/application/query/mapper.php');
require_once(ROOT.'/application/user/mapper.php');
require_once(ROOT.'/application/error/mapper.php');
require_once(ROOT.'/application/session/mapper.php');
require_once(ROOT.'/application/query2comment/mapper.php');
require_once(ROOT.'/application/query2work/mapper.php');
require_once(ROOT.'/application/street2house/mapper.php');
require_once(ROOT.'/application/accrual/mapper.php');
# model
require_once(ROOT.'/application/user/model.php');
require_once(ROOT.'/application/number/model.php');
require_once(ROOT.'/application/error/model.php');
require_once(ROOT.'/application/request/model.php');
require_once(ROOT.'/application/query/model.php');
require_once(ROOT.'/application/group/model.php');
require_once(ROOT.'/application/work/model.php');
require_once(ROOT.'/application/workgroup/model.php');
require_once(ROOT.'/application/street/model.php');
require_once(ROOT.'/application/house/model.php');
require_once(ROOT.'/application/query_work_type/model.php');
require_once(ROOT.'/application/street2house/model.php');
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
require_once(ROOT.'/application/work/verify.php');
require_once(ROOT.'/application/city/verify.php');
# factory
require_once(ROOT.'/application/flat/factory.php');
require_once(ROOT.'/application/client_query/factory.php');
require_once(ROOT.'/application/house/factory.php');
require_once(ROOT.'/application/number/factory.php');
require_once(ROOT.'/application/street/factory.php');
require_once(ROOT.'/application/error/factory.php');
require_once(ROOT.'/application/department/factory.php');
require_once(ROOT.'/application/query/factory.php');
require_once(ROOT.'/application/session/factory.php');
require_once(ROOT.'/application/user/factory.php');
require_once(ROOT.'/application/query2comment/factory.php');
require_once(ROOT.'/application/work/factory.php');
require_once(ROOT.'/application/accrual/factory.php');
# controllers
require_once(ROOT.'/application/query/controller.php');

class pdo_mock extends PDO{

  public function __construct(){

  }
}