<?php
require __DIR__.'/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new Symfony\Component\ClassLoader\UniversalClassLoader();

$loader->registerNamespaces(array(
	'Symfony\Component' => __DIR__.'/src',
	'Audicious' => __DIR__.'/src'
));

$loader->register();

//Swift Mailer has its own auto loader..
require __DIR__.'/vendor/swiftmailer/lib/swift_required.php';