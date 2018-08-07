<?php

class Router
{
  private $request;

  private $routes = [
                      "home"          => ["controller" => 'FrontController', "method" => 'listPosts' ],
                      "post"          => ["controller" => 'FrontController', "method" => 'post'],
                      "addComment"    => ["controller" => 'FrontController', "method" => 'addComment'],
                      "newUser"       => ["controller" => 'FrontController', "method" => 'newUser'],

                      "login"         => ["controller" => 'UserController', "method" => 'login'],
                      "logout"        => ["controller" => 'UserController', "method" => 'logout'],
                      "onlineComment" => ["controller" => 'UserController', "method" => 'onlineComment'],
                      "modifyComment" => ["controller" => 'UserController', "method" => 'modifyComment'],
                      "commentUpdate" => ["controller" => 'UserController', "method" => 'commentUpdate'],
                      "delete"        => ["controller" => 'UserController', "method" => 'deleteComment'],
                      "flagComment"   => ["controller" => 'UserController', "method" => 'flagComment'],
                      "editProfile"   => ["controller" => 'UserController', "method" => 'editProfile'],
                      "updateProfile" => ["controller" => 'UserController', "method" => 'updateProfile'],

                      "admin"         => ["controller" => 'BackController', "method" => 'admin'],
                      "unflag"        => ["controller" => 'BackController', "method" => 'unflag'],
                      "delAdmin"      => ["controller" => 'BackController', "method" => 'deleteFlagged'],
                      "adminPost"     => ["controller" => 'BackController', "method" => 'newPost'],
                      "editPost"      => ["controller" => 'BackController', "method" => 'editPost'],
                      "modifyPost"    => ["controller" => 'BackController', "method" => 'modifyPost'],
                      "delPost"       => ["controller" => 'BackController', "method" => 'delPost'],
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
    // extract $_GET params :
    $elements = explode('/',$this->request);
    unset($elements[0]);

    for($i=1;$i<count($elements);$i++)
    {
      $params[$elements[$i]] = $elements[$i+1];
      $i++;
    }

    // extract $_POST params :
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
