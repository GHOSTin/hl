<?php namespace main;


use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Silex\Provider\SwiftmailerServiceProvider;

$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen(DIRECTORY_SEPARATOR.'main'))).DIRECTORY_SEPARATOR;
require_once($root."vendor/autoload.php");

$app = new Application();
if(conf::status === 'development')
  $app['debug'] = true;
$app['user'] = null;

$paths = array(__DIR__);
$isDevMode = (conf::status == 'development')? true: false;
$dbParams = array(
  'driver'   => 'pdo_mysql',
  'host'     => conf::db_host,
  'user'     => conf::db_user,
  'password' => conf::db_password,
  'dbname'   => conf::db_name,
  'charset'  => 'utf8'
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$app['em'] = EntityManager::create($dbParams, $config);
$app['\main\models\number'] = function($app){
  return new \main\models\number($app);
};
$app['\main\models\query'] = function($app){
  return new \main\models\query($app);
};
$app['\main\models\report_query'] = function($app){
  return new \main\models\report_query($app);
};
$app->register(new TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/templates',
));

$app->before(function (Request $request, Application $app) {
  session_start();
  if(isset($_SESSION['user'])){
    $app['user'] = $app['em']->find('\domain\user', $_SESSION['user']);
  }
}, Application::EARLY_EVENT);
#default_page
$app->get('/', 'main\\controllers\\default_page::default_page');
$app->post('/login/', 'main\\controllers\\default_page::login');
$app->get('/logout/', 'main\\controllers\\default_page::logout');
$app->get('/about/', 'main\\controllers\\default_page::about');
# profile
$app->get('/profile/', 'main\\controllers\\profile::default_page');
$app->get('/profile/get_userinfo', 'main\\controllers\\profile::get_user_info');
$app->put('/profile/update_telephone',
          'main\\controllers\\profile::update_telephone');
$app->put('/profile/update_cellphone',
          'main\\controllers\\profile::update_cellphone');
$app->get('/profile/update_password',
          'main\\controllers\\profile::update_password');
$app->get('/profile/get_notification_center_content',
          'main\\controllers\\profile::get_notification_center_content');
# api
$app->get('/api/get_chat_options', 'main\\controllers\\api::get_chat_options');
# error
$app->get('/error/show_dialog', 'main\\controllers\\error::show_dialog');
$app->get('/error/send_error', 'main\\controllers\\error::send_error');
$app->get('/error/delete_error', 'main\\controllers\\error::delete_error');
$app->get('/error/', 'main\\controllers\\error::default_page');
# works
$app->get('/works/', 'main\\controllers\\works::default_page');
$app->get('/works/get_workgroup_content',
          'main\\controllers\\works::get_workgroup_content');
$app->get('/works/get_dialog_rename_workgroup',
          'main\\controllers\\works::get_dialog_rename_workgroup');
$app->get('/works/rename_workgroup',
          'main\\controllers\\works::rename_workgroup');
$app->get('/works/get_dialog_add_work',
          'main\\controllers\\works::get_dialog_add_work');
$app->get('/works/add_work', 'main\\controllers\\works::add_work');
$app->get('/works/get_dialog_exclude_work',
          'main\\controllers\\works::get_dialog_exclude_work');
$app->get('/works/exclude_work', 'main\\controllers\\works::exclude_work');
$app->get('/works/get_dialog_create_workgroup',
          'main\\controllers\\works::get_dialog_create_workgroup');
$app->get('/works/create_workgroup',
          'main\\controllers\\works::create_workgroup');
$app->get('/works/get_work_content',
          'main\\controllers\\works::get_work_content');
$app->get('/works/get_dialog_rename_work',
          'main\\controllers\\works::get_dialog_rename_work');
$app->get('/works/rename_work',
          'main\\controllers\\works::rename_work');
$app->get('/works/get_dialog_create_work',
          'main\\controllers\\works::get_dialog_create_work');
$app->get('/works/create_work',
          'main\\controllers\\works::create_work');
# user
$app->get('/user/',
          'main\\controllers\\users::default_page');
$app->get('/user/get_user_letter',
          'main\\controllers\\users::get_user_letter');
$app->get('/user/logs', 'main\\controllers\\users::logs');
$app->get('/user/get_dialog_clear_logs',
          'main\\controllers\\users::get_dialog_clear_logs');
$app->get('/user/clear_logs', 'main\\controllers\\users::clear_logs');
$app->get('/user/get_user_content',
          'main\\controllers\\users::get_user_content');
$app->get('/user/get_user_information',
          'main\\controllers\\users::get_user_information');
