<?php
class UserController
{
  public function login($params)
  {
    $myView = new View('postView');
    extract($params); //$params = array($login,$password)
    if (isset($login))
    {
      $login = htmlspecialchars($login);
      //$password = htmlspecialchars($password);

      if (!empty($password) && !empty($login))
      {
        $reqlogin = new LoginManager();
        $hashedPw = $reqlogin->getUser($login)->getMdp();

        if (password_verify($password, $hashedPw))
        {
          // Tous les test passées, on fais login session
          $user = $reqlogin->login(array('login' => $login, 'password' => $hashedPw));
          unset($_SESSION['noUser']);

          $_SESSION['user_session']['user_id']      = $user['id'];
          $_SESSION['user_session']['user_pseudo']  = $user['pseudo'];
          $_SESSION['user_session']['user_avatar']  = $user['avatar'];
          $_SESSION['user_session']['user_email']   = $user['email'];
          $_SESSION['user_session']['user_status']  = $user['status'];


          if ($postId !== null){
            $myView->redirect('post/id/'.$postId);
          } else {
            $myView->goHome();
          }
        } else {
          // utilisateur inexistant
          $_SESSION['noUser'] = 1;
        }
      }
    }
    $myView->goHome();
  }

  public function logout()
  {
    session_destroy();
    $myView = new View('listPostsView');
    $myView->goHome();
  }

