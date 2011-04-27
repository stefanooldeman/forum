<?php
namespace Audicious\Ajax;

use Audicious\AjaxInterface as AjaxInterface;

class Helper implements AjaxInterface {

	const REQUEST_POST = 'POST';
	const REQUEST_GET = 'GET';

	private $data = array();

	private $error = 0;

	public function execute($className, $methodName) {

		if(class_exists($className) && method_exists($className, $methodName)) {
			$object = new $className();

			if(($object instanceof Executable) == false) {
				throw new RequestException('requested ajax not instance of an ajax executable', 2);
			}

			$object->setHelper($this);
			//call the method wich then puts data into this same object
			$object->$methodName();

		} else {
			throw new RequestException('requested ajax request was not found', 1);
			$this->error = 1;
		}
	}

	public function getGetRequest() {
		$this->verifyRequestMethod(self::REQUEST_GET);

		return (object) $_GET;
	}

	public function getPostRequest() {
		$this->verifyRequestMethod(self::REQUEST_POST);

		return (object) $_POST;
	}

	public function getData() {
		return $this->data;
	}

	public function getError() {
		return $this->error;
	}

	private function verifyRequestMethod($request) {

		if($_SERVER['REQUEST_METHOD'] !== $request) {
			throw new RequestException('requested method "' . $request . '" did not match actual server request_method ("' . $_SERVER['REQUEST_METHOD'] . '")');
		}
	}

	public function addData($key, $value, $forceWrite = false) {
		if(array_key_exists($key, $this->data)) {

			if($forceWrte) {
				$this->data[$key] = $value;
			} else {

				if(!is_array($this->data[$key])) {
					$this->data[$key] = (array) $this->data[$key];
				}
				array_push($value, $this->data[$key]);
			}
		} else {

			$this->data[$key] = $value;
		}
	}

}

class RequestException extends \Exception {}
