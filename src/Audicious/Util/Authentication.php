<?php
namespace Audicious\Util;

/**
 * Description of Authentication
 *
 * @author stefanooldeman
 */
class Authentication {

	private $session;

	private $namespace;

	public function hasIdentity() {
		$name = (string) $this->namespace;
		return isset($this->session->$name);
	}

	public function authenticate() {
		$this->session = $this->session->create($this->namespace);
	}

	public function forget() {
		$this->session->remove($this->namespace);
	}

	public function getValue($key) {
		return $this->session->$key;
	}

	public function setValue($key, $value) {
		$this->session->$key = $value;
	}

	/**
	 * when setting or getting values the namesace is needed so we always set it to the session storage
	 */
	public function configureSession($options) {
		$options = (object) $options;
		$this->namespace = $options->namespace;
		$this->session->setNamespace($this->namespace);
	}

	//Dependency Injection Method
	public function setSession($session) {
		$this->session = $session;
	}
}