  public function onlineComment($params)
  {
    extract($params);
    $errors = array();
    if (empty($pseudo)){
      $errors += ['errPseudo' => 'Le commentaire n\'a pas été ajouté. Veuillez reesayer ulterieurement'];
    }
    if(empty($comment)){
      $errors += ['errComment' => 'Veuillez ajouter un commentaire'];
    }
    if(strlen($comment) > 260){
      $errors += ['errCommentContent' => 'Le commentaire ne doit pas dépasser les 260 caractères'];
    }

    if (empty($errors)) {
      $commentManager = new CommentManager();
      $commentManager->postComment($postId,$pseudo,$authorId,$comment);
      $_SESSION['comment']['success'] = 1;
    } else {
      $_SESSION['comment']['error'] = $errors;
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function modifyComment($params)
  {
    if(is_array($params)){
      extract($params);
    } else {
      $myView = new View();
      $myView->redirect('notFound');
    }
    $postManager = new PostManager();
    $post = $postManager->getPost($postId);
    $commentManager = new CommentManager();
    $comments = $commentManager->getComments($postId);

    $avatarList = array();
    $elmZero = array('avatar' => 'default.png','status' => 'visiteur' );
    $avatarList += [ 0 => $elmZero];
    $loginManager = new LoginManager();
    $idList = $loginManager->idList();

    foreach ($idList as $id => $value) {
      $value = (int) $value;
      $userAv = $loginManager->getAvatar($value);
      $avatarList += [$value => $userAv];
    }

    $data = array(
      'post' => $post,
      'comments' => $comments,
      'avatarList' => $avatarList,
      'commentId' => $commentId
    );
    $myView = new View('postView');
    $myView->render($data);
  }

  public function commentUpdate($params)
  {
    if(is_array($params)){
      extract($params);
    } else {
      $myView = new View();
      $myView->redirect('notFound');
    }
    $commentManager = new CommentManager();
    $check = $commentManager->verifyAuthor($commentId,$_SESSION['user_session']['user_id']);
    if($check){
      $commentManager->commentUpdate($commentId,$updated);
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId.'#comment'.$commentId);
  }

  public function deleteComment($data)
  {
    if(is_array($data)){
      extract($data);
    } else {
      $myView = new View();
      $myView->redirect('notFound');
    }
    $commentManager = new CommentManager();
    $check = $commentManager->verifyAuthor($commId,$_SESSION['user_session']['user_id']);
    if($check)
      $reponse = $commentManager->deleteComment($commId);

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function flagComment($data)
  {
    if(is_array($data)){
      extract($data);
    } else {
      $myView = new View();
      $myView->redirect('notFound');
    }
    $commentManager = new CommentManager();
    $succes = $commentManager->flagComment($commentId,$_SESSION['user_session']['user_pseudo']);
    if($succes){
      $postId .= '#comment'.$commentId;
      $_SESSION['comment']['flag'] = $commentId;
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function editProfile()
  {
    if(empty($_SESSION['user_session']['user_id'])){
      $myView = new View();
      $myView->redirect('notFound');
    }
    $loginManager = new LoginManager();
    $user = $loginManager->getUser($_SESSION['user_session']['user_pseudo']);

    $myView = new View('editProfile');
    $myView->render();
  }

  public function updateProfile()
  {
    if(empty($_SESSION['user_session']['user_id'])){
      $myView = new View();
      $myView->redirect('notFound');
    }
    $errors = array();
    $data = array( 'id' => $_SESSION['user_session']['user_id']);
    $userInfos = array();
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {         // si on a une image

      $tailleMax = 2097152; // octets pour 2Mo
      $extensionsValides = array('jpg','jpeg','gif','png');                     // verifier taille
      if($_FILES['avatar']['size'] > $tailleMax || $_FILES['avatar']['size'] <= 0){
        $errors['errAvatar']['size'] = 'Votre photo de profil ne doit pas depasser 2 Mo';
      }

      $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'],'.'),1));
      // strrchr: renvoyer extension du fichier à partir du point '.' /
      // substr : ignorer le caractère "1" de la chaine (le point) /
      // strtolower : tout en minuscule /
      if(!in_array($extensionUpload,$extensionsValides)){                       // verifier extensionValide
        $errors['errAvatar']['ext'] = 'Votre photo de profil doit être de format jpg, jpeg, gif ou png';
      }

      $chemin = 'public/images/avatar/'.$_SESSION['user_session']['user_id'].'.'.$extensionUpload;
      $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'],$chemin);
      if(!$resultat){                                                           // verifier chargement
        $errors['errAvatar']['load'] = 'Erreur de chargement de photo';
      }

      if (empty($errors)) {
        $image = $_SESSION['user_session']['user_id'].'.'.$extensionUpload;
        $userInfos += ['image' => $image];
        $_SESSION['user_session']['user_avatar'] = $_SESSION['user_session']['user_id'].'.'.$extensionUpload;
      }
    }

    if ($_POST['pseudo'] !== $_SESSION['user_session']['user_pseudo']){         // verif pseudo
      $data += ['pseudo' => htmlspecialchars($_POST['pseudo'])];
    }
    if ($_POST['email'] !== $_SESSION['user_session']['user_email']){           // verif email
      $data += ['email' => htmlspecialchars($_POST['email'])];
    }
    if(!empty(htmlspecialchars($_POST['password']))){                           //verif password
      if(strlen($_POST['password']) >= 6){ // min 6 caractères
        $hashedPw = password_hash($_POST['password'],PASSWORD_DEFAULT,['cost' => 12]);
      } else {
        $data += ['errPassword' => 'Le mot de passe doit avoir au moins 6 caractères.'];
      }
    } else {
      $hashedPw = null;
    }
    if ($_POST['password'] !== $_POST['password2']){                            // verif pw = pw2
      $data += ['errPassword2' => 'Les mots de passe ne se correspondent pas.'];
    }

    $newUser = new User($data);
    $errors += $newUser->getErrors();

    if(empty($errors)){
      $userInfos += array(
        'id'        => $_SESSION['user_session']['user_id'],
        'pseudo'    => $_POST['pseudo'],
        'email'     => $_POST['email']
      );
      if(!empty($hashedPw)){
        $userInfos += ['mdp' => $hashedPw];
      }

      $loginManager = new LoginManager();
      $loginManager->userUpdate($userInfos);
      $_SESSION['user_session']['user_pseudo'] = $_POST['pseudo'];
      $_SESSION['user_session']['user_email'] = $_POST['email'];
      $errors += ['errClear' => '  Succès ! Le profil a été mis à jour.'];
    }
    $_SESSION['error'] = $errors;

    $myView = new View();
    $myView->redirect('editProfile');
  }
}
