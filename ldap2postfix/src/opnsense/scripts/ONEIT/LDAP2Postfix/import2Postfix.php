<?php
use ONEIT\LDAP2Postfix\Api\ServiceController;

include("/usr/local/opnsense/mvc/script/load_phalcon.php");


if ($argv[1] == "start" || $argv[1] == "restart" || $argv[1] == "reload" || $argv[1] == "import")
{
  $controller = new ServiceController();
  $controller->importAction();
}

?>
