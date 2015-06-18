<?php
require_once("incs/database.php");
$accounts = Database::Accounts();
echo $accounts->getUserId("username", "stealarcher");
?>