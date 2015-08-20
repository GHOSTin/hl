<?php namespace client;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Session\Session;
use domain\metrics;
use Swift_Message;
use Twig_SimpleFilter;
use config\general as conf;
use Silex\Provider\MonologServiceProvider;
use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;

$DS = DIRECTORY_SEPARATOR;
$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'client'))).$DS;
require_once($root."vendor/autoload.php");

date_default_timezone_set(conf::php_timezone);

$dbParams = array(
  'driver' => 'pdo_mysql',
  'host' => conf::db_host,
  'user' => conf::db_user,
  'password' => conf::db_password,
  'dbname' => conf::db_name,
  'charset' => 'utf8'
);

$app = new Application();
$app['salt'] = conf::authSalt;
$app['debug'] = (conf::status === 'development')? true: false;
$app['number'] = null;
$app['email_for_reply'] = conf::email_for_reply;
$app['email_for_registration'] = conf::email_for_registration;
$app['debt_limit'] = conf::debt_limit;

$config = new Configuration();
$driver = $config->newDefaultAnnotationDriver($root.$DS.'domain');
$config->setMetadataDriverImpl($driver);
$config->setProxyDir($root.$DS.'cache'.$DS.'proxy');
$config->setProxyNamespace('proxies');

$app['em'] = EntityManager::create($dbParams, $config);

if($app['debug']){
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates'];
}else{
  $cache = $root.$DS.'cache'.$DS.'twig'.$DS.'client'.$DS;
  $twig_conf = ['twig.path' => __DIR__.$DS.'templates',
                'twig.options' => ['cache' => $cache]];
}
$app->register(new TwigServiceProvider(), $twig_conf);

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

$app['Swift_Message'] = $app->factory(function($app){
  return Swift_Message::newInstance();
});
$app['domain\metrics'] = $app->factory(function($app){
  return new metrics();
});

$app['client\models\queries'] = function($app){
  return new models\queries($app['twig'], $app['em'], $app['number']);
};

$app['client\models\arrears'] = function($app){
  return new models\arrears($app['twig'], $app['em']);
};

$filter = new Twig_SimpleFilter('natsort', function (array $array) {
  natsort($array);
  return $array;
});
$app['twig']->addFilter($filter);

$app->register(new MonologServiceProvider(), array(
  'monolog.logfile' => $root.'cache'.$DS.'client.log',
  'monolog.level' => Logger::WARNING
));

$app['auth_log'] = function($app) use ($root, $DS){
  $formatter = new JsonFormatter();
  $logger = new Logger('auth');
  $stream = new StreamHandler($root.'cache'.$DS.'auth.log', Logger::INFO);
  $stream->setFormatter($formatter);
  $logger->pushHandler($stream);
  return $logger;
};

$app->before(function (Request $request, Application $app) {
  $app['session'] = new Session();
  $app['session']->start();
  if($app['session']->get('number')){
    $app['number'] = $app['em']->find('\domain\number', $app['session']->get('number'));
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
$app->get('/recovery/', 'client\controllers\default_page::recovery');
$app->post('/recovery/', 'client\controllers\default_page::recovery_password');

# settings
$app->get('/settings/', 'client\controllers\settings::default_page')->before($security);
$app->get('/settings/password/', 'client\controllers\settings::password_form')->before($security);
$app->post('/settings/password/', 'client\controllers\settings::change_password')->before($security);
$app->get('/settings/email/', 'client\controllers\settings::email_form')->before($security);
$app->post('/settings/email/', 'client\controllers\settings::change_email')->before($security);
$app->get('/settings/cellphone/', 'client\controllers\settings::cellphone_form')->before($security);
$app->post('/settings/cellphone/', 'client\controllers\settings::change_cellphone')->before($security);
$app->get('/settings/notification/', 'client\controllers\settings::notification_form')->before($security);
$app->post('/settings/notification/', 'client\controllers\settings::change_notification')->before($security);

# queries
$app->get('/queries/', 'client\controllers\queries::default_page')->before($security);
$app->get('/queries/request/', 'client\controllers\queries::request')->before($security);
$app->post('/queries/request/', 'client\controllers\queries::send_request')->before($security);


# accruals
$app->get('/accruals/', 'client\controllers\accruals::default_page')->before($security);

# metrics
$app->get('/metrics/', 'client\controllers\metrics::default_page')->before($security);
$app->post('/metrics/', 'client\controllers\metrics::send')->before($security);
$app->get('/metrics/history/', 'client\controllers\metrics::history')->before($security);

# registration
$app->get('/registration/', 'client\controllers\registration::default_page');
$app->post('/registration/', 'client\controllers\registration::send');

# arrears
$app->get('/arrears/', 'client\controllers\arrears::default_page');
$app->get('/arrears/streets/{id}/houses/', 'client\controllers\arrears::houses');
$app->get('/arrears/houses/{id}/flats/', 'client\controllers\arrears::flats');
$app->get('/arrears/houses/{id}/top/', 'client\controllers\arrears::top');
$app->get('/arrears/flats/{id}/', 'client\controllers\arrears::flat');

$app->error(function (NotFoundHttpException $e) use ($app){
  return $app['twig']->render('error404.tpl', ['number' => $app['number']]);
});

$app->run();