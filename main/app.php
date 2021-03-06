<?php namespace main;

use ReflectionClass;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Silex\Provider\SessionServiceProvider;
use Twig_SimpleFilter;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use Swift_Message;
use Silex\Provider\MonologServiceProvider;
use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;

use config\general as conf;

$DS = DIRECTORY_SEPARATOR;
$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'main'))).$DS;
require_once($root."vendor".$DS."autoload.php");

date_default_timezone_set(conf::php_timezone);

$app = new Application();



//*** начало секции конфигурирования параметров
$app['debug'] = (conf::status === 'development')? true: false;
$app['site_url'] = conf::site_url;
$app['user'] = null;
$app['salt'] = conf::authSalt;
$app['chat_host'] = conf::chat_host;
$app['chat_port'] = conf::chat_port;
$app['email_for_reply'] = conf::email_for_reply;
$app['files'] = $root.'files/';
$app['logs_directory'] = conf::logs_directory;
$dbParams = array(
  'driver'   => 'pdo_mysql',
  'host'     => conf::db_host,
  'user'     => conf::db_user,
  'password' => conf::db_password,
  'dbname'   => conf::db_name,
  'charset'  => 'utf8'
);
//*** конец секции конфигурирования параметров



$config = new Configuration();
$driver = $config->newDefaultAnnotationDriver($root.$DS.'domain');
$config->setMetadataDriverImpl($driver);
$config->setProxyDir($root.$DS.'cache'.$DS.'proxy');
$config->setProxyNamespace('proxies');
$app['em'] = EntityManager::create($dbParams, $config);



//*** начало секции моделей
$app['\main\models\number'] = function($app){
  return new \main\models\number($app['em'], $app['user']);
};
$app['main\models\queries'] = function($app){
  return new models\queries($app['em'], $app['session'], $app['user'], $app['twig']);
};

$app['main\models\report_event'] = function($app){
  return new \main\models\report_event($app['em'], $app['session']);
};

$app['main\models\report_outages'] = function($app){
  return new models\report_outages($app['em'], $app['twig'], $app['user'], $app['session']);
};
$app['main\models\import_numbers'] = function($app){
  return new \main\models\import_numbers($app['em'], $app['session']);
};
$app['main\models\import_accruals'] = function($app){
  return new models\import_accruals($app['twig'], $app['em'], $app['user']);
};
$app['main\models\import_meterages'] = function($app){
  return new models\import_meterages($app['twig'], $app['em'], $app['user']);
};
$app['main\models\users'] = function($app){
  return new models\users($app['twig'], $app['em'], $app['user']);
};
$app['main\models\groups'] = function($app){
  return new models\groups($app['twig'], $app['em'], $app['user']);
};
$app['main\models\profile'] = function($app){
  return new models\profile($app['twig'], $app['em'], $app['user']);
};
$app['main\models\notification_center'] = function($app){
  return new models\notification_center($app['twig'], $app['em']);
};
$app['main\models\reports'] = function($app){
  return new models\reports($app['twig'], $app['user']);
};
$app['main\models\report_queries'] = function($app){
  return new models\report_queries($app['twig'], $app['em'], $app['user'], $app['session']);
};
$app['main\models\report_user_access'] = function($app){
  return new models\report_user_access($app['em'], $app['twig'], $app['user']);
};
$app['main\models\number_request'] = function($app){
  return new models\number_request($app['twig'], $app['em'], $app['user']);
};
$app['main\models\api_keys'] = function($app){
  return new models\api_keys($app['twig'], $app['em'], $app['user']);
};
$app['main\models\system'] = function($app){
  return new models\system($app['twig'], $app['user']);
};
$app['main\models\system_search'] = function($app){
  return new models\system_search($app['twig'], $app['em'], $app['user']);
};
$app['main\models\registrations'] = function($app){
  return new models\registrations($app['twig'], $app['em'], $app['user'], $app['system_log']);
};
$app['main\models\logs'] = function($app){
  return new models\logs($app['twig'], $app['user'], $app['logs']);
};
$app['main\models\factory'] = function($app){
  return new models\factory($app);
};

$app['main\models\numbers'] = function($app){
  return new models\numbers($app['twig'], $app['em'], $app['user']);
};

$app['main\models\outages'] = function($app){
  return new models\outages($app['twig'], $app['em'], $app['user']);
};

$app['main\models\repository'] = function($app){
  return new models\repository($app['em'], $app['user']);
};

$app['main\models\events'] = function($app){
  return new models\events($app['twig'], $app['em'], $app['user']);
};

