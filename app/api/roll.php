<?php

cors();

$uuid = getVar('uuid');
$id = getVar('id');

$user = R::findOne('user', 'key = ?', array($key));

if(!is_object($user)){
	$user = R::dispense("user");
	$user->key = $key;
	$user->balance = 0;
	$user->updated = time();
	$user->created = time();
	$id = R::store($user);
	$user = R::load("user",$id);
}


$roll = R::dispense("roll");
$roll->uuid = $uuid;
$roll->first = rand(1,10);
$roll->second = rand(1,10);
$roll->third = rand(1,10);
$roll->fourth = rand(1,10);
$roll->fifth = rand(1,10);
$roll->time = time();
$roll->key = $key;
R::store($roll);

$data = array(
	"status"=>"success",
	"won"=>false,
	"numbers"=>array(
		$roll->first,
		$roll->second,
		$roll->third,
		$roll->fourth,
		$roll->fifth
		)
	);

$jackpotSlots = $bitcoin->getbalance("slots")*.9;

if($user->balance > .001){
	$bitcoin->walletpassphrase($settings["secondPassword"],60);
	$bitcoin->move($key,"slots",.0005);
	$bitcoin->move($key,"site",.0001);
	$bitcoin->move($key,$settings["jackpotMonth"],.0002);
	$bitcoin->move($key,$settings["jackpotWeek"],.0002);
	$jackpotSlots = $bitcoin->getbalance("slots")*.9;

	if($roll->first==7 && $roll->second==7 && $roll->third==7){
		$bitcoin->move("slots",$key,$jackpotSlots);
		$data["won"] = true;
		$data["ammount"] = $jackpotSlots;
	}elseif($roll->first==10 && $roll->second==10 && $roll->third==10){
		$bitcoin->move("slots",$key,.01);
		$data["won"] = true;
		$data["ammount"] = .01;
	}elseif($roll->first==7 && $roll->second==7){
		$bitcoin->move("slots",$key,.01);
		$data["won"] = true;
		$data["ammount"] = .01;
	}elseif($roll->first==10 && $roll->second==10){
		$bitcoin->move("slots",$key,.005);
		$data["won"] = true;
		$data["ammount"] = .005;
	}elseif($roll->first==7){
		$bitcoin->move("slots",$key,.002);
		$data["won"] = true;
		$data["ammount"] = .002;
	}elseif($roll->first==10){
		$bitcoin->move("slots",$key,.001);
		$data["won"] = true;
		$data["ammount"] = .001;
	}

	$data["jackpot"] = $jackpotSlots;
	$entry = R::dispense('entry');
	$entry->type = "weekly";
	$entry->time = time();
	$entry->typeid = $settings["jackpotWeek"];
	$entry->key = $key;
	R::store($entry);
	$entry = R::dispense('entry');
	$entry->type = "monthly";
	$entry->time = time();
	$entry->typeid = $settings["jackpotMonth"];
	$entry->key = $key;
	R::store($entry);
}

$data["jackpot"] = $jackpotSlots;

echo json_encode($data);

?>