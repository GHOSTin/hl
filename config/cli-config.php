<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Symfony\Component\Console\Application;
use config\general as conf;

$DS = DIRECTORY_SEPARATOR;

$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen($DS.'config'))).$DS;
require_once($root."vendor".$DS."autoload.php");

$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host'     => conf::db_host,
    'user'     => conf::db_user,
    'password' => conf::db_password,
    'dbname'   => conf::db_name,
    'charset'  => 'utf8'
);

$config = new Configuration();
$driver = $config->newDefaultAnnotationDriver($root.$DS.'domain');
$config->setMetadataDriverImpl($driver);
$config->setProxyDir($root.$DS.'cache'.$DS.'proxy');
$config->setProxyNamespace('proxies');

$em = EntityManager::create($dbParams, $config);

$em->getConnection()
   ->getDatabasePlatform()
   ->registerDoctrineTypeMapping('enum', 'string');

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));

$cli = new Application('Doctrine Command Line Interface', \Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
ConsoleRunner::addCommands($cli);
$cli->run();