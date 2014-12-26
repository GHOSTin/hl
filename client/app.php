<?php namespace client;

use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \Silex\Application;
use \Silex\Provider\TwigServiceProvider;
use \Silex\Provider\SwiftmailerServiceProvider;
use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
$DS = DIRECTORY_SEPARATOR;
$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'client'))).$DS;
require_once($root."vendor/autoload.php");


$app = new Application();
$app['debug'] = (conf::status === 'development')? true: false;
$app['number'] = null;
$app['salt'] = conf::authSalt;
date_default_timezone_set(conf::php_timezone);

$dbParams = array(
  'driver'   => 'pdo_mysql',
  'host'     => conf::db_host,
  'user'     => conf::db_user,
  'password' => conf::db_password,
  'dbname'   => conf::db_name,
  'charset'  => 'utf8'
);

$config = Setup::createAnnotationMetadataConfiguration([__DIR__], $app['debug']);
$app['em'] = EntityManager::create($dbParams, $config);

if($app['debug']){
  $twig_conf = ['twig.path' => __DIR__.'/templates'];
}else{
  $cache = $root.$DS.'cache'.$DS.'twig'.$DS.'client'.$DS;
  $twig_conf = ['twig.path' => __DIR__.'/templates',
                'twig.options' => ['cache' => $cache]];
}
$app->register(new TwigServiceProvider(), $twig_conf);

$app->before(function (Request $request, Application $app) {
  session_start();
  if(isset($_SESSION['number'])){
    $app['number'] = $app['em']->find('\domain\number', $_SESSION['number']);
  }
}, Application::EARLY_EVENT);

$security = function(Request $request, Application $app){
  if(is_null($app['number']))
    throw new NotFoundHttpException();
};
# default_page
$app->get('/', 'client\controllers\default_page::default_page');
$app->post('/login/', 'client\controllers\default_page::login');
$app->get('/logout/', 'client\controllers\default_page::logout')->before($security);

# settings
$app->get('/settings/', 'client\controllers\settings::default_page')->before($security);
$app->post('/settings/change_password/', 'client\controllers\settings::change_password')->before($security);

# queries
$app->get('/queries/', 'client\controllers\queries::default_page')->before($security);

# accruals
$app->get('/accruals/', 'client\controllers\accruals::default_page')->before($security);

$app->error(function (NotFoundHttpException $e) use ($app){
  return $app['twig']->render('error404.tpl', ['number' => $app['number']]);
});
$app->run();