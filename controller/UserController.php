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
    $myView = new View('postLists');
    $myView->goHome();
  }

  public function onlineComment($params)
  {
    extract($params);
    if (empty($pseudo)){
      $_SESSION['comment']['error'] = 'Le commentaire n\'a pas été ajouté. Veuillez reesayer ulterieurement';
    }
    if(empty($comment)){
      $_SESSION['comment']['error'] = 'Veuillez ajouter un commentaire';
    }
    if (!empty($pseudo) && !empty($comment)) {
      $commentManager = new CommentManager();
      $commentManager->postComment($postId,$pseudo,$authorId,$comment);
      $_SESSION['comment']['success'] = 1;
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function modifyComment($params)
  {
    extract($params);
    $postManager = new PostManager();
    $post = $postManager->getPost($postId);
    $commentManager = new CommentManager();
    $comments = $commentManager->getComments($postId);

    $avatarList = array();
    $loginManager = new LoginManager();
    for ($i = 1; $i <= $loginManager->usersCount(); $i++)
    {
        $avatar = $loginManager->getAvatar($i);
        if(empty($avatar)) { $avatar = 'default.png'; }
        $avatarList += [$i => $avatar];
    }

    $myView = new View('editCommentView');
    $myView->render(array(
      'post' => $post,
      'comments' => $comments,
      'avatarList' => $avatarList,
      'commentId' => $commentId
    ));
  }

  public function commentUpdate($params)
  {
    extract($params);
    $commentManager = new CommentManager();
    $check = $commentManager->verifyAuthor($commentId,$_SESSION['user_session']['user_id']);
    if($check){
      $modified = $commentManager->commentUpdate($commentId,$updated);
      $modified .= '#comment'.$commentId;
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$modified);
  }

  public function deleteComment($data)
  {
    extract($data); //if (!empty($data))
    $commentManager = new CommentManager();
    $check = $commentManager->verifyAuthor($commId,$_SESSION['user_session']['user_id']);
    if($check)
      $reponse = $commentManager->deleteComment($commId);

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function flagComment($data)
  {
    extract($data); // $data = array($postId,$commentId)
    $commentManager = new CommentManager();
    $succes = $commentManager->flagComment($commentId,$_SESSION['user_session']['user_pseudo']);
    if($succes){
      $postId .= '#comment'.$commentId;
      $_SESSION['flagged'] = $commentId;
    }

    $myView = new View('postView');
    $myView->redirect('post/id/'.$postId);
  }

  public function editProfile()
  {
    $loginManager = new LoginManager();
    $user = $loginManager->getUser($_SESSION['user_session']['user_pseudo']);

    $myView = new View('editProfile');
    $myView->render();
  }

  public function updateProfile()
  {
    unset($_SESSION['update_err']);
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
      $tailleMax = 2097152; // octets pour 2Mo
      $extensionsValides = array('jpg','jpeg','gif','png');
      if($_FILES['avatar']['size'] <= $tailleMax && $_FILES['avatar']['size'] != 0){
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'],'.'),1));
        // strrchr: renvoyer extension du fichier à partir du point '.' /
        // substr : ignorer le caractère "1" de la chaine (le point) /
        // strtolower : tout en minuscule /
        if(in_array($extensionUpload,$extensionsValides)){ // voir si l'extensionUpload contient un extensionValide
          $chemin = 'public/images/avatar/'.$_SESSION['user_session']['user_id'].'.'.$extensionUpload;
          $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'],$chemin);
          if($resultat){
            $loginManager = new LoginManager();
            $_SESSION['user_session']['user_avatar'] = $_SESSION['user_session']['user_id'].'.'.$extensionUpload;
            $loginManager->avatarUpdate($_SESSION['user_session']['user_avatar']);
            $_SESSION['update_err']['errClear'] = '  Succès ! L\'avatar a été mis à jour.';
          } else {
            $errAvatar = 'Erreur de chargement de photo';
          }
        } else {
          $errAvatar = 'Votre photo de profil doit être de format jpg, jpeg, gif ou png';
        }
      } else {
        $errAvatar = 'Votre photo de profil ne doit pas depasser 2 Mo';
      }
      if(isset($errAvatar))
        $_SESSION['update_err'] = array('errAvatar' => $errAvatar);

    } else {

      $data = array();
      if ($_POST['pseudo'] !== $_SESSION['user_session']['user_pseudo']){       // verif pseudo
        $data += ['pseudo' => $_POST['pseudo']];
      }
      if ($_POST['email'] !== $_SESSION['user_session']['user_email']){         // verif email
        $data += ['email' => $_POST['email']];
      }
      if(!empty($_POST['password'])){                                           //verif password
        if(strlen($_POST['password']) >= 6){ // min 6 caractères
          $hashedPw = password_hash($_POST['password'],PASSWORD_DEFAULT,['cost' => 12]);
        } else {
          $data += ['errPassword' => 'Le mot de passe doit avoir au moins 6 caractères.'];
        }
      } else {
        $hashedPw = null;
      }
      if ($_POST['password'] !== $_POST['password2']){                          // verif pw = pw2
        $data += ['errPassword2' => 'Les mots de passe ne se correspondent pas.'];
      }

      $newUser = new User($data);
      $arrErrors = $newUser->getErrors();

      if(empty($arrErrors)){
        $userInfos = array(
          'id'        => $_SESSION['user_session']['user_id'],
          'pseudo'    => $_POST['pseudo'],
          'email'     => $_POST['email']
        );
        if(!empty($hashedPw))
          $userInfos += ['mdp' => $hashedPw];
        $loginManager = new LoginManager();
        $loginManager->userUpdate($userInfos);
        $_SESSION['user_session']['user_pseudo']  = $_POST['pseudo'];
        $_SESSION['user_session']['user_email']   = $_POST['email'];

        $arrErrors += ['errClear' => '  Succès ! Le profil a été mis à jour.'];
      }

      $_SESSION['update_err'] = $arrErrors;
    }
    $myView = new View();
    $myView->redirect('editProfile');
  }
}
