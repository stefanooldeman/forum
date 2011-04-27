<?php
namespace Audicious\Ajax;

class User extends Executable {

	public function loginAction() {
		$post = $this->getHelper()->getPostRequest();
		$this->getHelper()->addData('username', null);
	}

}
