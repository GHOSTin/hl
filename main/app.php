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

$app->error(function (NotFoundHttpException $e, $code) use ($app){
    return $app['twig']->render('error404.tpl', ['user' => $app['user']]);
});
$app->run();