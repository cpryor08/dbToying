<?php
class Session
{
	protected $id;
	public function Initialize($user)
	{
		if (session_id() == '') {
			session_start();
			//session_id(0);
			$_SESSION['user'] = $user;
		} else {
			$user = $_SESSION['user'];
		}
		$this->id = session_id();
	}
}
?>