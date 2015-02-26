<?php namespace main;

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \Silex\Application;
use \Silex\Provider\TwigServiceProvider;
use \Silex\Provider\SwiftmailerServiceProvider;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use config\general as conf;
use Twig_SimpleFilter;

$DS = DIRECTORY_SEPARATOR;
$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'main'))).$DS;
require_once($root."vendor/autoload.php");

date_default_timezone_set(conf::php_timezone);

$dbParams = array(
  'driver'   => 'pdo_mysql',
  'host'     => conf::db_host,
  'user'     => conf::db_user,
  'password' => conf::db_password,
  'dbname'   => conf::db_name,
  'charset'  => 'utf8'
);

$app = new Application();
$app['debug'] = (conf::status === 'development')? true: false;
$app['user'] = null;
$app['salt'] = conf::authSalt;
$app['chat_host'] = conf::chat_host;
$app['chat_port'] = conf::chat_port;

$config = Setup::createAnnotationMetadataConfiguration([__DIR__], $app['debug']);
$app['em'] = EntityManager::create($dbParams, $config);

$app['\main\models\number'] = function($app){
  return new \main\models\number($app);
};
$app['\main\models\query'] = function($app){
  return new \main\models\query($app);
};
$app['main\models\report_query'] = function($app){
  return new \main\models\report_query($app);
};
$app['\domain\query2comment'] = $app->factory(function($app){
  return new \domain\query2comment;
});

if($app['debug']){
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates'];
}else{
  $cache = $root.$DS.'cache'.$DS.'twig'.$DS.'main'.$DS;
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates',
                'twig.options' => ['cache' => $cache]];
}
$app->register(new TwigServiceProvider(), $twig_conf);
$filter = new Twig_SimpleFilter('natsort', function (array $array) {
  natsort($array);
  return $array;
});
$app['twig']->addFilter($filter);

$app->before(function (Request $request, Application $app) {
  session_start();
  if(isset($_SESSION['user'])){
    $app['user'] = $app['em']->find('\domain\user', $_SESSION['user']);
  }
}, Application::EARLY_EVENT);

$security = function(Request $request, Application $app){
  if(is_null($app['user']))
    throw new NotFoundHttpException();
};

#default_pages
$app->get('/', 'main\controllers\default_page::default_page');
$app->post('/login/', 'main\controllers\default_page::login');
$app->get('/logout/', 'main\controllers\default_page::logout')->before($security);
$app->get('/about/', 'main\controllers\default_page::about')->before($security);

# profile
$app->get('/profile/', 'main\controllers\profile::default_page')->before($security);
$app->get('/profile/get_userinfo', 'main\controllers\profile::get_user_info')->before($security);
$app->put('/profile/update_telephone', 'main\controllers\profile::update_telephone')->before($security);
$app->put('/profile/update_cellphone', 'main\controllers\profile::update_cellphone')->before($security);
$app->get('/profile/update_password', 'main\controllers\profile::update_password')->before($security);
$app->get('/profile/get_notification_center_content', 'main\controllers\profile::get_notification_center_content')->before($security);

# api
$app->get('/api/get_chat_options', 'main\controllers\api::get_chat_options');
$app->get('/api/get_users', 'main\controllers\api::get_users');
$app->get('/api/get_user_by_login_and_password', 'main\controllers\api::get_user_by_login_and_password');
# error
$app->get('/error/show_dialog', 'main\controllers\error::show_dialog')->before($security);
$app->get('/error/send_error', 'main\controllers\error::send_error')->before($security);
$app->get('/error/delete_error', 'main\controllers\error::delete_error')->before($security);
$app->get('/error/', 'main\controllers\error::default_page')->before($security);

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

