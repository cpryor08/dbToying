<?php
define("WORKING_DIRECTORY", getcwd());
include_once(WORKING_DIRECTORY."\\incs\\session.php");
class User extends Session
{
	protected $logged = false;
	public function __construct()
	{
		$this->Initialize($this);
	}
}
?>