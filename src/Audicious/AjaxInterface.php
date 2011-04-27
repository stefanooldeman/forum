<?php
namespace Audicious;

interface AjaxInterface {

	public function getPostRequest();

	public function getGetRequest();

	public function getData();

	public function getError();

}
