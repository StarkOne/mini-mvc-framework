<?php

namespace application\core;

class Router
{
  protected $router = [];
  protected $params = [];

  public function __construct()
  {
    $arr = require_once 'application/config/routes.php';
    foreach ($arr as $key => $value) {
      $this->add($key, $value);
    }
  }

  public function add($route, $params)
  {
    $route = '#^'. $route .'#';
    $this->router[$route] = $params;
  }

  public function match()
  {
    $url = trim($_SERVER['REQUEST_URI'], '/');
    foreach ($this->router as $route => $parems) {
      if(preg_match($route, $url, $matches)) {
        $this->params = $parems;
        return true;
      }
    }
    return false;
  }

  public function run()
  {
    if($this->match()) {
      $controller = 'application\controllers\\' . ucfirst($this->params['controller']) . 'Controller.php';
      if(class_exists($controller)) {
        echo 'ОК';
      } else {
        echo 'Не найден' . $controller;
      }
    } else {
      echo "Маршрут не найден";
    }
  }

}
