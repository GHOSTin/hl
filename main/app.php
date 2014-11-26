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

$app->error(function (NotFoundHttpException $e, $code) use ($app){
    return $app['twig']->render('error404.tpl', ['user' => $app['user'],
                                'menu' => null, 'hot_menu' => null]);
});
$app->run();