$app['main\models\files'] = function($app){
  return new models\files($app['twig'], $app['em'], $app['user'], $app['filesystem'], $app['session'], $app['files']);
};
//*** конец секции моделей



$app['\domain\query2comment'] = $app->factory(function($app){
  return new \domain\query2comment;
});
$app['config_reflection'] = function($app){
  return new ReflectionClass('config\general');
};

$app['accrual_columns'] = function($app){
  return explode(';', conf::accrual_columns);
};

$app['filesystem'] = function($app) use ($root){
  return new Filesystem(new Adapter($root.'files'));
};

$app['logs'] = function($app) use ($root){
  return new Filesystem(new Adapter($root.'cache'));
};



if($app['debug']){
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates'];
}else{
  $cache = $root.$DS.'cache'.$DS.'twig'.$DS.'main'.$DS;
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates',
                'twig.options' => ['cache' => $cache]];
}



$app['Swift_Message'] = $app->factory(function($app){
  return Swift_Message::newInstance();
});
// Параметры swift должны идти всегда ниже того места где идет его регистрация
// Иначе параметры обнуляются во время регистрации если были заданы выше
$app->register(new SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
    'host' => 'smtp.yandex.ru',
    'port' => '465',
    'username' => conf::email_for_reply,
    'password' => conf::email_for_reply_password,
    'encryption' => 'ssl',
    'auth_mode' => null
);



$app->register(new SessionServiceProvider);

$app['session.storage.options'] = [
  'cookie_lifetime' => 86400 * 30,
  'gc_maxlifetime' => 86400 * 30
];

$app->register(new TwigServiceProvider(), $twig_conf);
$filter = new Twig_SimpleFilter('natsort', function (array $array) {
  natsort($array);
  return $array;
});
$app['twig']->addFilter($filter);



//*** начало секции конфигурирования логгирования
$app->register(new MonologServiceProvider(), array(
  'monolog.logfile' => $root.'cache'.$DS.'main.log',
  'monolog.level' => Logger::WARNING
));

$app['auth_log'] = function($app) use ($root, $DS){
  $formatter = new JsonFormatter();
  $logger = new Logger('auth');
  $stream = new StreamHandler($root.'cache'.$DS.'auth_main.log', Logger::INFO);
  $stream->setFormatter($formatter);
  $logger->pushHandler($stream);
  return $logger;
};

// Логгер для системных событий.
// Использую пока для того чтобы отделить логи фреймворка от логов системы
$app['system_log'] = function($app) use ($root, $DS){
  $formatter = new JsonFormatter();
  $logger = new Logger('system');
  $stream = new RotatingFileHandler($app['logs_directory'].'system_client.log', Logger::INFO);
  $stream->setFormatter($formatter);
  $logger->pushHandler($stream);
  return $logger;
};
//*** конец секции конфигурирования логгирования



$app->before(function (Request $request, Application $app) {
  if($app['session']->get('user')){
    $app['user'] = $app['em']->find('domain\user', $app['session']->get('user'));
  }
}, Application::EARLY_EVENT);

$app->before(function (Request $request, Application $app) {
  if(!is_null($app['user']) && $app['user']->get_status() !== 'true'){
    $app['session']->invalidate();
    $app['user'] = null;
    throw new AccessDeniedHttpException();
  }
}, Application::EARLY_EVENT);

$security = function(Request $request, Application $app){
  if(is_null($app['user']))
    throw new NotFoundHttpException();
};


//*** начало секции маршрутов
# default_pages
$app->get('/', 'main\controllers\default_page::default_page');
$app->get('/about/', 'main\controllers\default_page::about')->before($security);



# files
$app->post('/files/', 'main\controllers\files::load')->before($security);
$app->get('/files/{date}/{name}', 'main\controllers\files::get_file')->before($security);



# auth
$app->get('/enter/', 'main\controllers\auth::login_form');
$app->post('/enter/', 'main\controllers\auth::login');
$app->get('/logout/', 'main\controllers\auth::logout')->before($security);



# profile
$app->get('/profile/', 'main\controllers\profile::default_page')->before($security);
$app->get('/profile/get_userinfo', 'main\controllers\profile::get_user_info')->before($security);
$app->put('/profile/update_telephone', 'main\controllers\profile::update_telephone')->before($security);
$app->put('/profile/update_cellphone', 'main\controllers\profile::update_cellphone')->before($security);
$app->get('/profile/update_password', 'main\controllers\profile::update_password')->before($security);



