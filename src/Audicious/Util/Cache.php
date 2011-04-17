<?php
namespace Audicious\Util;

/**
 * Description of Cache
 *
 * @author stefanooldeman
 */
class Cache {
	
	private $lifetime;
	private $dir;

	public function setLifetime($lifetime) {
		$this->lifetime = $lifetime;
	}

}