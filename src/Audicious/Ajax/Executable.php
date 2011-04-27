<?php
namespace Audicious\Ajax;

use Audicious\AjaxInterface as AjaxInterface;

abstract class Executable {

	public $ajaxHelper;

	public function getHelper() {
		return $this->ajaxHelper;
	}

	public function setHelper(AjaxInterface $helper) {
		$this->ajaxHelper = $helper;
	}
}