#notification_center
$app->get('/notification_center/get_content/', 'main\controllers\notification_center::get_content')->before($security);



# api
$app->get('/api/get_chat_options', 'main\controllers\api::get_chat_options');
$app->get('/api/get_users', 'main\controllers\api::get_users');
$app->get('/api/get_user_by_login_and_password', 'main\controllers\api::get_user_by_login_and_password');



# works
$app->get('/works/', 'main\controllers\works::default_page')->before($security);
$app->get('/works/get_workgroup_content', 'main\controllers\works::get_workgroup_content')->before($security);
$app->get('/works/get_dialog_rename_workgroup', 'main\controllers\works::get_dialog_rename_workgroup')->before($security);
$app->get('/works/rename_workgroup', 'main\controllers\works::rename_workgroup')->before($security);
$app->get('/works/get_dialog_add_work', 'main\controllers\works::get_dialog_add_work')->before($security);
$app->get('/works/add_work', 'main\controllers\works::add_work')->before($security);
$app->get('/works/get_dialog_exclude_work', 'main\controllers\works::get_dialog_exclude_work')->before($security);
$app->get('/works/exclude_work', 'main\controllers\works::exclude_work')->before($security);
$app->get('/works/get_dialog_create_workgroup', 'main\controllers\works::get_dialog_create_workgroup')->before($security);
$app->get('/works/create_workgroup', 'main\controllers\works::create_workgroup')->before($security);
$app->get('/works/get_work_content', 'main\controllers\works::get_work_content')->before($security);
$app->get('/works/get_dialog_rename_work', 'main\controllers\works::get_dialog_rename_work')->before($security);
$app->get('/works/rename_work', 'main\controllers\works::rename_work')->before($security);
$app->get('/works/get_dialog_create_work', 'main\controllers\works::get_dialog_create_work')->before($security);
$app->get('/works/create_work', 'main\controllers\works::create_work')->before($security);
$app->get('/works/get_dialog_create_event', 'main\controllers\works::get_dialog_create_event')->before($security);
$app->get('/works/create_event', 'main\controllers\works::create_event')->before($security);
$app->get('/works/get_event_content', 'main\controllers\works::get_event_content')->before($security);
$app->get('/works/get_dialog_rename_event', 'main\controllers\works::get_dialog_rename_event')->before($security);
$app->get('/works/rename_event', 'main\controllers\works::rename_event')->before($security);
$app->get('/works/get_dialog_add_event', 'main\controllers\works::get_dialog_add_event')->before($security);
$app->get('/works/add_event', 'main\controllers\works::add_event')->before($security);
$app->get('/works/get_dialog_exclude_event', 'main\controllers\works::get_dialog_exclude_event')->before($security);
$app->get('/works/exclude_event', 'main\controllers\works::exclude_event')->before($security);



# phrase
$app->get('/api/workgroups/{id}/phrases/', 'main\controllers\workgroup::phrases')->before($security);
$app->get('/workgroups/{id}/phrases/create/', 'main\controllers\workgroup::create_phrase_dialog')->before($security);
$app->post('/workgroups/{id}/phrases/', 'main\controllers\workgroup::create_phrase')->before($security);
$app->get('/workgroups/phrases/{id}/remove/', 'main\controllers\phrase::remove_dialog')->before($security);
$app->delete('/workgroups/phrases/', 'main\controllers\phrase::remove')->before($security);
$app->get('/workgroups/phrases/{id}/edit/', 'main\controllers\phrase::edit_dialog')->before($security);
$app->put('/workgroups/phrases/{id}/', 'main\controllers\phrase::edit')->before($security);



# groups
$app->get('/user/get_groups', 'main\controllers\groups::get_groups')->before($security);
$app->get('/user/get_dialog_create_group', 'main\controllers\groups::get_dialog_create_group')->before($security);
$app->get('/user/create_group', 'main\controllers\groups::create_group')->before($security);