# user
$app->get('/user/', 'main\controllers\users::default_page')->before($security);
$app->get('/user/get_user_letter', 'main\controllers\users::get_user_letter')->before($security);
$app->get('/user/logs', 'main\controllers\users::logs')->before($security);
$app->get('/user/get_dialog_clear_logs', 'main\controllers\users::get_dialog_clear_logs')->before($security);
$app->get('/user/clear_logs', 'main\controllers\users::clear_logs')->before($security);
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
$app->get('/user/get_group_letters', 'main\controllers\users::get_group_letters')->before($security);
$app->get('/user/get_user_letters', 'main\controllers\users::get_user_letters')->before($security);
$app->get('/user/get_group_letter', 'main\controllers\users::get_group_letter')->before($security);
$app->get('/user/get_group_content', 'main\controllers\users::get_group_content')->before($security);
$app->get('/user/get_group_users', 'main\controllers\users::get_group_users')->before($security);
$app->get('/user/get_dialog_edit_group_name', 'main\controllers\users::get_dialog_edit_group_name')->before($security);
$app->get('/user/update_group_name', 'main\controllers\users::update_group_name')->before($security);
$app->get('/user/get_group_profile', 'main\controllers\users::get_group_profile')->before($security);
$app->get('/user/get_dialog_add_user', 'main\controllers\users::get_dialog_add_user')->before($security);
$app->get('/user/add_user', 'main\controllers\users::add_user')->before($security);
$app->get('/user/get_dialog_exclude_user', 'main\controllers\users::get_dialog_exclude_user')->before($security);
$app->get('/user/exclude_user', 'main\controllers\users::exclude_user')->before($security);
$app->get('/user/get_dialog_create_group', 'main\controllers\users::get_dialog_create_group')->before($security);
$app->get('/user/create_group', 'main\controllers\users::create_group')->before($security);
$app->get('/user/get_user_profiles', 'main\controllers\users::get_user_profiles')->before($security);
$app->get('/user/get_profile_content', 'main\controllers\users::get_profile_content')->before($security);
$app->get('/user/update_rule', 'main\controllers\users::update_rule')->before($security);
$app->get('/user/get_dialog_delete_profile', 'main\controllers\users::get_dialog_delete_profile')->before($security);
$app->get('/user/delete_profile', 'main\controllers\users::delete_profile')->before($security);
$app->get('/user/get_dialog_add_profile', 'main\controllers\users::get_dialog_add_profile')->before($security);
$app->get('/user/add_profile', 'main\controllers\users::add_profile')->before($security);
$app->get('/user/get_restriction_content', 'main\controllers\users::get_restriction_content')->before($security);
$app->get('/user/update_restriction', 'main\controllers\users::update_restriction')->before($security);

# metrics
$app->get('/metrics/', 'main\controllers\metrics::default_page')->before($security);
$app->get('/metrics/archive/', 'main\controllers\metrics::archive')->before($security);
$app->get('/metrics/archive/set_date', 'main\controllers\metrics::set_date')->before($security);
$app->post('/metrics/remove_metrics', 'main\controllers\metrics::remove_metrics')->before($security);

