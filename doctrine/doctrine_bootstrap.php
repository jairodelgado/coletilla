<?php
require_once 'Common/ClassLoader.php';

$classLoader = new \Doctrine\Common\ClassLoader("Doctrine", $_SERVER["DOCUMENT_ROOT"] . '/coletilla/');
$classLoader->register();
$configuration = new \Doctrine\ORM\Configuration();
$configuration->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$configuration->setMetadataDriverImpl($configuration->newDefaultAnnotationDriver($_SERVER["DOCUMENT_ROOT"] . '/coletilla/doctrine/entities'));
$configuration->setQueryCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$configuration->setProxyDir($_SERVER["DOCUMENT_ROOT"] . '/coletilla/doctrine/proxies');
$configuration->setProxyNamespace('coletilla\proxies');
$configuration->setAutoGenerateProxyClasses(false);
$connectionOptions = array('driver' => db_driver, 'user' => db_user, 'password' => db_password, 'host' => db_host, 'port' => db_port, 'dbname' => db_name);

$db_manager = \Doctrine\ORM\EntityManager::create($connectionOptions, $configuration);
?>
