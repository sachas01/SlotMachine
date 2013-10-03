<?php

$settings = array(
	"name" => "Satoshi Jackpot",
	"url" => "http://openmanager.net",
	"rate" => ".9",
	"identifier" => "60c3da7e-1b03-5f57-a931-68624bed332d",
	"password" => "8qI8JMOa37",
	"secondPassword" => "73aOMJ8Iq8"
);

$sub = array_shift(explode(".",$_SERVER['HTTP_HOST']));
$path = explode("?",$_SERVER['REQUEST_URI']);
$var = explode("/",$path[0]);

require_once(VENDOR."/Mustache/Autoloader.php");
require_once(VENDOR."/jsonRPC/jsonRPCClient.php");
require_once(VENDOR."/storage/rb.php");


R::setup('sqlite:'.BASE.'/data.sqlite','root','dayofdying');

Mustache_Autoloader::register();
$m = new Mustache_Engine(
	array(
		'loader' => new Mustache_Loader_FilesystemLoader(VIEWS),
		'partials_loader' => new Mustache_Loader_FilesystemLoader(VIEWS."/partials")
	)
);

$bitcoin = new jsonRPCClient('https://'.$settings["identifier"].':'.$settings["password"].'@blockchain.info:443');
$bitcoin->walletpassphrase($settings["secondPassword"],60);

require_once(BASE."/functions.php");
require_once(BASE."/jackpot.php");
require_once(BASE."/user.php");

?>