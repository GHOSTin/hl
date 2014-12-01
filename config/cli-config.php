<?php
use \Doctrine\ORM\Tools\Console\ConsoleRunner;
use \Doctrine\ORM\Tools\Setup;
use \Doctrine\ORM\EntityManager;
use \Symfony\Component\Console\Application;
use \main\conf;

$root = substr(__DIR__, 0, (strlen(__DIR__) - strlen(DIRECTORY_SEPARATOR.'config'))).DIRECTORY_SEPARATOR;
require_once($root."vendor/autoload.php");

$paths = array(
    $root.'domain/'
);
$isDevMode = (conf::status == 'development')? true: false;
if ($isDevMode) {
    $cache = new \Doctrine\Common\Cache\ArrayCache;
} else {
    $cache = new \Doctrine\Common\Cache\ArrayCache;
}
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'host'     => conf::db_host,
    'user'     => conf::db_user,
    'password' => conf::db_password,
    'dbname'   => conf::db_name,
    'charset'  => 'utf8'
);
$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, 'cache/proxy/');
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$em = EntityManager::create($dbParams, $config);
$em->getConnection()->getDatabasePlatform()
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