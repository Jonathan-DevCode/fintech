<?php

function autoload($className)
{
    $found = false;
    $classpaths = [
        'lib' . DIRECTORY_SEPARATOR,
        'model' . DIRECTORY_SEPARATOR,
        'controller' . DIRECTORY_SEPARATOR,
        'helpers' . DIRECTORY_SEPARATOR
    ];
    foreach ($classpaths as $path) {
        if (preg_match('/Model/', $className)) {
            $class = "$className.php";
        } else {
            $class = ucfirst("$className.php");
        }
        $filepath = _DIR_ . DIRECTORY_SEPARATOR . "$path" . $class;
        if (is_readable("$filepath")) {
            $found = true;
            require_once "$filepath";
            break;
        }
    }
    if ($found === false) {
        (new Page)->_404()->_and_stop();
    }
}
spl_autoload_register('autoload');