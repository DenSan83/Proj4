<?php

class View
{
  private $template;
  const LIST_POST = VIEW.'listPostsView.php';
  const POST_VIEW = VIEW.'postView.php';

  public function __construct($template)
  {
    $this->template = $template;
  }

  public function render($params)
  {
    $template = $this->template;
    switch($template)
    {
      case 'postsList':
        include(self::LIST_POST);
        break;
      case 'postView':
        include(self::POST_VIEW);
    }
  }

  public function redirect($route)
  {
    header('Location: '.HOST.'post/id/'.$route);
    exit;
  }

  public function goHome()
  {
    header('Location: '.HOST);
    exit;
  }
}