# user
$app->get('/user/', 'main\controllers\users::default_page')->before($security);
$app->get('/user/get_users', 'main\controllers\users::get_users')->before($security);
$app->get('/user/get_user_content', 'main\controllers\users::get_user_content')->before($security);
$app->get('/user/get_user_information', 'main\controllers\users::get_user_information')->before($security);
$app->get('/user/get_dialog_edit_fio', 'main\controllers\users::get_dialog_edit_fio')->before($security);
$app->get('/user/update_fio', 'main\controllers\users::update_fio')->before($security);
$app->get('/user/get_dialog_edit_login', 'main\controllers\users::get_dialog_edit_login')->before($security);
$app->get('/user/update_login', 'main\controllers\users::update_login')->before($security);
$app->get('/user/get_dialog_edit_user_status', 'main\controllers\users::get_dialog_edit_user_status')->before($security);
$app->get('/user/update_user_status', 'main\controllers\users::update_user_status')->before($security);
$app->get('/user/get_dialog_edit_password', 'main\controllers\users::get_dialog_edit_password')->before($security);
$app->get('/user/update_password', 'main\controllers\users::update_password')->before($security);
$app->get('/user/get_dialog_create_user', 'main\controllers\users::get_dialog_create_user')->before($security);
$app->get('/user/create_user', 'main\controllers\users::create_user')->before($security);
$app->get('/user/get_group_content', 'main\controllers\users::get_group_content')->before($security);
$app->get('/user/get_group_users', 'main\controllers\users::get_group_users')->before($security);
$app->get('/user/get_dialog_edit_group_name', 'main\controllers\users::get_dialog_edit_group_name')->before($security);
$app->get('/user/update_group_name', 'main\controllers\users::update_group_name')->before($security);
$app->get('/user/get_group_profile', 'main\controllers\users::get_group_profile')->before($security);
$app->get('/user/get_dialog_add_user', 'main\controllers\users::get_dialog_add_user')->before($security);
$app->get('/user/add_user', 'main\controllers\users::add_user')->before($security);
$app->get('/user/get_dialog_exclude_user', 'main\controllers\users::get_dialog_exclude_user')->before($security);
$app->get('/user/exclude_user', 'main\controllers\users::exclude_user')->before($security);



# users
$app->get('/users/{id}/restrictions/', 'main\controllers\users::get_restrictions')->before($security);
$app->get('/users/{id}/restrictions/{profile}/{item}/', 'main\controllers\users::update_restriction')->before($security);
$app->get('/users/{id}/access/', 'main\controllers\users::access')->before($security);
$app->get('/users/{id}/access/{profile}/{rule}/', 'main\controllers\users::update_access')->before($security);
$app->get('/users/access/', 'main\controllers\report_user_access::report')->before($security);



# metrics
$app->get('/metrics/', 'main\controllers\metrics::default_page')->before($security);
$app->get('/metrics/archive/', 'main\controllers\metrics::archive')->before($security);
$app->get('/metrics/archive/set_date', 'main\controllers\metrics::set_date')->before($security);
$app->post('/metrics/remove_metrics', 'main\controllers\metrics::remove_metrics')->before($security);



# events
$app->get('/numbers/events/days/{date}/', 'main\controllers\events::get_day_events')->before($security);
$app->get('/numbers/events/dialogs/create/', 'main\controllers\events::get_dialog_create_event')->before($security);
$app->get('/numbers/events/streets/{id}/houses/', 'main\controllers\events::houses')->before($security);
$app->get('/numbers/events/houses/{id}/numbers/', 'main\controllers\events::numbers')->before($security);
$app->post('/numbers/events/', 'main\controllers\events::create_event')->before($security);



# outages
$app->get('/numbers/outages/', 'main\controllers\outages::default_page')->before($security);
$app->get('/numbers/outages/dialogs/create/', 'main\controllers\outages::dialog_create')->before($security);
$app->get('/numbers/outages/streets/{id}/houses/', 'main\controllers\outages::houses')->before($security);
$app->get('/numbers/outages/groups/{id}/users/', 'main\controllers\outages::users')->before($security);
$app->post('/numbers/outages/', 'main\controllers\outages::create')->before($security);
$app->get('/numbers/outages/{id}/edit/', 'main\controllers\outages::edit')->before($security);
$app->post('/numbers/outages/{id}/', 'main\controllers\outages::update')->before($security);
$app->get('/numbers/outages/today/', 'main\controllers\outages::today')->before($security);
$app->get('/numbers/outages/yesterday/', 'main\controllers\outages::yesterday')->before($security);
$app->get('/numbers/outages/week/', 'main\controllers\outages::week')->before($security);
$app->get('/numbers/outages/lastweek/', 'main\controllers\outages::lastweek')->before($security);
$app->get('/numbers/outages/active/', 'main\controllers\outages::active')->before($security);



