<?php
if (isset($routes["$route"])) {
    $parts = explode(":", $routes["$route"]);
    $Controller = $parts[0];
    $Action = $parts[1];
    $Param = $parts[2];
} else {
    (new Page)->_404()->_and_stop();
}

