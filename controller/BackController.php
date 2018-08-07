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

  public function editPost($array)
  {
    $id = $array['id'];
    if ($_SESSION['user_session']['user_status'] == 'admin'){

      $postManager = new PostManager();
      $myPost = $postManager->getPost($id);
      $params = array(
        'id' => $myPost->getId(),
        'title' => $myPost->getTitle(),
        'content' => $myPost->getContent(),
        'image' => $myPost->getImage()
      );

      $myView = new View('editPost');
      $myView->render($params);
    } else {
      $myView = new View();
      $myView->goHome();
    }
  }

  public function modifyPost()
  {
    //var_dump($_FILES,$_POST);exit();
    if(isset($_FILES['postImg']) && !empty($_FILES['postImg']['name'])) {
      $tailleMax = 2097152; // octets pour 2Mo
      $extensionsValides = array('jpg','jpeg','gif','png');
      if($_FILES['postImg']['size'] <= $tailleMax && $_FILES['postImg']['size'] != 0){
        $extensionUpload = strtolower(substr(strrchr($_FILES['postImg']['name'],'.'),1));
        // strrchr: renvoyer extension du fichier à partir du point '.' /
        // substr : ignorer le caractère "1" de la chaine (le point) /
        // strtolower : tout en minuscule /
        if(in_array($extensionUpload,$extensionsValides)){ // voir si l'extensionUpload contient un extensionValide
          $chemin = 'public/images/post/post'.$_POST['postId'].'.'.$extensionUpload;
          $resultat = move_uploaded_file($_FILES['postImg']['tmp_name'],$chemin);
          if($resultat){
            $image = 'post'.$_POST['postId'].'.'.$extensionUpload;
            $data = array(
              'id' => $_POST['postId'],
              'title' => $_POST['postTitle'],
              'content' => $_POST['newPost'],
              'image' => $image
            );
            $postManager = new PostManager();
            $postManager->postUpdate($data);
            $_SESSION['error']['clear'] = '  Succès ! Le post a été mis à jour.';
          } else {
            $errImage = 'Erreur de chargement de photo';
          }
        } else {
          $errImage = 'Erreur : L\'image doit être de format jpg, jpeg, gif ou png';
        }
      } else {
        $errImage = 'Erreur : L\'image ne doit pas depasser 2 Mo';
      }
      if(isset($errImage))
        $_SESSION['error']['image'] = array('errImage' => $errImage);
    } else {
      $data = array(
        'id' => $_POST['postId'],
        'title' => $_POST['postTitle'],
        'content' => $_POST['newPost'],
        'image' => null
      );
      $postManager = new PostManager();
      $postManager->postUpdate($data);
      $_SESSION['error']['clear'] = '  Succès ! Le post a été mis à jour.';
    }


    $myView = new View();
    $myView->redirect('editPost/id/'.$_POST['postId']);
  }
}
