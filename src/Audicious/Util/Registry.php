<?php

//namespace Audicious\Util;

class Registry {
	protected static $registry;

	/**
	 * @param string $classAlias
	 */
	public static function get($classAlias) {
		if(array_key_exists($classAlias, self::$registry)) {
			return self::$registry[$classAlias];
		}
		throw new Exception('could not find requested class "' . $classAlias . '" in registry');
	}

	public static function set($alias, $object) {
		self::$registry[$alias] = $object;
	}
}
