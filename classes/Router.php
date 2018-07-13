<?php
include_once('controller/frontend.php');

class Router
{
  private $request;

  private $routes = [
                      "home"          => ["controller" => 'Home', "method" => 'listPosts' ],
                      "post"          => ["controller" => 'Home', "method" => 'post'],
                      "addComment"    => ["controller" => 'Home', "method" => 'addComment'],
                      "modifyComment" => ["controller" => 'Home', "method" => 'modifyComment'],
                      "commentUpdate" => ["controller" => 'Home', "method" => 'commentUpdate'],
                    ];

  public function __construct($request)
  {
    $this->request = $request;
  }

  public function getRoute()
  {
    $elements = explode('/',$this->request);
    return $elements[0];
  }

  public function getParams()
  {
    $params = null;
    // extract $_GET params
    $elements = explode('/',$this->request);
    unset($elements[0]);

    for($i=1;$i<count($elements);$i++)
    {
      $params[$elements[$i]] = $elements[$i+1];
      $i++;
    }

    // extract $_POST params
    if($_POST)
    {
      foreach($_POST as $key => $val)
      {
        $params[$key] = $val;
      }
    }
    return $params;
  }

  public function renderController()
  {
    $route = $this->getRoute();
    $params = $this->getParams();

    if(key_exists($route,$this->routes))
    {
      $controller  = $this->routes[$route]['controller'];
      $method      = $this->routes[$route]['method'];

      $currentController = new $controller();
      $currentController->$method($params);

    } else {
      echo '404';
    }
  }
}
