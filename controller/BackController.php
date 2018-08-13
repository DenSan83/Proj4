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
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      $commentManager = new CommentManager();
      $commentManager->unflag($_POST['commentId']);

      $myView = new View();
      $myView->redirect('admin');
    } else {
      $myView = new View();
      $myView->goHome();
    }
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
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      $errors = array();
      $data = array();
      if(isset($_FILES['postImg']) && !empty($_FILES['postImg']['name'])) {       // si on a une image

        $tailleMax = 2097152; // octets pour 2Mo
        $extensionsValides = array('jpg','jpeg','gif','png');                     // verifier taille
        if($_FILES['postImg']['size'] > $tailleMax || $_FILES['postImg']['size'] <= 0 ){
          $errors += ['errImage' => 'Erreur : L\'image ne doit pas depasser 2 Mo'];
        }

        $extensionUpload = strtolower(substr(strrchr($_FILES['postImg']['name'],'.'),1));
        // strrchr: renvoyer extension du fichier à partir du point '.' /
        // substr : ignorer le caractère "1" de la chaine (le point) /
        // strtolower : tout en minuscule /
        $newName = substr_replace($_FILES['postImg']['name'], '',-4);
        if(!in_array($extensionUpload,$extensionsValides)){                       // verifier extensionValide
          $errors += ['errImage' => 'Erreur : L\'image doit être de format jpg, jpeg, gif ou png'];
        }

        $chemin = 'public/images/post/'.$newName.'.'.$extensionUpload;
        $resultat = move_uploaded_file($_FILES['postImg']['tmp_name'],$chemin);
        if(!$resultat){                                                           // verifier chargement
          $errors += ['errImage' => 'Erreur de chargement de photo'];
        }

        if (empty($errors)) {
          $image = $newName.'.'.$extensionUpload;
          $data += ['image' => $image];
        }
      }

      $content = str_replace("\r\n",'',$_POST['newPost']);
      $data += array(
        'title' => $_POST['postTitle'],
        'content' => $content
      );
      $post = new Post($data);
      $errors += $post->getErrors();

      if(empty($errors)){
        $postManager = new PostManager();
        $postManager->newPost($data);
        $_SESSION['error']['clear'] = '  Succès ! Le post a été créé.';
      } else {
        $_SESSION['error']['newPost'] = $errors;
      }
    }

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
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      $errors = array();
      $data = array('id' => $_POST['postId']);
      if(isset($_FILES['postImg']) && !empty($_FILES['postImg']['name'])) {       // si on a une image

        $tailleMax = 2097152; // octets pour 2Mo
        $extensionsValides = array('jpg','jpeg','gif','png');                     // verifier taille
        if($_FILES['postImg']['size'] > $tailleMax || $_FILES['postImg']['size'] <= 0 ){
          $errors += ['errImage' => 'Erreur : L\'image ne doit pas depasser 2 Mo'];
        }

        $extensionUpload = strtolower(substr(strrchr($_FILES['postImg']['name'],'.'),1));
        // strrchr: renvoyer extension du fichier à partir du point '.' /
        // substr : ignorer le caractère "1" de la chaine (le point) /
        // strtolower : tout en minuscule /
        $newName = substr_replace($_FILES['postImg']['name'], '',-4);
        if(!in_array($extensionUpload,$extensionsValides)){                       // verifier extensionValide
          $errors += ['errImage' => 'Erreur : L\'image doit être de format jpg, jpeg, gif ou png'];
        }

        $chemin = 'public/images/post/'.$newName.'.'.$extensionUpload;
        $resultat = move_uploaded_file($_FILES['postImg']['tmp_name'],$chemin);
        if(!$resultat){                                                           // verifier chargement
          $errors += ['errImage' => 'Erreur de chargement de photo'];
        }

        if (empty($errors)) {
          $image = $newName.'.'.$extensionUpload;
          $data += ['image' => $image];
        }
      }

      $content = str_replace("\r\n",'',$_POST['newPost']);
      $data += array(
        'title' => $_POST['postTitle'],
        'content' => $content
      );
      $post = new Post($data);
      $errors += $post->getErrors();

      if(empty($errors)){
        $postManager = new PostManager();
        $postManager->postUpdate($data);
        $_SESSION['error']['clear'] = '  Succès ! Le post a été mis à jour.';
      } else {
        $_SESSION['error']['newPost'] = $errors;
      }

      $myView = new View();
      $myView->redirect('editPost/id/'.$_POST['postId']);
    } else {
      $myView = new View();
      $myView->goHome();
    }
  }

  public function delPost()
  {
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      $postManager = new PostManager();
      $postManager->delPost($_POST['delId']);

      $myView = new View();
      $myView->redirect('admin');
    } else {
      $myView = new View();
      $myView->goHome();
    }
  }
}
