<?php
class BackController
{
  public function admin()
  {
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      //get last comments
      $commentManager = new CommentManager();
      $lastComments = $commentManager->getLast(5);
      //sur quel post? => post_id->post_titre

      //get flagged
      $flagged = $commentManager->getFlagged();
      //var_dump($flagged[0]);exit(); // id, comment_id, flagger, flag_date
      foreach($flagged as $flagComment){
        //id, auteur du comment, comment, flagger, date_flagged, supr, unflag
        //$flagComment['comment_id'], auteur du comment, comment, $flagComment['flagger'], $flagComment['flag_date'], supr, unflag
      }

      //envelopper dans array
      $params = array('lastComments' => $lastComments,'flagged' => $flagged);
      $myView = new View('adminView');
      $myView->render($params);
    } else {
      $myView = new View();
      $myView->goHome();
      //header('Location: '.HOST);
    }
  }
}
