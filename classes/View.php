<?php

class View
{
  private $template;

  public function __construct($template = null)
  {
    $this->template = $template;
  }

  public function render($params = null)
  {
    $backendList = array('adminView','editPost','showComments');
    $template = $this->template;

    ob_start();
    if(!empty($params)){
      extract($params);
    }
    if(in_array($template,$backendList)){
      require(VIEW_BCK.$template.'.php');
    } else {
      require(VIEW.$template.'.php');
    }
    $content = ob_get_clean();
    require(VIEW.'template.php');
  }

  public function redirect($route)
  {
    header('Location: '.HOST.$route);
    exit();
  }

  public function goHome()
  {
    header('Location: '.HOST);
    exit();
  }
}
