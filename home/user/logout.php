<?php
require_once dirname(__FILE__).'/php_libs/funcs.php';

$_SESSION = array();

if(isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time()-86400, '/');
}

session_destroy();

if(isset($_GET['refpg'])){
	jump(_DOMAIN.$_GET['refpg']);
}else{
	jump('./login.php');
}
?>
