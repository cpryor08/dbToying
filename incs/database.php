<?php
define("WORKING_DIRECTORY", getcwd());
include_once(WORKING_DIRECTORY."\\incs\\settings.php");

class Database
{
	protected $tableName = "table";
	protected $connection;
	protected $database;
	public function __construct()
	{
		$this->tableName = strtolower(get_class($this));
		$this->connection = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
		$this->database = mysqli_select_db($this->connection, MYSQL_DB);
	}
	public function __call($name, $arguments)
	{
		$opt = strtolower(substr($name, 0, 3));
		switch($opt)
		{
			case "get":
				{
					$col = strtolower(substr($name, 3, strlen($name)));
					$where = mysqli_real_escape_string($this->connection, $arguments[0]);
					$equals = mysqli_real_escape_string($this->connection, $arguments[1]);
					$query = mysqli_query($this->connection, "SELECT `".$col."` FROM `".$this->tableName."` WHERE `".$where."`='".$equals."' LIMIT 1");
					$query = mysqli_fetch_assoc($query);
					return $query[$col];
				}
			case "set":
				{
					$col = strtolower(substr($name, 3, strlen($name)));
					$val = mysqli_real_escape_string($this->connection, $arguments[0]);
					$where = mysqli_real_escape_string($this->connection, $arguments[1]);
					$equals = mysqli_real_escape_string($this->connection, $arguments[2]);
					return mysqli_query($this->connection, "UPDATE `".$this->tableName."` SET `".$col."`='".$val."' WHERE `".$where."`='".$equals."'");
				}
			default:
				{
					throw new Exception("Tried to use __call without using a get/set in database.php");
					break;
				}
		}
	}
	public static function __callStatic($name, $arguments)
	{
		$className = strtolower($name);
		$con = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
		$db = mysqli_select_db($con, MYSQL_DB);
		if (mysqli_query($con, "DESCRIBE ".$className))
		{
			$path = WORKING_DIRECTORY."\\incs\\".$className.".php";
			if (!file_exists($path))
			{
				$code = "class $className extends Database { }";
				eval($code);
			}
		} else {
			throw new Exception("Table with the name ".$className." does not exist.");
		}
		return new $className();
	}
	public function query($query)
	{
		return mysqli_query($this->connection, $query);
	}
	public function fetch_assoc($query)
	{
		return mysqli_fetch_assoc($query);
	}
	public function num_rows($query)
	{
		return mysqli_num_rows($query);
	}
	public function __sleep()
	{
		mysqli_close($this->connection);
	}
}
?>