# number
$app->get('/number/', 'main\controllers\numbers::default_page')->before($security);
$app->get('/number/get_street_content', 'main\controllers\numbers::get_street_content')->before($security);
$app->get('/number/get_house_content', 'main\controllers\numbers::get_house_content')->before($security);
$app->get('/number/get_number_content', 'main\controllers\numbers::get_number_content')->before($security);
$app->get('/number/get_dialog_edit_number_fio', 'main\controllers\numbers::get_dialog_edit_number_fio')->before($security);
$app->get('/number/update_number_fio', 'main\controllers\numbers::update_number_fio')->before($security);
$app->get('/number/get_dialog_edit_number', 'main\controllers\numbers::get_dialog_edit_number')->before($security);
$app->get('/number/update_number', 'main\controllers\numbers::update_number')->before($security);
$app->get('/number/get_dialog_edit_password', 'main\controllers\numbers::get_dialog_edit_password')->before($security);
$app->get('/number/update_number_password', 'main\controllers\numbers::update_number_password')->before($security);
$app->get('/number/get_dialog_edit_number_telephone', 'main\controllers\numbers::get_dialog_edit_number_telephone')->before($security);
$app->get('/number/update_number_telephone', 'main\controllers\numbers::update_number_telephone')->before($security);
$app->get('/number/get_dialog_edit_number_cellphone', 'main\controllers\numbers::get_dialog_edit_number_cellphone')->before($security);
$app->get('/number/update_number_cellphone', 'main\controllers\numbers::update_number_cellphone')->before($security);
$app->get('/number/get_dialog_edit_number_email', 'main\controllers\numbers::get_dialog_edit_number_email')->before($security);
$app->get('/number/update_number_email', 'main\controllers\numbers::update_number_email')->before($security);
$app->get('/number/get_dialog_edit_department', 'main\controllers\numbers::get_dialog_edit_department')->before($security);
$app->get('/number/edit_department', 'main\controllers\numbers::edit_department')->before($security);
$app->get('/number/query_of_house', 'main\controllers\numbers::query_of_house')->before($security);
$app->get('/number/query_of_number', 'main\controllers\numbers::query_of_number')->before($security);
$app->get('/number/accruals', 'main\controllers\numbers::accruals')->before($security);
$app->get('/number/contact_info', 'main\controllers\numbers::contact_info')->before($security);

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
$app->get('/query/get_dialog_edit_payment_status', 'main\controllers\queries::get_dialog_edit_payment_status')->before($security);
$app->get('/query/update_payment_status', 'main\controllers\queries::update_payment_status')->before($security);
$app->get('/query/get_dialog_edit_work_type', 'main\controllers\queries::get_dialog_edit_work_type')->before($security);
$app->get('/query/update_work_type', 'main\controllers\queries::update_work_type')->before($security);
$app->get('/query/get_dialog_edit_warning_status', 'main\controllers\queries::get_dialog_edit_warning_status')->before($security);
$app->get('/query/update_warning_status', 'main\controllers\queries::update_warning_status')->before($security);
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
$app->get('/query/get_dialog_change_initiator', 'main\controllers\queries::get_dialog_change_initiator')->before($security);
$app->get('/query/get_houses', 'main\controllers\queries::get_houses')->before($security);
$app->get('/query/get_numbers', 'main\controllers\queries::get_numbers')->before($security);
$app->get('/query/change_initiator', 'main\controllers\queries::change_initiator')->before($security);
$app->get('/query/get_dialog_create_query', 'main\controllers\queries::get_dialog_create_query')->before($security);
$app->get('/query/get_dialog_initiator', 'main\controllers\queries::get_dialog_initiator')->before($security);
$app->get('/query/get_initiator', 'main\controllers\queries::get_initiator')->before($security);
$app->get('/query/create_query', 'main\controllers\queries::create_query')->before($security);

# report
$app->get('/report/', 'main\controllers\reports::default_page')->before($security);
$app->get('/report/get_query_reports', 'main\controllers\reports::get_query_reports')->before($security);
$app->get('/report/clear_filter_query', 'main\controllers\reports::clear_filter_query')->before($security);
$app->get('/report/set_time_begin', 'main\controllers\reports::set_time_begin')->before($security);
$app->get('/report/set_time_end', 'main\controllers\reports::set_time_end')->before($security);
$app->get('/report/set_filter_query_status', 'main\controllers\reports::set_filter_query_status')->before($security);
$app->get('/report/set_filter_query_department', 'main\controllers\reports::set_filter_query_department')->before($security);
$app->get('/report/set_filter_query_worktype', 'main\controllers\reports::set_filter_query_worktype')->before($security);
$app->get('/report/set_filter_query_street', 'main\controllers\reports::set_filter_query_street')->before($security);
$app->get('/report/set_filter_query_house', 'main\controllers\reports::set_filter_query_house')->before($security);
$app->get('/report/report_query_one', 'main\controllers\reports::report_query_one')->before($security);
$app->get('/report/report_query_one_xls', 'main\controllers\reports::report_query_one_xls')->before($security);

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

$app->error(function (NotFoundHttpException $e) use ($app){
  return $app['twig']->render('error404.tpl', ['user' => $app['user']]);
});

$app->run();