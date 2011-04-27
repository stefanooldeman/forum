<?php
use Symfony\Component\DependencyInjection as DI;

$sc = new DI\ContainerBuilder();

$cache = $sc->register('cache_class', 'Audicious\Util\Cache')
	->setProperties(array(
		'lifetime' => (7 * 24 * 60 * 60),
		'dir' => __DIR__.'../app/cache/'
	));

$sc->register('user_class', 'Audicious\Model\User')
	->addMethodCall('setCache', array($sc->get('cache_class'), $cache->getProperties()));


$sc->register('session_class', 'Audicious\Util\Session')
//	->addMethodCall('start', array())
	->addArgument(array(
		'name' => 'AudiciousSession',
		'cookie_secure' => 1,
		'cookie_httponly' => 1,
		'cookie_path' => '/web/',
		'cookie_lifetime' => 0,
	));

$authOptions = array('namespace' => 'user');
$sc->register('auth_class', 'Audicious\Util\Authentication')
	->addMethodCall('setSession', array($sc->get('session_class')))
	->setConfigurator($sc->get('auth_class')->configureSession($authOptions));