$app->get('/user/get_dialog_edit_fio',
          'main\\controllers\\users::get_dialog_edit_fio');
$app->get('/user/update_fio',
          'main\\controllers\\users::update_fio');
$app->get('/user/get_dialog_edit_login',
          'main\\controllers\\users::get_dialog_edit_login');
$app->get('/user/update_login', 'main\\controllers\\users::update_login');
$app->get('/user/get_dialog_edit_user_status',
          'main\\controllers\\users::get_dialog_edit_user_status');
$app->get('/user/update_user_status',
          'main\\controllers\\users::update_user_status');
$app->get('/user/get_dialog_edit_password',
          'main\\controllers\\users::get_dialog_edit_password');
$app->get('/user/update_password', 'main\\controllers\\users::update_password');
$app->get('/user/get_dialog_create_user',
          'main\\controllers\\users::get_dialog_create_user');
$app->get('/user/create_user', 'main\\controllers\\users::create_user');
$app->get('/user/get_group_letters',
          'main\\controllers\\users::get_group_letters');
$app->get('/user/get_user_letters',
          'main\\controllers\\users::get_user_letters');
$app->get('/user/get_group_letter',
          'main\\controllers\\users::get_group_letter');
$app->get('/user/get_group_content',
          'main\\controllers\\users::get_group_content');
$app->get('/user/get_group_users',
          'main\\controllers\\users::get_group_users');
$app->get('/user/get_dialog_edit_group_name',
          'main\\controllers\\users::get_dialog_edit_group_name');
$app->get('/user/update_group_name',
          'main\\controllers\\users::update_group_name');
$app->get('/user/get_group_profile',
          'main\\controllers\\users::get_group_profile');
$app->get('/user/get_dialog_add_user',
          'main\\controllers\\users::get_dialog_add_user');
$app->get('/user/add_user', 'main\\controllers\\users::add_user');
$app->get('/user/get_dialog_exclude_user',
          'main\\controllers\\users::get_dialog_exclude_user');
$app->get('/user/exclude_user', 'main\\controllers\\users::exclude_user');
$app->get('/user/get_dialog_create_group',
          'main\\controllers\\users::get_dialog_create_group');
$app->get('/user/create_group', 'main\\controllers\\users::create_group');
$app->get('/user/get_user_profiles',
          'main\\controllers\\users::get_user_profiles');
$app->get('/user/get_profile_content',
          'main\\controllers\\users::get_profile_content');
$app->get('/user/update_rule', 'main\\controllers\\users::update_rule');
$app->get('/user/get_dialog_delete_profile',
          'main\\controllers\\users::get_dialog_delete_profile');
$app->get('/user/delete_profile', 'main\\controllers\\users::delete_profile');
$app->get('/user/get_dialog_add_profile',
          'main\\controllers\\users::get_dialog_add_profile');
$app->get('/user/add_profile', 'main\\controllers\\users::add_profile');
$app->get('/user/get_restriction_content',
          'main\\controllers\\users::get_restriction_content');
$app->get('/user/update_restriction',
          'main\\controllers\\users::update_restriction');
# metrics
$app->get('/metrics/', 'main\\controllers\\metrics::default_page');
$app->get('/metrics/archive/', 'main\\controllers\\metrics::archive');
$app->get('/metrics/archive/set_date', 'main\\controllers\\metrics::set_date');
$app->post('/metrics/remove_metrics',
          'main\\controllers\\metrics::remove_metrics');
# number
$app->get('/number/', 'main\\controllers\\numbers::default_page');
$app->get('/number/get_street_content',
          'main\\controllers\\numbers::get_street_content');
$app->get('/number/get_house_content',
          'main\\controllers\\numbers::get_house_content');
$app->get('/number/get_number_content',
          'main\\controllers\\numbers::get_number_content');
$app->get('/number/get_dialog_edit_number_fio',
          'main\\controllers\\numbers::get_dialog_edit_number_fio');
$app->get('/number/update_number_fio',
          'main\\controllers\\numbers::update_number_fio');
$app->get('/number/get_dialog_edit_number',
          'main\\controllers\\numbers::get_dialog_edit_number');
$app->get('/number/update_number', 'main\\controllers\\numbers::update_number');
$app->get('/number/get_dialog_edit_password',
          'main\\controllers\\numbers::get_dialog_edit_password');
$app->get('/number/update_number_password',
          'main\\controllers\\numbers::update_number_password');
$app->get('/number/get_dialog_edit_number_telephone',
          'main\\controllers\\numbers::get_dialog_edit_number_telephone');