# number
$app->get('/number/', 'main\controllers\numbers::default_page')->before($security);
$app->get('/numbers/streets/', 'main\controllers\numbers::get_streets')->before($security);
$app->get('/numbers/streets/{id}/', 'main\controllers\numbers::get_street_content')->before($security);
$app->get('/numbers/houses/{id}/', 'main\controllers\numbers::get_house_content')->before($security);
$app->get('/numbers/houses/{id}/outages/', 'main\controllers\numbers::outages')->before($security);
$app->get('/numbers/{id}/', 'main\controllers\numbers::get_number_content')->before($security);
$app->get('/number/get_dialog_edit_number_fio', 'main\controllers\numbers::get_dialog_edit_number_fio')->before($security);
$app->get('/number/update_number_fio', 'main\controllers\numbers::update_number_fio')->before($security);
$app->get('/number/get_dialog_edit_number', 'main\controllers\numbers::get_dialog_edit_number')->before($security);
$app->get('/number/update_number', 'main\controllers\numbers::update_number')->before($security);
$app->get('/number/update_number_password', 'main\controllers\numbers::update_number_password')->before($security);
$app->get('/number/get_dialog_edit_number_telephone', 'main\controllers\numbers::get_dialog_edit_number_telephone')->before($security);
$app->get('/number/update_number_telephone', 'main\controllers\numbers::update_number_telephone')->before($security);
$app->get('/number/get_dialog_edit_number_cellphone', 'main\controllers\numbers::get_dialog_edit_number_cellphone')->before($security);
$app->get('/number/update_number_cellphone', 'main\controllers\numbers::update_number_cellphone')->before($security);
$app->get('/number/get_dialog_edit_number_email', 'main\controllers\numbers::get_dialog_edit_number_email')->before($security);
$app->get('/number/update_number_email', 'main\controllers\numbers::update_number_email')->before($security);
$app->get('/number/get_dialog_edit_department', 'main\controllers\numbers::get_dialog_edit_department')->before($security);
$app->get('/number/edit_department', 'main\controllers\numbers::edit_department')->before($security);
$app->get('/number/houses/{id}/queries/', 'main\controllers\numbers::query_of_house')->before($security);
$app->get('/number/query_of_number', 'main\controllers\numbers::query_of_number')->before($security);
$app->get('/number/accruals', 'main\controllers\numbers::accruals')->before($security);
$app->get('/number/contact_info', 'main\controllers\numbers::contact_info')->before($security);
$app->get('/number/get_events', 'main\controllers\numbers::get_events')->before($security);



# numbers
$app->get('/number/{id}/', 'main\controllers\number::get_number_json')->before($security);
$app->get('/numbers/{id}/get_dialog_generate_password/', 'main\controllers\number::get_dialog_generate_password')->before($security);
$app->get('/numbers/{id}/generate_password/', 'main\controllers\number::generate_password')->before($security);
$app->get('/numbers/{id}/contacts/', 'main\controllers\number::get_dialog_contacts')->before($security);
$app->post('/numbers/{id}/contacts/', 'main\controllers\number::update_contacts')->before($security);
$app->get('/numbers/{id}/contacts/history/', 'main\controllers\number::history')->before($security);
$app->get('/numbers/{id}/meterages/', 'main\controllers\number::meterages')->before($security);



# number2event
$app->get('/numbers/{number_id}/events/dialog_add/', 'main\controllers\number::get_dialog_add_event')->before($security);
$app->post('/numbers/{number_id}/events/', 'main\controllers\number::add_event')->before($security);
$app->get('/numbers/{number_id}/events/{event_id}/{time}/dialog_exclude/', 'main\controllers\number::get_dialog_exclude_event')->before($security);
$app->delete('/numbers/events/{id}/', 'main\controllers\number::exclude_event')->before($security);
$app->get('/numbers/events/{event_id}/dialog_edit/', 'main\controllers\number::get_dialog_edit_event')->before($security);
$app->put('/numbers/events/{event_id}/', 'main\controllers\number::edit_event')->before($security);
$app->get('/numbers/events/{id}/', 'main\controllers\number::get_event')->before($security);



# export
$app->get('/export/', 'main\controllers\export::default_page')->before($security);
$app->get('/export/get_dialog_export_numbers', 'main\controllers\export::get_dialog_export_numbers')->before($security);
$app->get('/export/export_numbers', 'main\controllers\export::export_numbers')->before($security);



