<?php
namespace Audicious\Model;

/**
 * Description of User
 *
 * @author stefanooldeman
 */
class User {

	public $cache;

	public function __construct() {}

	public function setCache($cache, $options) {
		$this->cache = $cache;
		$this->cache->setLifetime( (integer) $options['lifetime']);
	}


}
