<?php

$content = array(
	"title" => $settings["name"],
	"profile" => true
);

$user = R::findOne('user', 'key = ?', array($key));

if(is_object($user)){
	if($user->updated + 60*15 < time()){
		$user->address = $bitcoin->getaccountaddress($key);
		$user->balance = $bitcoin->getbalance($key);
		$user->updated = time();
		R::store($user);
	}
	$content["user"]=$user->export();
}

$content = array_merge($content,$settings);
echo $m->render("pages/profile", $content);

?>