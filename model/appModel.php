<?php

Class appModel {

    public $db;

    public function __construct() {
        $this->initApp();
    }

    public function initApp() {
        $registry = Registry::getInstance();
        if ($registry->get('db') == false) {
            $registry->set('db', new DB);
        }
        $this->db = $registry->get('db');
    }

}