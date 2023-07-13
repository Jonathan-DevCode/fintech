<?php

class Tpl
{
    public static function view($tpl, $data = null, $compress = false, $cache = false, $isObj = 0)
    {
        if ($data != null && $isObj == true) {
            $data = (object)$data;
        }
        $lifeTime = 180;
        $tpl = preg_replace('/\./', '/', $tpl);
        $ds = DIRECTORY_SEPARATOR;
        $file = 'view' . $ds . $tpl . '.php';
        $fileCache = Path::base() . $ds . 'view' . $ds . "cache" . $ds . $tpl . '.cache';
        ob_start();
        if (file_exists($fileCache) && (filemtime($fileCache) > (time() - $lifeTime)) && $cache) {
            $content = file_get_contents($fileCache);
        } else {
            (!file_exists($file)) ? die('Arquivo view' . $ds . $tpl . '.php inexistente.') : '';
            require_once 'view' . $ds . $tpl . '.php';
            $content = ob_get_contents();
            if (preg_match_all('/\@\((.*?)\)/', $content, $p)) {
                if (isset($p[1])) {
                    foreach ($p[1] as $m) {
                        $n = self::import($m,$data);
                        $content = str_replace("@($m)", $n, $content);
                    }
                }
            }
            $BaseURI = Http::base();
            $content = str_replace('${baseUri}', $BaseURI, $content);
            $content = str_replace('${rand}', rand(0, 5000), $content);
            if (isset($data['mapper']) && !empty($data['mapper'])) {
                foreach ($data['mapper'] as $map) {
                    foreach ($data["$map"] as $k => $v) {
                        $content = str_replace('${' . $k . '}', $v, $content);
                    }
                }
            }
            if ($cache) {
                if ($compress == true) {
                    $content = self::minyfy($content);
                }
                file_put_contents($fileCache, $content);
            }
        }
        ob_end_clean();
        if ($compress == true) {
            echo self::minyfy($content);
        } else {
            echo $content;
        }
    }

    public static function output($tpl, $data = null, $compress = false, $cache = false, $isObj = 0)
    {
        if ($data != null && $isObj == true) {
            $data = (object)$data;
        }
        $lifeTime = 180;
        $tpl = preg_replace('/\./', '/', $tpl);
        $ds = DIRECTORY_SEPARATOR;
        $file = 'view' . $ds . $tpl . '.php';
        $fileCache = 'view' . $ds . "cache" . $ds . $tpl . '.cache';
        ob_start();
        if (file_exists($fileCache) && (filemtime($fileCache) > time() - $lifeTime) && $cache) {
            $content = file_get_contents($fileCache);
        } else {
            (!file_exists($file)) ? die('Arquivo view' . $ds . $tpl . '.php inexistente.') : '';
            require_once 'view' . $ds . $tpl . '.php';
            $content = ob_get_contents();
            if (preg_match_all('/\@\((.*?)\)/', $content, $p)) {
                if (isset($p[1])) {
                    foreach ($p[1] as $m) {
                        $n = self::import($m,$data);
                        $content = str_replace("@($m)", $n, $content);
                    }
                }
            }
            $BaseURI = Http::base();
            $content = str_replace('${baseUri}', $BaseURI, $content);
            $content = str_replace('${rand}', rand(0, 5000), $content);
            if(isset($data['mapper']) && !empty($data['mapper'])){
                foreach ($data['mapper'] as $map) {
                    foreach ($data["$map"] as $k => $v) {
                        $content = str_replace('${'.$k.'}', $v, $content);
                    }
                }
            }
            if ($cache) {
                if ($compress == true) {
                    $content = self::minyfy($content);
                }
                file_put_contents($fileCache, $content);
            }
        }
        ob_end_clean();
        if ($compress == true) {
            return self::minyfy($content);
        } else {
            return $content;
        }
    }

    public static function minyfy($content)
    {
        $search = array(
            '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
            '/[^\S ]+\</s',     // strip whitespaces before tags, except space
            '/(\s)+/s',         // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
        return preg_replace($search, $replace, $content);
    }

    public static function import($partial, $datax)
    {
    	global $data;
    	$data = $datax;
        $partial = preg_replace('/\./', '/', $partial);
        $ds = DIRECTORY_SEPARATOR;
        $file = 'view' . $ds . $partial . '.php';
        if (file_exists($file)) {
            ob_start();
            require_once 'view' . $ds . $partial . '.php';
            $icontent = ob_get_contents();
            if (preg_match_all('/\@\((.*?)\)/', $icontent, $p)) {
                if (isset($p[1])) {
                    foreach ($p[1] as $m) {
                        $n = self::import_step($m,$data);
                        $icontent = str_replace("@($m)", $n, $icontent);
                    }
                }
            }
            ob_end_clean();
            return $icontent;
        } else {
            return 'view' . $ds . $partial . '.php inexistente.';
        }
    }

    public static function import_step($partial,$datax)
    {
    	global $data;
    	$data = $datax;    	
        $partial = preg_replace('/\./', '/', $partial);
        $ds = DIRECTORY_SEPARATOR;
        $file = 'view' . $ds . $partial . '.php';
        if (file_exists($file)) {
            ob_start();
            require_once 'view' . $ds . $partial . '.php';
            $ricontent = ob_get_contents();
            ob_end_clean();
            return $ricontent;
        } else {
            return 'view' . $ds . $partial . '.php inexistente.';
        }
    }

}
