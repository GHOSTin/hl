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
$app->error(function (NotFoundHttpException $e, $code) use ($app){
    return $app['twig']->render('error404.tpl', ['user' => $app['user'],
                                'menu' => null, 'hot_menu' => null]);
});
$app->run();