# query
$app->get('/query/', 'main\controllers\queries::default_page')->before($security);
$app->get('/query/get_day', 'main\controllers\queries::get_day')->before($security);
$app->get('/query/get_timeline', 'main\controllers\queries::get_timeline')->before($security);
$app->get('/query/get_search', 'main\controllers\queries::get_search')->before($security);
$app->get('/query/get_search_result', 'main\controllers\queries::get_search_result')->before($security);
$app->get('/query/get_query_content', 'main\controllers\queries::get_query_content')->before($security);
$app->get('/query/get_query_title', 'main\controllers\queries::get_query_title')->before($security);
$app->get('/query/get_documents', 'main\controllers\queries::get_documents')->before($security);
$app->get('/query/print_query', 'main\controllers\queries::print_query')->before($security);
$app->get('/query/get_query_numbers', 'main\controllers\queries::get_query_numbers')->before($security);
$app->get('/query/get_query_users', 'main\controllers\queries::get_query_users')->before($security);
$app->get('/query/get_query_works', 'main\controllers\queries::get_query_works')->before($security);
$app->get('/query/get_query_comments', 'main\controllers\queries::get_query_comments')->before($security);
$app->get('/query/get_dialog_edit_work_type', 'main\controllers\queries::get_dialog_edit_work_type')->before($security);
$app->get('/query/update_work_type', 'main\controllers\queries::update_work_type')->before($security);
$app->get('/query/get_dialog_change_query_type', 'main\controllers\queries::get_dialog_change_query_type')->before($security);
$app->get('/query/update_query_type', 'main\controllers\queries::update_query_type')->before($security);
$app->get('/query/get_dialog_edit_description', 'main\controllers\queries::get_dialog_edit_description')->before($security);
$app->get('/query/update_description', 'main\controllers\queries::update_description')->before($security);
$app->get('/query/get_dialog_edit_reason', 'main\controllers\queries::get_dialog_edit_reason')->before($security);
$app->get('/query/update_reason', 'main\controllers\queries::update_reason')->before($security);
$app->get('/query/get_dialog_edit_contact_information', 'main\controllers\queries::get_dialog_edit_contact_information')->before($security);
$app->get('/query/update_contact_information', 'main\controllers\queries::update_contact_information')->before($security);
$app->get('/query/get_dialog_reopen_query', 'main\controllers\queries::get_dialog_reopen_query')->before($security);
$app->get('/query/reopen_query', 'main\controllers\queries::reopen_query')->before($security);
$app->get('/query/get_dialog_reclose_query', 'main\controllers\queries::get_dialog_reclose_query')->before($security);
$app->get('/query/reclose_query', 'main\controllers\queries::reclose_query')->before($security);
$app->get('/query/get_dialog_close_query', 'main\controllers\queries::get_dialog_close_query')->before($security);
$app->get('/query/close_query', 'main\controllers\queries::close_query')->before($security);
$app->get('/query/get_dialog_to_working_query', 'main\controllers\queries::get_dialog_to_working_query')->before($security);
$app->get('/query/to_working_query', 'main\controllers\queries::to_working_query')->before($security);
$app->get('/query/get_dialog_add_user', 'main\controllers\queries::get_dialog_add_user')->before($security);
$app->get('/query/get_user_options', 'main\controllers\queries::get_user_options')->before($security);
$app->get('/query/add_user', 'main\controllers\queries::add_user')->before($security);
$app->get('/query/get_dialog_remove_user', 'main\controllers\queries::get_dialog_remove_user')->before($security);
$app->get('/query/remove_user', 'main\controllers\queries::remove_user')->before($security);
$app->get('/query/get_dialog_add_comment', 'main\controllers\queries::get_dialog_add_comment')->before($security);
$app->get('/query/add_comment', 'main\controllers\queries::add_comment')->before($security);
$app->get('/query/get_dialog_add_work', 'main\controllers\queries::get_dialog_add_work')->before($security);
$app->get('/query/add_work', 'main\controllers\queries::add_work')->before($security);
$app->get('/query/get_dialog_remove_work', 'main\controllers\queries::get_dialog_remove_work')->before($security);
$app->get('/query/remove_work', 'main\controllers\queries::remove_work')->before($security);
$app->get('/query/clear_filters', 'main\controllers\queries::clear_filters')->before($security);
$app->get('/query/set_status', 'main\controllers\queries::set_status')->before($security);
$app->get('/query/set_department', 'main\controllers\queries::set_department')->before($security);
$app->get('/query/set_street', 'main\controllers\queries::set_street')->before($security);
$app->get('/query/set_house', 'main\controllers\queries::set_house')->before($security);
$app->get('/query/set_work_type', 'main\controllers\queries::set_work_type')->before($security);
$app->get('/query/set_query_type', 'main\controllers\queries::set_query_type')->before($security);
$app->get('/query/get_dialog_change_initiator', 'main\controllers\queries::get_dialog_change_initiator')->before($security);
$app->get('/query/get_houses', 'main\controllers\queries::get_houses')->before($security);
$app->get('/query/get_numbers', 'main\controllers\queries::get_numbers')->before($security);
$app->get('/query/change_initiator', 'main\controllers\queries::change_initiator')->before($security);
$app->get('/query/get_dialog_create_query', 'main\controllers\queries::get_dialog_create_query')->before($security);
$app->get('/query/get_dialog_initiator', 'main\controllers\queries::get_dialog_initiator')->before($security);
$app->get('/query/get_initiator', 'main\controllers\queries::get_initiator')->before($security);
$app->get('/query/create_query', 'main\controllers\queries::create_query')->before($security);
$app->get('/query/update_contacts', 'main\controllers\queries::update_contacts')->before($security);



