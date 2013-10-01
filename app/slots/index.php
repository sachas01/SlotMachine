<?php

$content = array(
	"title" => $settings["name"],
	"slots" => true,
	"pagecss" => "slots",
);

$content = array_merge($content,$settings,$user->export());
echo $m->render("pages/slots", $content);

?>