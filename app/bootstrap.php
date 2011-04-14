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

$user = $sc->get('user_class');


$sc->register('session_class', 'Audicious\Util\Session')
	->addMethodCall('start', array())
	->addArgument(array(
		'name' => 'AudiciousSession',
		'cookie_secure' => 1,
		'cookie_httponly' => 1,
		'cookie_path' => '/web/',
		'cookie_lifetime' => 0,
	));

$session = $sc->get('session_class');

$sc->register('aut_class', 'Audicious\Util\Authentication')
	->addMethodCall('setSessionStorage', array($sc->get('session_class')));

//EXPIRIMENTAL
//namspaces are hatius when using singelton like these
//lets over think it and use this with an include
//Or use namespace each time we call this static methods.. (not funny)
//use Audicious\Util\Registry as Registry;
require '../src/Audicious/Util/Registry.php';
Registry::set('service', $sc);
