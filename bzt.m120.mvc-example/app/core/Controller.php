<?php

    class Controller {

        public function model($model) {
            require_once('../app/models/' . $model . '.php');
            return new $model();
        }

        public function view($view, $data = []) {
            require_once('../app/views/' . $view . '.php');
        }

        public function goto($controller, $method, $args = []) {
            $base = '/PHPAufgabe/bzt.m120.mvc-example/public';
            $location = 'http://' . $_SERVER['HTTP_HOST'] . $base . "/" . $controller . "/" . $method . "/" . implode("/", $args);
            header("Location: " . $location);
            exit;
        }
    }
?>