$app->get('/number/update_number_telephone',
          'main\\controllers\\numbers::update_number_telephone');
$app->get('/number/get_dialog_edit_number_cellphone',
          'main\\controllers\\numbers::get_dialog_edit_number_cellphone');
$app->get('/number/update_number_cellphone',
          'main\\controllers\\numbers::update_number_cellphone');
$app->get('/number/get_dialog_edit_number_email',
          'main\\controllers\\numbers::get_dialog_edit_number_email');
$app->get('/number/update_number_email',
          'main\\controllers\\numbers::update_number_email');
$app->get('/number/get_dialog_edit_department',
          'main\\controllers\\numbers::get_dialog_edit_department');
$app->get('/number/edit_department',
          'main\\controllers\\numbers::edit_department');
$app->get('/number/query_of_house',
          'main\\controllers\\numbers::query_of_house');
$app->get('/number/query_of_number',
          'main\\controllers\\numbers::query_of_number');
$app->get('/number/accruals', 'main\\controllers\\numbers::accruals');
$app->get('/number/contact_info', 'main\\controllers\\numbers::contact_info');
# export
$app->get('/export/', 'main\\controllers\\export::default_page');
$app->get('/export/get_dialog_export_numbers',
          'main\\controllers\\export::get_dialog_export_numbers');
$app->get('/export/export_numbers',
          'main\\controllers\\export::export_numbers');
# query
$app->get('/query/', 'main\\controllers\\queries::default_page');
$app->get('/query/get_day', 'main\\controllers\\queries::get_day');
$app->get('/query/get_timeline', 'main\\controllers\\queries::get_timeline');
$app->get('/query/get_search', 'main\\controllers\\queries::get_search');
$app->get('/query/get_search_result',
          'main\\controllers\\queries::get_search_result');
$app->get('/query/get_query_content',
          'main\\controllers\\queries::get_query_content');
$app->get('/query/get_query_title',
          'main\\controllers\\queries::get_query_title');
$app->get('/query/get_documents', 'main\\controllers\\queries::get_documents');
$app->get('/query/print_query', 'main\\controllers\\queries::print_query');
$app->get('/query/get_query_numbers',
          'main\\controllers\\queries::get_query_numbers');
$app->get('/query/get_query_users',
          'main\\controllers\\queries::get_query_users');
$app->get('/query/get_query_works',
          'main\\controllers\\queries::get_query_works');
$app->get('/query/get_query_comments',
          'main\\controllers\\queries::get_query_comments');
$app->get('/query/get_dialog_edit_payment_status',
          'main\\controllers\\queries::get_dialog_edit_payment_status');
$app->get('/query/update_payment_status',
          'main\\controllers\\queries::update_payment_status');
$app->get('/query/get_dialog_edit_work_type',
          'main\\controllers\\queries::get_dialog_edit_work_type');
$app->get('/query/update_work_type',
          'main\\controllers\\queries::update_work_type');
$app->get('/query/get_dialog_edit_warning_status',
          'main\\controllers\\queries::get_dialog_edit_warning_status');
$app->get('/query/update_warning_status',
          'main\\controllers\\queries::update_warning_status');
$app->get('/query/get_dialog_edit_description',
          'main\\controllers\\queries::get_dialog_edit_description');
$app->get('/query/update_description',
          'main\\controllers\\queries::update_description');
$app->get('/query/get_dialog_edit_reason',
          'main\\controllers\\queries::get_dialog_edit_reason');
$app->get('/query/update_reason', 'main\\controllers\\queries::update_reason');
$app->get('/query/get_dialog_edit_contact_information',
          'main\\controllers\\queries::get_dialog_edit_contact_information');
$app->get('/query/update_contact_information',
          'main\\controllers\\queries::update_contact_information');
$app->get('/query/get_dialog_reopen_query',
          'main\\controllers\\queries::get_dialog_reopen_query');
$app->get('/query/reopen_query', 'main\\controllers\\queries::reopen_query');
$app->get('/query/get_dialog_reclose_query',
          'main\\controllers\\queries::get_dialog_reclose_query');
$app->get('/query/reclose_query', 'main\\controllers\\queries::reclose_query');
$app->get('/query/get_dialog_close_query',
          'main\\controllers\\queries::get_dialog_close_query');
$app->get('/query/close_query', 'main\\controllers\\queries::close_query');
$app->get('/query/get_dialog_to_working_query',
          'main\\controllers\\queries::get_dialog_to_working_query');
$app->get('/query/to_working_query',
          'main\\controllers\\queries::to_working_query');
$app->get('/query/get_dialog_add_user',
          'main\\controllers\\queries::get_dialog_add_user');
