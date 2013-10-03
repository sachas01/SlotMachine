<?php

$address = getVar("address");
$ammount = getVar("ammount");

$user = R::findOne('user', 'key = ?', array($key));

if(is_object($user)){

	$balance = $bitcoin->getbalance($key);

	if($balance < $ammount)
		$ammount = $balance;

	if($ammount > 0)
		$bitcoin->sendtoaddress($address,$ammount);

	$balance = $balance - $ammount;

	$user->updated = time();
	$user->balance = $balance;
	R::store($user);
}

include(APP."/profile/index.php");

?>