# queries
$app->get('/queries/{id}/files/', 'main\controllers\queries::get_query_files')->before($security);
$app->post('/queries/{id}/files/', 'main\controllers\queries::add_file')->before($security);
$app->get('/queries/{id}/files/{date}/{name}', 'main\controllers\queries::get_file')->before($security);
$app->get('/queries/{id}/files/{date}/{name}/get_dialog_delete_file/', 'main\controllers\queries::get_dialog_delete_file')->before($security);
$app->get('/queries/{id}/files/{date}/{name}/delete/', 'main\controllers\queries::delete_file')->before($security);
$app->get('/queries/dialogs/create_query_from_request/', 'main\controllers\queries::create_query_from_request_dialog')->before($security);
$app->get('/queries/create_query_from_request/', 'main\controllers\queries::create_query_from_request')->before($security);
$app->get('/queries/dialogs/abort_query_from_request/', 'main\controllers\queries::abort_query_from_request_dialog')->before($security);
$app->get('/queries/{id}/dialogs/edit_visible/', 'main\controllers\queries::edit_visible_dialog')->before($security);
$app->get('/queries/{id}/visible/', 'main\controllers\queries::update_visible')->before($security);
$app->get('/queries/abort_query_from_request/', 'main\controllers\queries::abort_query_from_request')->before($security);
$app->get('/queries/requests/count/', 'main\controllers\queries::count')->before($security);
$app->get('/queries/requests/', 'main\controllers\queries::requests')->before($security);
$app->get('/queries/outages/', 'main\controllers\queries::outages')->before($security);
$app->get('/queries/day/stats/', 'main\controllers\queries::stats')->before($security);
$app->get('/queries/selections/', 'main\controllers\queries::selections')->before($security);
$app->get('/queries/selections/noclose/', 'main\controllers\queries::noclose')->before($security);
$app->get('/queries/{id}/history/', 'main\controllers\queries::history')->before($security);
$app->get('/queries/stats/all/noslose/', 'main\controllers\queries::all_noclose')->before($security);



# reports
$app->get('/reports/', 'main\controllers\reports::default_page')->before($security);
$app->get('/reports/queries/', 'main\controllers\report_queries::default_page')->before($security);
$app->get('/reports/queries/clear_filters/', 'main\controllers\report_queries::clear_filters')->before($security);
$app->get('/reports/queries/set_time_begin/', 'main\controllers\report_queries::set_time_begin')->before($security);
$app->get('/reports/queries/set_time_end/', 'main\controllers\report_queries::set_time_end')->before($security);
$app->get('/reports/queries/set_status/', 'main\controllers\report_queries::set_status')->before($security);
$app->get('/reports/queries/set_department/', 'main\controllers\report_queries::set_department')->before($security);
$app->get('/reports/queries/set_worktype/', 'main\controllers\report_queries::set_worktype')->before($security);
$app->get('/reports/queries/set_query_type/', 'main\controllers\report_queries::set_query_type')->before($security);
$app->get('/reports/queries/set_street/', 'main\controllers\report_queries::set_street')->before($security);
$app->get('/reports/queries/set_house/', 'main\controllers\report_queries::set_house')->before($security);
$app->get('/reports/queries/report1/', 'main\controllers\report_queries::report1')->before($security);
$app->get('/reports/queries/report1/noclose/', 'main\controllers\report_queries::noclose')->before($security);
$app->get('/reports/queries/report1/xls/', 'main\controllers\report_queries::report1_xls')->before($security);
$app->get('/reports/event/', 'main\controllers\report_event::get_event_reports')->before($security);
$app->get('/reports/event/set_time_begin', 'main\controllers\report_event::set_time_begin')->before($security);
$app->get('/reports/event/set_time_end', 'main\controllers\report_event::set_time_end')->before($security);
$app->get('/reports/event/html/', 'main\controllers\report_event::html')->before($security);
$app->get('/reports/event/clear/', 'main\controllers\report_event::clear')->before($security);
$app->get('/reports/outages/', 'main\controllers\report_outages::default_page')->before($security);
$app->get('/reports/outages/html/', 'main\controllers\report_outages::html')->before($security);
$app->post('/reports/outages/filters/begin/start/', 'main\controllers\report_outages::start')->before($security);
$app->post('/reports/outages/filters/begin/end/', 'main\controllers\report_outages::end')->before($security);



