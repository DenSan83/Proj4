<?php
class BackController
{
  public function admin()
  {
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      //get last comments
      $commentManager = new CommentManager();
      $lastComments = $commentManager->getLast(5);
      $postManager = new PostManager();

      //get flagged
      $flagged = $commentManager->getFlagged();

      //envelopper dans array
      $params = array(
        'lastComments' => $lastComments,
        'postManager' => $postManager,
        'flagged' => $flagged,
        'commentManager' => $commentManager
      );
      $myView = new View('adminView');
      $myView->render($params);
    } else {
      $myView = new View();
      $myView->goHome();
    }
  }
}
