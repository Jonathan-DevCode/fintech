<?php

class Media
{

    static public function upload($file, $dir, $type = 'img', $newname = null)
    {
        if ($type == 'img') {
            return self::img_upload($file, $dir, $newname);
        }
        if ($type == 'file') {
            return self::file_upload($file, $dir, $newname);
        }
    }

    static public function img_upload($file, $pasta = '', $newname = null)
    {
        // caso não for usado dentro de um foreach para as imagens
        $arquivo_tmp = $file['tmp_name'];
        $nome = $file['name'];
        if (!empty($nome) && !empty($arquivo_tmp)) {
            $ds = DIRECTORY_SEPARATOR;
            $dir = Path::base();
            $basepath = $dir . $ds . 'media' . $ds . $pasta;
            if (!is_dir("$basepath")) {
                mkdir("$basepath", 0775);
            } else {
                @system("sudo chmod -R 777 $basepath");
            }
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
            // Somente imagens
            if (strstr('.jpg;.jpeg;.png;.gif', $extensao)) {
                // cria novo nome para a img
                $nome_arquivo = explode(".", $nome);
                $nome_arquivo[0] = str_replace('.', '-', $nome_arquivo[0]);
                if ($newname == null) {
                    $novoNome = Filter::slug($nome_arquivo[0] . '-' . date('d-m-Y') . '-' . uniqid(time())) . '.' . $extensao;
                } else {
                    $novoNome = $newname . '.' . $extensao;
                }
                $novoNome = Media::slug($novoNome);
                // Concatena a pasta com o nome
                $destino = $basepath . $ds . $novoNome;
                // tenta mover o arquivo para o destino
                if (getimagesize($file["tmp_name"])) {
                    if (move_uploaded_file($arquivo_tmp, $destino)) {
                        // arquivo movido com sucesso
                        @system("sudo chmod -R 755 $destino");
                        $tamanho = filesize($destino);
                        $data = (object)['size' => $tamanho, 'url' => $novoNome, 'ext' => $extensao, 'path' => $destino];
                        return $data;
                    }
                }
            } else {
                // formato nao permitido
                return 1;
                exit;
            }
        } else {            
            return -1;
            exit;
        }
    }


