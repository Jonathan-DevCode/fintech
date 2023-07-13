<?php

class ClientCookie
{
    static public function init($timeLife = 10000)
    {
        $expiration = time() + 31536000;
        setcookie('ACTIVITY_ID', md5(uniqid(time())), $expiration);
        setcookie('START_ACTIVITY', time(), $expiration);
        setcookie('START_ACTIVITY_H', date('d/m/Y H:i:s'), $expiration);
        setcookie('LAST_ACTIVITY', time(), $expiration);
        setcookie('LIFE_TIME', $timeLife, $expiration);
    }

    static public function node($key, $value = null, $timeLife = null)
    {
        if ($value === null) {
            if (isset($_COOKIE['client_node'][$key])) {
                return $_COOKIE['client_node'][$key];
            } else {
                return false;
            }
        } else {
            if (isset($key)) {
                $expiration = time() + ($timeLife !== null ? $timeLife : $_COOKIE['LIFE_TIME']);
                setcookie('client_node[' . $key . ']', $value, $expiration);
            }
        }
    }

    static public function nodes_destroy()
    {
        if (isset($_COOKIE['client_node'])) {
            unset($_SESSION['client_node']);
        }
    }

    static public function destroy()
    {
        self::nodes_destroy();
        setcookie('ACTIVITY_ID', '', time() - 3600);
        setcookie('START_ACTIVITY', '', time() - 3600);
        setcookie('START_ACTIVITY_H', '', time() - 3600);
        setcookie('LAST_ACTIVITY', '', time() - 3600);
        setcookie('LIFE_TIME', '', time() - 3600);
        unset($_COOKIE['client_node']);
    }
}