$app->get('/query/get_user_options',
          'main\\controllers\\queries::get_user_options');
$app->get('/query/add_user',
          'main\\controllers\\queries::add_user');
$app->get('/query/get_dialog_remove_user',
          'main\\controllers\\queries::get_dialog_remove_user');
$app->get('/query/remove_user', 'main\\controllers\\queries::remove_user');
$app->get('/query/get_dialog_add_comment',
          'main\\controllers\\queries::get_dialog_add_comment');
$app->get('/query/add_comment', 'main\\controllers\\queries::add_comment');
$app->get('/query/get_dialog_add_work',
          'main\\controllers\\queries::get_dialog_add_work');
$app->get('/query/add_work', 'main\\controllers\\queries::add_work');
$app->get('/query/get_dialog_remove_work',
          'main\\controllers\\queries::get_dialog_remove_work');
$app->get('/query/remove_work', 'main\\controllers\\queries::remove_work');
$app->get('/query/clear_filters', 'main\\controllers\\queries::clear_filters');
$app->get('/query/set_status', 'main\\controllers\\queries::set_status');
$app->get('/query/set_department',
          'main\\controllers\\queries::set_department');
$app->get('/query/set_street', 'main\\controllers\\queries::set_street');
$app->get('/query/set_house', 'main\\controllers\\queries::set_house');
$app->get('/query/set_work_type', 'main\\controllers\\queries::set_work_type');
$app->get('/query/get_dialog_change_initiator',
          'main\\controllers\\queries::get_dialog_change_initiator');
$app->get('/query/get_houses', 'main\\controllers\\queries::get_houses');
$app->get('/query/get_numbers', 'main\\controllers\\queries::get_numbers');
$app->get('/query/change_initiator',
          'main\\controllers\\queries::change_initiator');
$app->get('/query/get_dialog_create_query',
          'main\\controllers\\queries::get_dialog_create_query');
$app->get('/query/get_dialog_initiator',
          'main\\controllers\\queries::get_dialog_initiator');
$app->get('/query/get_initiator', 'main\\controllers\\queries::get_initiator');
$app->get('/query/create_query', 'main\\controllers\\queries::create_query');
# report
$app->get('/report/', 'main\\controllers\\reports::default_page');
$app->get('/report/get_query_reports',
          'main\\controllers\\reports::get_query_reports');
$app->get('/report/clear_filter_query',
          'main\\controllers\\reports::clear_filter_query');
$app->get('/report/set_time_begin',
          'main\\controllers\\reports::set_time_begin');
$app->get('/report/set_time_end', 'main\\controllers\\reports::set_time_end');
$app->get('/report/set_filter_query_status',
          'main\\controllers\\reports::set_filter_query_status');
$app->get('/report/set_filter_query_department',
          'main\\controllers\\reports::set_filter_query_department');
$app->get('/report/set_filter_query_worktype',
          'main\\controllers\\reports::set_filter_query_worktype');
$app->get('/report/set_filter_query_street',
          'main\\controllers\\reports::set_filter_query_street');
$app->get('/report/set_filter_query_house',
          'main\\controllers\\reports::set_filter_query_house');
$app->get('/report/report_query_one',
          'main\\controllers\\reports::report_query_one');
$app->get('/report/report_query_one_xls',
          'main\\controllers\\reports::report_query_one_xls');
# tasks
$app->get('/task/', 'main\\controllers\\task::default_page');
$app->get('/task/show_active_tasks',
          'main\\controllers\\task::show_active_tasks');
$app->get('/task/show_finished_tasks',
          'main\\controllers\\task::show_finished_tasks');
$app->get('/task/get_task_content',
          'main\\controllers\\task::get_task_content');
$app->get('/task/send_task_comment',
          'main\\controllers\\task::send_task_comment');
$app->get('/task/edit_task_content',
          'main\\controllers\\task::edit_task_content');
$app->get('/task/save_task_content',
          'main\\controllers\\task::save_task_content');
$app->get('/task/get_dialog_create_task',
          'main\\controllers\\task::get_dialog_create_task');
$app->get('/task/add_task', 'main\\controllers\\task::add_task');
$app->get('/task/get_dialog_close_task',
          'main\\controllers\\task::get_dialog_close_task');
$app->get('/task/close_task', 'main\\controllers\\task::close_task');

$app->error(function (NotFoundHttpException $e, $code) use ($app){
    return $app['twig']->render('error404.tpl', ['user' => $app['user'],
                                'menu' => null, 'hot_menu' => null]);
});
$app->run();