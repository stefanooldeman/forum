<?php
namespace Audicious\Util\Session;

use Audicious\Util\Session as Session;
use \Audicious\Util\SessionException as SessionException;
/**
 * Description of SessionStorage
 *
 * @author stefanooldeman
 */
class Storage {

	protected static $prefix;

	protected $namespace;

	protected static $registry = array();

	protected function loadPrefix() {
		if(strlen(self::$prefix) < 1) {
			self::$prefix = $_SERVER['HTTP_HOST'];
		}
	}

	public function __construct() {
		$this->loadPrefix();
		if(Session::isStarted()) {
			//first namespace that is been stored in the session
			if(empty($_SESSION[self::$prefix])) {
				$_SESSION[self::$prefix] = array();
				$_SESSION[self::$prefix]['_registry'] = array();
			}
		}
	}

	/**
	 * set the namespace to work with to prevent creating it.
	 */
	public function setNamespace($namespace) {
		$this->namespace = $namespace;
	}

	public static function get($namespace) {
		if(!isset($_SESSION[self::$prefix][$namespace])) {
			trigger_error('requested namespace in ' . __METHOD__ . ' returned empty value because the namespace was not set ', \E_USER_NOTICE);
			return null;
		}
		return (object) $_SESSION[self::$prefix][$namespace];
	}

	public function create($namespace = 'Default') {
		$this->loadPrefix();

		if($namespace == '' || $namespace[0] == '_') {
			throw new Exception('invalid namespace name');
		}

		$this->namespace = $namespace;
		if(in_array($this->namespace, self::$registry)) {
			throw new SessionException('namespace already in regestry and in use');
		}

		if(!isset($_SESSION[self::$prefix][$this->namespace])) {

			$_SESSION[self::$prefix][$this->namespace] = array(
				'namespace' => $this->namespace,
				'created_at' => date('r'),
				'data' => array()
			);

			$_SESSION[self::$prefix]['_registry'][$this->namespace] = true;
		}
		self::$registry[] = $this->namespace;

		return $this;
	}

	public function remove($namespace) {
		if(!isset($_SESSION[self::$prefix][$namespace])) {
			return;
		}
		unset($_SESSION[self::$prefix][$namespace]);
	}

	public function __unset($name) {
		unset($_SESSION[self::$prefix][$this->namespace]['data'][$name]);
	}

	public function & __get($name) {
		return $_SESSION[self::$prefix][$this->namespace]['data'][$name];
	}

	public function __set($key, $value) {
		$_SESSION[self::$prefix][$this->namespace]['data'][$key] = $value;
	}

	public function __isset($namespace) {
		return isset($_SESSION[self::$prefix][$namespace]);
	}

}
