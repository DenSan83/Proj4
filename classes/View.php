<?php

class View
{
  private $template;
  const LIST_POST = VIEW.'listPostsView.php';
  const POST_VIEW = VIEW.'postView.php';
  const EDIT_COMMENT_VIEW = VIEW.'editCommentView.php';
  const NEW_USER = VIEW.'newUser.php';
  const EDIT_PROFILE = VIEW.'editProfile.php';

  public function __construct($template)
  {
    $this->template = $template;
  }

  public function render($params = null)
  {
    $template = $this->template;
    switch($template)
    {
      case 'postsList':
        include(self::LIST_POST);
        break;
      case 'postView':
        include(self::POST_VIEW);
        break;
      case 'editCommentView':
        include(self::EDIT_COMMENT_VIEW);
        break;
      case 'newUser':
        include(self::NEW_USER);
        break;
      case 'editProfile':
        include(self::EDIT_PROFILE);
        break;
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
