<?php

$user = R::findOne('user', 'key = ?', array($key));

if(!is_object($user)){
	$user = R::dispense("user");
	$user->key = $key;
	$user->address = $bitcoin->getaccountaddress($key);
	$user->balance = $bitcoin->getbalance($key);
	$user->updated = time();
	$user->created = time();
	$id = R::store($user);
	$user = R::load("user",$id);
}

include(APP."/profile/index.php");

?>