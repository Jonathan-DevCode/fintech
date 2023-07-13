<?php

require_once "config/routes.php";

if (isset($routes["$Controller"])) {
    $parts = explode(":", $routes["$Controller"]);
    $Controller = $parts[0];
    $Action = $parts[1];
}
$Controller = preg_replace("/[^a-zA-Z0-9]+/", '', $Controller);
$obj = new $Controller;
/*
$registry = Registry::getInstance();
if ($registry->get("$Controller") == false) {
    $registry->set("$Controller", new $Controller);
    $obj = $registry->get("$Controller");}
*/

$obj->baseurl = Http::base();
if (method_exists($Controller, $Action)) {
    $obj->$Action();
} else {
    if (!in_array($Controller, $ignore) && !in_array($Action, $ignore)) {
        (new Page)->_action("$Controller::$Action()")->_and_stop();
    } else {
        $obj->indexAction();
    }
}
