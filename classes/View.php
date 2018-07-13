<?php

class View
{
  private $template;

  public function __construct($template = null)
  {
    $this->template = $template;
  }

  public function listPosts($param)
  {
    extract($param);
    include('view/Frontend/listPostsView.php');
  }

  public function postView($params = array())
  {
    extract($params);
    include('view/frontend/postView.php');
  }

  public function redirect($route)
  {
    header('Location: index.php?r=post/id/'.$route);
    exit;
  }
}
