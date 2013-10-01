<?php

$content = array(
	"title" => $settings["name"],
	"games" => true,
	"pagecss" => "index",
	"type" => ucwords($var[2]),
);
if($var[2]=="weekly")
	$content["jackpot"] = $jackpotWeek->export();
else
	$content["jackpot"] = $jackpotMonth->export();

$winners =  R::find('jackpot',' type = ? AND id != ?', array($var[2], $content["jackpot"]["id"]));
$content["winners"] = R::exportAll($winners);

$content = array_merge($content,$settings);
echo $m->render("pages/jackpot", $content);

?>