    static function slug($key, $nkey = null, $reverse = null)
    {
        $group_a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç',
            'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð',
            'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú',
            'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä',
            'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í',
            'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø',
            'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'Ð', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '?', '?', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', '?', '?', 'L', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', '?', 'O', 'o',
            'O', 'o', 'O', 'o', '?', '?', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            '?', '?', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', '?', 'Z', 'z',
            'Z', 'z', '?', '?', '?', '?', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', '?',
            '?', '?', '?', '?', '?');
        $group_b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C',
            'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D',
            'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U',
            'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a',
            'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i',
            'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A',
            'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E',
            'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g',
            'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H',
            'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
            'I', 'i', '', '', 'J', 'j', 'K', 'k', 'L',
            'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l',
            'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o',
            'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R',
            'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's',
            'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
            'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z',
            'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U',
            'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u',
            'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A',
            'a', 'AE', 'ae', 'O', 'o');

        $pattern = array('/[^a-zA-Z0-9 -.]/', '/[ -]+/', '/^-|-$/');
        $replace = array(' ', '-', '');

        if ($reverse != null) {
            $replace = array('-', ' ', '');
        }
        $replaced = str_replace($group_a, $group_b, $key);
        return strtolower(Filter:: parse_string(preg_replace($pattern, $replace, $replaced)));
    }

    static public function img_rotaciona($img = null, $pasta = null)
    {
        if (strlen($pasta) > 0 && strlen($img) > 0) {
            $dir = Path::base();
            $ds = DIRECTORY_SEPARATOR;
            $path = $dir . $ds . 'media' . $ds . $pasta . $ds . $img;
            list($width, $height, $image_type) = getimagesize($path);
            switch ($image_type) {
                case 1:
                    $src = imagecreatefromgif($path);
                    break;
                case 2:
                    $src = imagecreatefromjpeg($path);
                    break;
                case 3:
                    $src = imagecreatefrompng($path);
                    break;
                default:
                    return '';
                    break;
            }
            $tmp = imagerotate($src, -90, 0);
            ob_start();
            switch ($image_type) {
                case 1:
                    imagegif($tmp);
                    break;
                case 2:
                    imagejpeg($tmp, NULL, 100);
                    break; // best quality
                case 3:
                    imagepng($tmp, NULL, 100);
                    break; // no compression
                default:
                    echo '';
                    break;
            }
            $final_image = ob_get_contents();
            $f = fopen("$path", "w");
            fwrite($f, $final_image);
            fclose($f);
            ob_end_clean(); // limpa o buffer de saida
            return 1;
        } else {
            return 0;
        }
    }

    static public function img_redimensiona($img = null, $pasta = null, $quality = 70)
    {
        if (strlen($pasta) > 0 && strlen($img) > 0) {
            $dir = Path::base();
            $ds = DIRECTORY_SEPARATOR;
            $path = $dir . $ds . 'media' . $ds . $pasta . $ds . $img;
            list($width, $height, $image_type) = getimagesize($path);
            switch ($image_type) {
                case 1:
                    $src = imagecreatefromgif($path);
                    break;
                case 2:
                    $src = imagecreatefromjpeg($path);
                    break;
                case 3:
                    $src = imagecreatefrompng($path);
                    break;
                default:
                    return '';
                    break;
            }
            ob_start();
            switch ($image_type) {
                case 1:
                    imagegif($src);
                    break;
                case 2:
                    imagejpeg($src, NULL, $quality);
                    break;
                case 3:
                    imagepng($src, NULL, $quality);
                    break;
                default:
                    echo '';
                    break;
            }
            $final_image = ob_get_contents();
            $f = fopen("$path", "w");
            fwrite($f, $final_image);
            fclose($f);
            ob_end_clean(); // limpa o buffer de saida
            $tamanho = filesize($path);
            return $tamanho;
        } else {
            return 0;
        }
    }

    static public function img_from_base64($base64_string, $pasta = '', $tipo = 'jpg')
    {
        if (!isset($base64_string)) {
            die("{\"error\": \" Sem o base_img\"}");
        }
        $ds = DIRECTORY_SEPARATOR;
        $dir = Path::base();
        $path = $dir . $ds . 'media' . $ds;
        $basepath = $path . $ds . $pasta;
        if (!is_dir("$basepath")) {
            @mkdir("$basepath", 0775);
        } else {
            @system("chmod -R 775 $basepath");
        }
        $nome = md5(uniqid(time())) . '.' . $tipo;
        $basepath = $basepath . $ds . $nome;
        $ifp = fopen($basepath, 'wb');
        $data = explode(',', $base64_string);
        fwrite($ifp, base64_decode($data[1]));
        fclose($ifp);
        return $nome;
    }

    static public function file_upload($file, $pasta = '', $newname = null)
    {
        // caso não for usado dentro de um foreach para as imagens
        $arquivo_tmp = $file['tmp_name'];
        $nome = $file['name'];
        if (!empty($nome) && !empty($arquivo_tmp)) {
            $ds = DIRECTORY_SEPARATOR;
            $dir = Path::base();
            $basepath = $dir . $ds . 'media' . $ds . $pasta;
            if (!is_dir("$basepath")) {
                @mkdir("$basepath", 0777);
            } else {
                @system("chmod -R 777 $basepath");
            }
            // Pega a extensão
            $extensao = pathinfo($nome, PATHINFO_EXTENSION);
            // Converte a extensão para minúsculo
            $extensao = strtolower($extensao);
            // Somente arquivos nos formatos permitidos
            if (strstr('.jpg;.jpeg;.png;.gif;.pdf;.doc;.docx;.xls;.xlx;.txt;.ppt;.pptx;.zip;.rar', $extensao)) {
                // cria novo nome para a img
                $nome_arquivo = explode(".", $nome);
                $nome_arquivo[0] = str_replace('.', '-', $nome_arquivo[0]);
                if ($newname == null) {
                    $novoNome = $nome_arquivo[0] . '-' . date('d-m-Y') . '-' . uniqid(time()) . '.' . $extensao;
                } else {
                    $novoNome = $newname . '.' . $extensao;
                }

                $novoNome = Media::slug($novoNome);
                // Concatena a pasta com o nome
                $destino = $basepath . $ds . $novoNome;
                // tenta mover o arquivo para o destino
                if (@move_uploaded_file($arquivo_tmp, $destino)) {
                    // arquivo movido com sucesso
                    @system("chmod -R 755 $destino");
                    $tamanho = filesize($destino);
                    $data = (object)['size' => $tamanho, 'name' => $novoNome, 'ext' => $extensao, 'path' => $destino];
                    return $data;
                } else {
                    echo -1;
                    exit;
                }
            } else {
                // formato nao permitido
                echo 0;
                exit;
            }
        } else {
            echo 0;
            exit;
        }
    }

    static public function file_remove($file)
    {
    }

    static public function dir_remove($directory)
    {
        if (is_dir($directory)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $file) {
                $file->isFile() ? unlink($file->getPathname()) : rmdir($file->getPathname());
            }
            rmdir($directory);
            return true;
        } else {
            return false;
        }
    }
        // VIDEO THUMBNAIL
    static public function get_video_thumbnail($link = null, $pasta = null){
        $thumbnail = self::getImgThumbURL($link);
        $img = self::saveImg($thumbnail['img'], $pasta);
        $thumbnail['img'] = $img;
        return $thumbnail;
    }
    static public function get_video_thumbnail_slide($link = null, $pasta = null){
        $thumbnail = self::getImgThumbURL($link);
        $img = self::saveImg_slide($thumbnail['img'], $pasta);
        $thumbnail['img'] = $img;
        return $thumbnail;
    }
    // Define se o video pertence ao Youtube ou ao vímeo e retorana a URL da thumbnail
    static public function getImgThumbURL($video = null){
        if($video){
            // verifica a URL para encontrar vídeo do Youtube ou Vimeo
            $pos = strpos(strtolower($video), 'youtu');
            if($pos){
                $resp = self::getYouTubeThumbnail($video, 2);
                $data = [
                    'img' => $resp['thumb'],
                    'video_id' => $resp['video_id'],
                    'player' => 'youtube'
                ];
                return $data;
            }else{
                // verifica a URL para encontrar vídeo do Vimeo
                $pos = strpos(strtolower($video), 'vimeo');
                if($pos){
                    $resp = self::getVimeoThumbnail($video, 2);
                    $data = [
                        'img' => $resp['thumb'],
                        'video_id' => $resp['video_id'],
                        'player' => 'vimeo'
                    ];
                    return $data;
                }
//                die('vídeo precisa não contem a URL do vídemo nem do Youtube');
            }
        }else{
//            die('Url do vídeo precisa ser fornecida');
        }
    }

