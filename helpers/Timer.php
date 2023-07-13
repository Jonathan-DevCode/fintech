<?php

class Timer
{
    public static function diff_days($d1, $d2)
    {
        $dStart = new DateTime($d1);
        $dEnd = new DateTime($d1);
        $dDiff = $dStart->diff($dEnd);
        echo $dDiff->format('%R');
        echo $dDiff->days;
    }

    public static function day_interval($ndays = 0, $interval = '+',$date = null)
    {
        if ($date == null) {
            $date = date('Y-m-d');
        }
        return date('Y-m-d', strtotime($date . " $interval $ndays days"));
    }

    public static function parse_date($str, $patt = 'Y-m-d')
    {
        $str = preg_replace('/\//', '-', $str);
        $str = date($patt, strtotime($str));
        return $str;
    }

    static public function now($f = 'Y-m-d H:i:s')
    {
        $now = new DateTime("NOW", new DateTimeZone("America/Sao_Paulo"));
        $now->format($f);
        return $now;
    }
}