# tasks
$app->get('/task/', 'main\controllers\task::default_page')->before($security);
$app->get('/task/show_active_tasks', 'main\controllers\task::show_active_tasks')->before($security);
$app->get('/task/show_finished_tasks', 'main\controllers\task::show_finished_tasks')->before($security);
$app->get('/task/get_task_content', 'main\controllers\task::get_task_content')->before($security);
$app->get('/task/send_task_comment', 'main\controllers\task::send_task_comment')->before($security);
$app->get('/task/edit_task_content', 'main\controllers\task::edit_task_content')->before($security);
$app->get('/task/save_task_content', 'main\controllers\task::save_task_content')->before($security);
$app->get('/task/get_dialog_create_task', 'main\controllers\task::get_dialog_create_task')->before($security);
$app->get('/task/add_task', 'main\controllers\task::add_task')->before($security);
$app->get('/task/get_dialog_close_task', 'main\controllers\task::get_dialog_close_task')->before($security);
$app->get('/task/close_task', 'main\controllers\task::close_task')->before($security);



# import
$app->get('/import/', 'main\controllers\import::default_page')->before($security);
$app->post('/import/load_accruals/', 'main\controllers\import::load_accruals')->before($security);
$app->post('/import/load_debt/', 'main\controllers\import::load_debt')->before($security);
$app->post('/import/fond/', 'main\controllers\import::load_fond_file')->before($security);
$app->post('/import/streets/', 'main\controllers\import::load_streets')->before($security);
$app->post('/import/houses/', 'main\controllers\import::load_houses')->before($security);
$app->post('/import/flats/', 'main\controllers\import::load_flats')->before($security);
$app->post('/import/numbers/', 'main\controllers\import::load_numbers')->before($security);
$app->post('/import/meterages/', 'main\controllers\import::load_meterages')->before($security);



# system
$app->get('/system/', 'main\controllers\system::default_page')->before($security);
$app->get('/system/query_types/', 'main\controllers\query_types::default_page')->before($security);
$app->get('/system/query_types/get_dialog_create_query_type/', 'main\controllers\query_types::get_dialog_create_query_type')->before($security);
$app->get('/system/query_types/create_query_type/', 'main\controllers\query_types::create_query_type')->before($security);
$app->get('/system/query_types/{id}/color/', 'main\controllers\query_type::color')->before($security);



# registrations
$app->get('/system/registrations/', 'main\controllers\registrations::default_page')->before($security);
$app->get('/system/registrations/requests/open/', 'main\controllers\registrations::open')->before($security);



# api keys
$app->get('/system/api/keys/', 'main\controllers\api_keys::default_page')->before($security);
$app->get('/system/api/keys/create/dialog/', 'main\controllers\api_keys::create_dialog')->before($security);
$app->get('/system/api/keys/create/', 'main\controllers\api_keys::create')->before($security);



# conf
$app->get('/system/config/', 'main\controllers\system::config')->before($security);



# logs
$app->get('/system/logs/', 'main\controllers\logs::default_page')->before($security);
$app->get('/system/logs/client/', 'main\controllers\logs::client')->before($security);
$app->get('/system/logs/main/', 'main\controllers\logs::main')->before($security);



# search
$app->get('/system/search/number/', 'main\controllers\system::search_number_form')->before($security);
$app->post('/system/search/number/', 'main\controllers\system::search_number')->before($security);
//*** конец секции маршрутов



$app->error(function(NotFoundHttpException $e) use ($app){
  return $app['twig']->render('error404.tpl', ['user' => $app['user']]);
});

$app->error(function(AccessDeniedHttpException $e) use ($app){
  return $app['twig']->render('auth/block.tpl', ['user' => $app['user']]);
});

$app->run();