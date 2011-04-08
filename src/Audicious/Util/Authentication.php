<?php
namespace Audicious\Util;

/**
 * Description of Authentication
 *
 * @author stefanooldeman
 */
class Authentication {
	
	private $sessionStorage;

	public $namespace = 'user';

	public function login() {
		$userSess = $this->sessionStorage->factory($this->namespace);
	}

	public function logout() {
		$this->sessionStorage->remove($this->namespace);
	}

	//Dependency Injection Method
	public function setSessionStorage($store) {
		$this->sessionStorage = $store;
	}
}
