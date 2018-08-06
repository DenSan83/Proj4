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

  public function unflag()
  {
    $commentManager = new CommentManager();
    $commentManager->unflag($_POST['commentId']);

    $myView = new View();
    $myView->redirect('admin');
  }

  public function deleteFlagged()
  {
    if($_SESSION['user_session']['user_status'] == 'admin'){
      $commentManager = new CommentManager();
      $commentManager->unflag($_POST['deleteId']);
      $commentManager->deleteComment($_POST['deleteId']);

      $myView = new View();
      $myView->redirect('admin');
    } else {
      echo '404';
    }
  }

  public function newPost()
  {
    $postManager = new PostManager();
    $postManager->newPost($_POST['postTitle'],$_POST['newPost']);

    $myView = new View();
    $myView->goHome();
  }
}
