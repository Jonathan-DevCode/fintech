<?php
error_reporting(E_ALL);

class Factory
{
    private $db;
    public $tb = '';
    public $prefix = '';
    public $pk = 'id';
    public $dataset = null;
    public $format = false;
    public $rs = null;
    public $query = null;
    public $join = null;
    public $debug = false;
    public $test = false;
    public $limit = null;
    public $offset = null;
    public $where = null;
    public $order = null;
    public $fields = '*';
    public $group_by = null;

    public function __construct($tb = null, $debug = false)
    {
        $registry = Registry::getInstance();
        if ($registry->get('db') == false) {
            $registry->set('db', new DB);
        }
        $this->db = $registry->get('db');
        if ($tb != null) {
            $this->tb = $tb;
        }
        if ($this->db->prefix) {
            $this->prefix = $this->tb . "_";
        }
        $this->pk = $this->prefix . $this->pk;

        if ($debug) {
            $this->debug = 1;
            $this->test = 1;
        }
        return $this;
    }

    public function __set($key, $value)
    {
        $_key = $this->prefix . $key;
        $key = $_key;
        $this->dataset["$key"] = self::sql_normalize($value);
        $this->debug("__set $key = $value <br>");
        return $this;
    }

    public function __get($key)
    {
        return (isset($this->$key)) ? $this->$key : null;
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    /*chamado automaticamente nos gets / find */
    public function format_out()
    {
        $format_aux = [];
        foreach ($this->format as $key => $value) {
            if (!preg_match('/_/', $key)) {
                $_key = preg_replace("/$this->prefix/", '', $key);
                $_key = $this->prefix . $_key;
                $format_aux["$_key"] = $this->format["$key"];
            } else {
                $format_aux["$key"] = $this->format["$key"];
            }
        }
        $this->format = $format_aux;

        foreach ($this->format as $key => $value) {
            $alias = false;
            $campo = $key;
            if (is_array($this->format[$key])) {
                $format = $this->format[$key][0];
                if(isset($this->format[$key][1]))
                $alias = $this->format[$key][1];
            } else {
                $format = $this->format[$key];
            }
            /*DATE */
            if ($format == 'date') {
                $sqlf = ", CASE $campo WHEN $campo IS NULL THEN '' ELSE DATE_FORMAT($campo,'%d/%m/%Y') ";
                if ($alias) {
                    $sqlf .= " END AS $alias ";
                } else {
                    $sqlf .= " END AS $campo ";
                }
                $this->fields .= $sqlf;
            }

            /*DATE TIME */
            if ($format == 'datetime') {
                $sqlf = ", CASE $campo WHEN $campo IS NULL THEN '' ELSE DATE_FORMAT($campo,'%d/%m/%Y %H:%i') ";
                if ($alias) {
                    $sqlf .= " END AS $alias ";
                } else {
                    $sqlf .= " END AS $campo ";
                }
                $this->fields .= $sqlf;
            }

            /* MONEY*/
            if ($format == 'money') {
                $sqlf = ", FORMAT($campo, 2, 'pt_BR') ";
                if ($alias) {
                    $sqlf .= " AS $alias ";
                } else {
                    $sqlf .= " AS $campo ";
                }
                $this->fields .= $sqlf;
            }

            /* DECIMAL */
            if ($format == 'decimal') {
                $sqlf = ", FORMAT($campo, 2, 'pt_BR') ";
                if ($alias) {
                    $sqlf .= " AS $alias ";
                } else {
                    $sqlf .= " AS $campo ";
                }
                $this->fields .= $sqlf;
            }            
        }
        $this->format = false;
        return $this;
    }

    /*chamado automaticamente no save */
    public function format_in()
    {
        /*IF SAVE*/
        foreach ($this->format as $key => $format) {
            $key = preg_replace("/$this->prefix/", '', $key);
            $key = $this->prefix . $key;

            if (isset($this->dataset["$key"])) {
                // Date
                if ($format == 'date') {
                    if ($this->dataset["$key"] != "") {
                        $this->dataset["$key"] = date('Y-m-d', strtotime(str_replace('/', '-', $this->dataset["$key"])));
                    } else {
                        $this->dataset["$key"] = "0000-00-00";
                    }
                }
                // Datetime
                if ($format == 'datetime') {
                    if ($this->dataset["$key"] != "") {
                        $this->dataset["$key"] = date('Y-m-d H:i:s', strtotime($this->dataset["$key"]));
                    } else {
                        $this->dataset["$key"] = "0000-00-00 00:00:00";
                    }
                }

                // Text
                if ($format == 'text') {
                    if ($this->dataset["$key"] != "") {
                        $this->dataset["$key"] = strip_tags($this->dataset["$key"]);
                    }
                }

                // Long Text
                if ($format == 'longtext') {
                    if ($this->dataset["$key"] != "") {
                        $this->dataset["$key"] = ($this->dataset["$key"]);
                    }
                }
                // Money
                if ($format == 'money') {
                    if ($this->dataset["$key"] != "") {
                        $this->dataset["$key"] = number_format(str_replace(',', '.', str_replace('.', '', $this->dataset["$key"])), 2, '.', '');
                    } else {
                        $this->dataset["$key"] = "0.00";
                    }
                }
                // Int
                if ($format == 'int') {
                    $this->dataset["$key"] = intval($this->dataset["$key"]);
                }
                // Default String 
                if ($format == '' || is_null($format) ) {
                    $this->dataset["$key"] = addslashes(strip_tags($this->dataset["$key"]));
                }                
            }
        }
        $this->format = false;
        return $this;
    }


    public function escope($escope)
    {
        if (is_array($escope)) {
            if(isset($escope[0])){
                $aux_escope = array_values($escope);    
            }else{
                $aux_escope = array_keys($escope);
            }
            $data = $this->dataset;
            $this->dataset = null;       
            foreach ($data as $key => $value) {
                $key = preg_replace("/$this->prefix/", '', $key);
                $_key = $this->prefix . $key;
                if (in_array($_key, $aux_escope) || in_array($key, $aux_escope)) {
                    $this->dataset["$_key"] = $value;
                }
            }
        }
        return $this;
    }
    /*
        $with = ['user_cod' => 1234, 'user_hash' => 'ABC123'];
        (new Factory('user'))->with($with)->save();

        $escope = ['user_cod' => 'int', 'user_hash' => 'text'];
        (new Factory('user'))->with($with,$escope)->save();        

        $escope = ['user_nome', 'user_hash'];   <-- Tipo Padrão Text / String   
        (new Factory('user'))->with($with,$escope)->save();        
    */
      public function with($data, $escope = null)
    {
        $this->dataset = null;
        if (!empty($data)) {

            if(is_array($data) && isset($data[0])){
                $aux = [];
                foreach ($data as $seed){
                    foreach ($seed as $key => $value) {
                        $value = self::sql_normalize($value);
                        $key = preg_replace("/$this->prefix/", '', $key);
                        $_key = $this->prefix . $key;
                        $aux["$_key"] = $value;
                    }
                    $this->dataset[] = $aux;
                }
            }else{
                foreach ($data as $key => $value) {
                    $value = self::sql_normalize($value);
                    $key = preg_replace("/$this->prefix/", '', $key);
                    $_key = $this->prefix . $key;
                    $this->dataset["$_key"] = $value;
                }
            }
            if ($escope != null && is_array($escope)) {
                self::escope($escope);
                self::format($escope);
            }
        }
        return $this;
    }

    public function limit($limit, $offset = 0)
    {
        $this->limit = intval($limit);
        $this->offset = intval($offset);
        return $this;
    }

    public function order($order)
    {
        $this->order = $order;
        return $this;
    }

    public function sql($str)
    {
        $this->query = trim($str);
        return $this;
    }

    public function query($sql)
    {
        $this->db->query = self::sql_normalize($sql);
        $this->debug("$sql");
        $this->db->query("$sql");
    }

    public function select($fields)
    {
        $this->fields = $fields;
        return $this;
    }

    public function join($table, $on, $tipo = 'INNER')
    {
        $this->join .= "$tipo JOIN $table ON $on ";
        return $this;
    }

    public function where($where)
    {
        $this->where = $where;
        return $this;
    }

    public function rows_count($sql)
    {
        $this->db->query = $sql;
        $data = $this->db->fetch();
        return (isset($data[0])) ? $data[0]->total : 0;
    }

    public function get($json = false, $no_prefix = false)
    {
        if ($this->format) {
            self::format_out();
        }
        $this->db->query = "SELECT $this->fields FROM `$this->tb` ";
        if ($this->query != null) {
            $this->db->query = $this->query;
        }

        if ($this->join !== null) {
            $this->db->query .= "  $this->join ";
        }
        if ($this->where !== null) {
            $this->db->query .= " WHERE $this->where ";
        }
        if ($this->group_by !== null) {
            $this->db->query .= " GROUP BY $this->group_by ";
        }
        if ($this->order !== null) {
            $this->db->query .= " ORDER BY $this->order ";
        }
        if ($this->limit !== null) {
            $this->db->query .= " LIMIT $this->offset, $this->limit ";
        }
        $this->debug($this->db->query);
        $data = $this->db->fetch();

        if ($no_prefix == true && isset($data[0])) {
            $data = self::no_prefix();
        }
        if ($json == true) {
            echo json_encode((isset($data[0])) ? $data : null);
        } else {
            return (isset($data[0])) ? $data : null;
        }
    }

    public function count()
    {
        $count = $this->get();
        return (is_null($count)) ? 0 : sizeof($count);
    }

    public function get_all($count = true, $query = null)
    {
        if ($this->format) {
            self::format_out();
        }
        $this->db->query = "SELECT $this->fields FROM `$this->tb` ";
        $sqlCount = "SELECT count(*) as total FROM `$this->tb` ";
        if ($query != null) {
            $this->db->query = $query;
        }
        if ($this->join !== null) {
            $this->db->query .= "  $this->join ";
        }
        if ($this->where !== null) {
            $this->db->query .= " WHERE $this->where ";
        }
        if ($this->group_by !== null) {
            $this->db->query .= " GROUP BY $this->group_by ";
        }
        if ($this->order !== null) {
            $this->db->query .= " ORDER BY $this->order ";
        }
        $page = null;
        if ($this->limit !== null) {
            $page = Http::get_in_params('page');
            if ($page) {
                $this->offset = ($page->value - 1) * intval($this->limit);
            }
            $this->db->query .= " LIMIT $this->offset, $this->limit ";
        }
        $data = $this->db->fetch();
        $total = ($count) ? self::rows_count($sqlCount) : 0;

        $all_pages = [];
        if (isset($data[0])) {
            $all_param = Http::get_all_params();
            foreach ($all_param as $k => $v) {
                if ($all_param[$k] == 'page') {
                    unset($all_param[$k]);
                    unset($all_param[$k + 1]);
                    break;
                }
            }
            $next_page = ($total > $this->limit) ? Http::base() . "/" . implode("/", $all_param) : null;
            $prev_page = ($total > $this->limit) ? Http::base() . "/" . implode("/", $all_param) : null;
            if (intval($this->limit) > 0) {
                $pages = ceil($total / intval($this->limit));
                if (isset($page->value) && ($page->value - 1) > 0) {
                    $page_prev = ($page) ? "$prev_page/page/" . ($page->value - 1) : null;
                } else {
                    $page_prev = null;
                }
                if (isset($page->value)) {
                    if (($page->value + 1) <= $pages) {
                        $page_next = ($page) ? "$next_page/page/" . ($page->value + 1) : null;
                    } else {
                        $page_next = null;
                    }
                } else {
                    $page_next = "$next_page/page/2/";
                }
                $base = Http::base();
                for ($i = 1; $i <= $pages; $i++) {
                    $all_pages[] = $base . "/" . implode("/", $all_param) . "/page/$i/";
                }
            } else {
                $page_next = null;
                $page_prev = null;
                $pages = null;
            }
            $this->rs = [
                'total' => $total,
                'rows' => count($data),
                'pages' => $pages,
                'page' => ($page) ? $page->value : null,
                'page_next' => $page_next,
                'page_prev' => $page_prev,
                'page_all' => $all_pages,
                'limit' => $this->limit,
                'data' => $data
            ];
        }
        return (isset($data[0])) ? $this->rs : [];
    }

    public function find($id = null, $json = false)
    {
        if ($id == null) {
            $id = $this->__get($this->pk);
        }
        if ($this->tb == null || $this->tb == '') {
            die('informe a tabela no construtor Factory!');
        }
        if (intval($id) > 0) {
            if ($this->format) {
                self::format_out();
            }
            $this->db->query = "SELECT $this->fields FROM $this->tb WHERE " . $this->pk . " = " . intval($id) . ";";
            $this->debug((string)$this->db->query);
            $this->rs = $this->db->fetch();
            if (!empty($this->rs[0])) {
                /*
                //$aux_no_prefix = $this->rs[0];
                foreach ($this->rs[0] as $k => $v) {
                    if (isset($prefix_ret)) {
                        $aux_no_prefix = new stdClass;
                        foreach ($this->rs[0] as $k => $v) {
                            $k = preg_replace("/$this->prefix/", '', $k);
                            $aux_no_prefix->$k = $v;
                        }
                    }
                    $this->$k = "$v";
                }
                //$this->rs[0] = $aux_no_prefix;
                */
            } else {
                $this->dataset["$this->pk"] = null;
            }
        }
        if ($json) {
            return (isset($this->rs[0])) ? json_encode($this->rs[0]) : '';
        } else {
            return (isset($this->rs[0])) ? $this->rs[0] : '';
        }
    }

    public function find_by($field, $value)
    {
        if ($this->format) {
            self::format_out();
        }
        $this->db->query = "SELECT $this->fields FROM $this->tb WHERE " . $field . " = '" . $value . "' LIMIT 1;";
        $this->debug((string)$this->db->query);
        $this->rs = $this->db->fetch();
        if (!empty($this->rs[0])) {
            foreach ($this->rs[0] as $k => $v) {
                $this->$k = "$v";
            }
        } else {
            $this->dataset["$this->pk"] = null;
        }
        return (isset($this->rs[0])) ? $this->rs[0] : '';
    }



    public function save()
    {
        $q = "";
        $pk = $this->pk;
        $id = 0;
        if ($this->format) {
            self::format_in();
        }

        if(is_array($this->dataset) && isset($this->dataset[0])){
            foreach($this->dataset as $data){
                if (isset($data[$pk]) && !empty($data[$pk]) && intval($data[$pk]) > 0) {
                    $id = intval($data[$pk]);
                    $q .= "UPDATE `$this->tb` SET ";
                    foreach ($data as $k => $v) {
                        $k = $k;
                        $q .= "`$k` = '$v', ";
                    }
                    //$this->pk = $this->pk;
                    $q = substr($q, 0, -3);
                    $q .= "' WHERE $this->pk = $id";
                } else {
                    if (isset($data[$pk])) {
                        unset($data[$pk]);
                    }
                    $q = "INSERT INTO `$this->tb` ";
                    $f = "(";
                    $v = "(";

                    $f .= $this->prefix . "created,";
                    $f .= $this->prefix . "updated,";
                    $v .= "NOW(),";
                    $v .= "NOW(),";
                    foreach ($data as $k => $j) {
                        $f .= "$k,";
                        $v .= "'$j',";
                    }
                    $f = substr($f, 0, -1);
                    $v = substr($v, 0, -1);
                    $f .= ")";
                    $v .= ")";
                    $q .= "$f VALUES $v;";
                    $q = trim($q);
                }
                $this->debug("$q");
                if ($this->test == true) {
                    exit;
                }
                $res = $this->db->query("$q");

                if ($res) {
                    $resp = [];
                    if ($id) {
                        $resp[] = $id;
                    } else {
                        $resp[] = $this->db->lastId();
                    }
                } else {
                    return false;
                }
            }
            return $resp;
        }else {
            if (isset($this->dataset[$pk]) && !empty($this->dataset[$pk]) && intval($this->dataset[$pk]) > 0) {
                $id = intval($this->dataset[$pk]);
                $q .= "UPDATE `$this->tb` SET ";
                foreach ($this->dataset as $k => $v) {
                    $k = $k;
                    $q .= "`$k` = '$v', ";
                }
                //$this->pk = $this->pk;
                $q = substr($q, 0, -3);
                $q .= "' WHERE $this->pk = $id";
            } else {
                if (isset($this->dataset[$pk])) {
                    unset($this->dataset[$pk]);
                }
                $q = "INSERT INTO `$this->tb` ";
                $f = "(";
                $v = "(";

                $f .= $this->prefix . "created,";
                $f .= $this->prefix . "updated,";
                $v .= "NOW(),";
                $v .= "NOW(),";
                foreach ($this->dataset as $k => $j) {
                    $f .= "$k,";
                    $v .= "'$j',";
                }
                $f = substr($f, 0, -1);
                $v = substr($v, 0, -1);
                $f .= ")";
                $v .= ")";
                $q .= "$f VALUES $v;";
                $q = trim($q);
            }
            $this->debug("$q");
            if ($this->test == true) {
                exit;
            }
            $res = $this->db->query("$q");
            if ($res) {
                if ($id) {
                    return $id;
                } else {
                    return $this->db->lastId();
                }
            } else {
                return false;
            }
        }
    }

    /*
        $with = ['user_cod' => 1234, 'user_hash' => 'azvadasd'];
        (new Factory('user'))->update($with,'user_id > 1');
    */
    public function update($with, $where = '1 = 1')
    {
        if (is_array($with)) {
            $sql = "UPDATE `$this->tb` SET ";
            foreach ($with as $k => $v) {

                $prefix = $this->prefix;
                $k = preg_replace("/$prefix/", '', $k);
                $key = "{$prefix}$k";
                $sql .= " $key = '$v',";
            }
            $sql = substr($sql, 0, -1);
            $sql .= " WHERE $where;";
            $this->debug("$sql");
            $this->db->query("$sql");
        } else {
            echo 'Parâmetro 1 deve ser um array!';
            exit;
        }
    }

    /*
        (new Factory('user'))->drop(1);
    */
    public function drop($id = null)
    {
        if ($id == null) {
            $id = $this->__get($this->pk);
        }
        if (intval($id) > 0) {
            $id = intval($id);
            $sql = "DELETE FROM `$this->tb` WHERE $this->pk = $id";
            $this->debug("$sql");
            $this->db->query("$sql");
        } else {
            $sql = "DELETE FROM `$this->tb` ";
            if ($this->where != null) {
                $sql .= "WHERE $this->where;";
            }
            $this->debug("$sql");
            $this->db->query("$sql");
        }
    }

    public function sql_normalize($sql)
    {
        return addslashes($sql);
    }

    public function debug($msg)
    {
        if ($this->debug) {
            echo "<pre> $msg </pre>";
        }
    }

    public function to_obj($values)
    {
        foreach ($values as $k => $v) {
            $objN = new stdClass;
            $objN->{$k} = $v;
            $objs[] = (object)$objN->{$k};
        }
        return (object)$objs;
    }

    public function group_by($group)
    {
        $this->group_by = "$group";
        return $this;
    }

    public function map($prefix = 0)
    {
        if ($prefix == 0) {
            $map = $this->db->show_columns($this->tb);
            foreach ($map as $k => $v){
                if($map->$k == 'NULL'){
                    $map->$k = '';
                }
            }
            return $map;
        } else {
            $cols = (array)$this->db->show_columns($this->tb);
            $cols_no_prefix = [];
            foreach ($cols as $key => $value) {
                $key = preg_replace("/$this->prefix/", '', $key);
                $cols_no_prefix[$key] = $value;
            }
            return $cols_no_prefix;
        }
    }

    public function no_prefix()
    {
        $aux_no_prefix_arr = [];
        $this->rs = $this->db->data;
        foreach ($this->rs as $k => $v) {
            $aux_no_prefix = new stdClass;
            foreach ($this->rs[$k] as $k => $v) {
                $k = preg_replace("/$this->prefix/", '', $k);
                $aux_no_prefix->$k = $v;
            }
            $aux_no_prefix_arr[] = $aux_no_prefix;
            $this->$k = "$v";
        }
        return $aux_no_prefix_arr;
    }

}

