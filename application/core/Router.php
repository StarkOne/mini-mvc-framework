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
    $route = '#^'. $route .'$#';
    $this->router[$route] = $params;
  }

  public function match()
  {
    $url = trim($_SERVER['REQUEST_URI'], '/');
    foreach ($this->router as $route => $params) {
      if(preg_match($route, $url, $matches)) {
        $this->params = $params;
        return true;
      }
    }
    return false;
  }

  public function run()
  {
    if($this->match()) {
      $path = 'application\controllers\\' . ucfirst($this->params['controller']) . 'Controller';
      if(class_exists($path)) {
        $action = $this->params['action'] . 'Action';
        if(method_exists($path, $action)){
          $controller = new $path($this->params);
          $controller->$action();
        } else {
          echo 'Action не найден';
        }
      } else {
        echo 'Не найден контроллер' . $path;
      }
    } else {
      echo "Маршрут не найден";
    }
  }

}
