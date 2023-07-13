<?php
@session_start(['cookie_lifetime' => 86400]);

Class ClientSession
{

    static public function start()
    {
        @session_start();
    }

    static public function init($timeLife = 10000)
    {
        $_SESSION['ACTIVITY_ID'] = md5(uniqid(time()));
        $_SESSION['START_ACTIVITY'] = time();
        $_SESSION['START_ACTIVITY_H'] = date('d/m/Y H:i:s');
        $_SESSION['LAST_ACTIVITY'] = time();
        $_SESSION['LIFE_TIME'] = $timeLife;
    }

    static public function time_activity()
    {
        return floor((time() - $_SESSION['START_ACTIVITY']) / 60);
    }

    static public function time_left()
    {
        $minutos = floor(($_SESSION['LIFE_TIME'] - (time() - $_SESSION['LAST_ACTIVITY'])) / 60);
        $segundos = (($_SESSION['LIFE_TIME'] - (time() - $_SESSION['LAST_ACTIVITY'])) % 60);
        if ($segundos <= 9) {
            $segundos = "0" . $segundos;
        }
        return "$minutos:$segundos";
    }

    static public function all_nodes()
    {
        if (isset($_SESSION['client_node'])) {
            return $_SESSION['client_node'];
        }
    }

    static public function node($key, $value = null)
    {
        if ($value == null) {
            if (isset($_SESSION['client_node'][$key])) {
                return $_SESSION['client_node'][$key];
            } else {
                return false;
            }
        } else {
            if (isset($key)) {
                $_SESSION['client_node'][$key] = $value;
            }
        }
    }

    static public function client_node($key, $value = null)
    {
        if ($value == null) {
            if (isset($_SESSION['clientNode'][$key])) {
                return $_SESSION['clientNode'][$key];
            } else {
                return false;
            }
        } else {
            if (isset($key)) {
                $_SESSION['clientNode'][$key] = $value;
            }
        }
    }

    static public function node_drop($key)
    {
        if (isset($_SESSION['client_node'][$key])) {
            unset($_SESSION['client_node'][$key]);
        }
    }

    static public function nodes_distroy()
    {
        if (isset($_SESSION['client_node'])) {
            unset($_SESSION['client_node']);
        }
    }


    static public function time_activity_end()
    {
        if (!isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] >= $_SESSION['LIFE_TIME'])) {
            return true;
        }
    }

    static public function check()
    {
        if (!isset($_SESSION['LAST_ACTIVITY']) || (time() - $_SESSION['LAST_ACTIVITY'] >= $_SESSION['LIFE_TIME'])) {
            return false;
        } else {
            return true;
        }
    }

    static public function destroy()
    {
        self::nodes_distroy();
        @session_destroy();
    }

}

/* end file */