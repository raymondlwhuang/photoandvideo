<?php

class View {
    function __construct() {
        
    }

    public function render($page, $error, $params) {
        $pg = explode('?', $page);

        if (stripos($pg[0], '.php') == false) {
            $pg[0] .= '.php';
        }

		define('FRONTEND_PATH', "http://{$_SERVER['HTTP_HOST']}/PHP");

        if (file_exists("PHP/{$pg[0]}")) {
            require "PHP/{$pg[0]}";
        } else {
            require 'PHP/index.php';
        }
    }


}

?>