// Recupera o ID do vídeo do Youtube e a thumbnail do vídeo e o tamanho da img seguindo os tamanhos disponiveis no youtube
    static public function getYouTubeThumbnail($link, $size = 1) {
        $video_id = explode("?v=", $link);
        if (!isset($video_id[1])) {
            $video_id = explode("youtu.be/", $link);
        }
        $youtubeID = $video_id[1];
        if (empty($video_id[1])) $video_id = explode("/v/", $link);
        $video_id = explode("&", $video_id[1]);
        $youtubeVideoID = $video_id[0];
        if ($youtubeVideoID) {
            // seleciona o tamanho da igm passada no parâmetro na função
            switch ($size) {
                case 1:
                    $size = '/sddefault.jpg';
                    break;
                case 2:
                    $size = '/mqdefault.jpg';
                    break;
                case 3:
                    $size = '/hqdefault.jpg';
                    break;
                case 4:
                    $size = '/maxresdefault.jpg';
                    break;
                default:
                    $size = '/mqdefault.jpg';
                    break;
            }
            $thumbURL = 'https://img.youtube.com/vi/' . $youtubeID . $size;
            return ['thumb' => $thumbURL, 'video_id' => $youtubeID];
        } else {
//            die('não foi possivel recuperar o ID do vídeo do Youtube');
        }
    }
    

// Recupera o ID do vídeo do Vimeo e a thumbnail do vídeo escolhendo o tamanho da img seguindo os tamanhos disponiveis na vimeo
    static public function getVimeoThumbnail($link, $size = 2){
        $id = explode('vimeo.com/', $link);
        if(isset($id[1]) && !empty($id[1])){
            $id = $id[1];
        }else{
            die('Url do vídeo incorreta');
        }
        $url = "http://vimeo.com/api/v2/video/" . $id . ".json";
        $thumb = file_get_contents($url);
        $data = json_decode($thumb);
        // seleciona o tamanho da igm passada no parâmetro na função
        switch ($size) {
            case 1:
                $size = 'thumbnail_small';
                break;
            case 2:
                $size = 'thumbnail_medium';
                break;
            case 3:
                $size = 'thumbnail_large';
                break;
            default:
                $size = 'thumbnail_large';
                break;
        }
        return ['thumb' => $data[0]->{$size}, 'video_id' => $id];
    }

// salva a img em uma pasta
    static public function saveImg($thumbnail = null, $path = null, $nome = null){
        $ds = DIRECTORY_SEPARATOR;
        $dir = Path::base();
        $basepath = $dir . $ds . 'media' . $ds . 'thumbnails';
        if (!is_dir("$basepath")) {
            @mkdir("$basepath", 0775);
        }else{
            @system("chmod -R 777 $basepath");
//                @chmod("$basepath", 775);
        }
        if(!empty($path)){
            $basepath = $dir . $ds . 'media' . $ds . 'thumbnails' . $ds . $path;
            if (!is_dir("$basepath")) {
                @mkdir("$basepath", 0775);
            }else{
                @system("chmod -R 777 $basepath");
//                @chmod("$basepath", 775);
            }
        }
        $path = $basepath;
        if($thumbnail){
            $ch = curl_init($thumbnail);
            if(!$nome){
                $nome =  time() . date('d-m-Y-H-i-s') .'.jpg';
            }
            $fp = fopen($path . '/' . $nome, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return $nome;
        }
    }
    static public function saveImg_slide($thumbnail = null, $path = null, $nome = null){
        $ds = DIRECTORY_SEPARATOR;
        $dir = Path::base();
        $basepath = $dir . $ds . 'media' . $ds . 'slides';
        if (!is_dir("$basepath")) {
            @mkdir("$basepath", 0775);
        }else{
            @system("chmod -R 777 $basepath");
//                @chmod("$basepath", 775);
        }
        if(!empty($path)){
            $basepath = $dir . $ds . 'media' . $ds . 'slides' . $ds . $path;
            if (!is_dir("$basepath")) {
                @mkdir("$basepath", 0775);
            }else{
                @system("chmod -R 777 $basepath");
//                @chmod("$basepath", 775);
            }
        }
        $path = $basepath;
        if($thumbnail){
            $ch = curl_init($thumbnail);
            if(!$nome){
                $nome =  time() . date('d-m-Y-H-i-s') .'.jpg';
            }
            $fp = fopen($path . '/' . $nome, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return $nome;
        }
    }
}