<?php
require_once('public/recaptchalib.php');

class Home
{
  public function listPosts()
  {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    $myView = new View('postsList');
    $myView->render($posts);
  }

  public function post($params)
  {
    extract($params); //var_dump($params);exit();
    $postManager = new PostManager();
    $idExist = (bool) $postManager->getPost($id);
    if ($id > 0 && $idExist) {
      $commentManager = new CommentManager();
      $post = $postManager->getPost($id);
      $comments = $commentManager->getComments($id);

      $avatarList = array();
      $elmZero = array('avatar' => 'default.png','status' => 'visiteur' );
      $avatarList += [ 0 => $elmZero];
      $loginManager = new LoginManager();
      for ($i = 1; $i <= $loginManager->usersCount(); $i++)
      {
        $userAv = $loginManager->getAvatar($i);
        $avatarList += [$i => $userAv];
      }

      $myView = new View('postView');
      $parametres = array('post' => $post,'comments' => $comments, 'avatarList' => $avatarList);
      if (isset($noCaptcha)){
        $noCaptcha = 'Veuillez completer le Captcha !';
        $parametres += ['commentError' => $noCaptcha];
      }
      if (isset($noFields)){
        $noFields = 'Tous les champs doit etre remplis !';
        $parametres += ['commentError' => $noFields];
      }
      if (isset($impossible)){
        $impossible = 'Impossible d\'ajouter le commentaire !';
        $parametres += ['commentError' => $impossible];
      }
      if (isset($noUser))
        $parametres += ['noUser' => $noUser];

      $myView->render($parametres);
    } else {
      echo '404';
    }
  }

  public function addComment($params)
  {
    extract($params);
    $secret = '6LeVFmQUAAAAAGmnuGggo4GYbkxK-ejGajGOjFJd'; // clé privée captcha
    $reCaptcha = new ReCaptcha($secret);
    if(isset($_POST["g-recaptcha-response"]))
    {
      $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
      );
      $parametres = array('id' => $postId);
      if ($resp != null && $resp->success)
      {
        $author = $prenom.' '.$nom;
        if (!empty($author) && !empty($comment)) {
          $commentManager = new CommentManager();
          $affectedLines = $commentManager->postComment($postId,$author,$authorId, $comment);
        } else {
          $parametres += ['noFields' => 1];
          $this->post($parametres);
        }

        if ($affectedLines == false) {
          $parametres += ['impossible' => 1];
          $this->post($parametres);
        } else {
          $myView = new View('postView');
          $myView->redirect($postId.'#'.$comment['id']);
        }
      } else {
        $parametres += ['noCaptcha' => 1];
        $this->post($parametres);
      }
    }
  }

  public function onlineComment($params)
  {
    extract($params);
    if (!empty($pseudo) && !empty($comment)) {
      $commentManager = new CommentManager();
      $affectedLines = $commentManager->postComment($postId,$pseudo,$authorId,$comment);
    }

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    } else {
      $myView = new View('postView');
      $myView->redirect($postId);
    }
  }

  public function modifyComment($params)
  {
    extract($params);
    $postManager = new PostManager();
    $commentManager = new CommentManager();

    $post = $postManager->getPost($postId);
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
    $myView->redirect($modified);
  }

  public function deleteComment($data)
  {
    extract($data); //if (!empty($data))
    $commentManager = new CommentManager();
    $check = $commentManager->verifyAuthor($commId,$_SESSION['user_session']['user_id']);
    if($check)
      $reponse = $commentManager->deleteComment($commId);

    $myView = new View('postView');
    $myView->redirect($postId);
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
    $myView->redirect($postId);
  }

  public function login($params)
  {
    extract($params); //$params = array($login,$password)
    if (isset($login))
    {
      $login = htmlspecialchars($login);
      $password = htmlspecialchars($password);

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

          $myView = new View('postView');
          if ($postId !== null){
            $myView->redirect($postId);
          } else {
            $myView->goHome();
          }
        } else {
          // utilisateur inexistant
          $_SESSION['noUser'] = 1;
        }
      }
    }
  }

  public function admin()
  {
    if ($_SESSION['user_session']['user_status'] == 'admin'){
      //get last comments
      $commentManager = new CommentManager();
      $lastComments = $commentManager->getLast(5);

      //get flagged
      
      //envelopper dans array

      $params = array('lastComments' => $lastComments);
      $myView = new View('adminView');
      $myView->render($params);
    } else {
      header('Location: '.HOST);
    }
  }
  public function logout()
  {
    session_destroy();

    $myView = new View('postLists');
    $myView->goHome();
  }

  public function newUser($errorList = null)
  {
    if (!isset($errorList)){
      $myView = new View('newUser');
      $myView->render();
      exit();
    }
    $errorList = array();

    if(empty($_POST['pseudo'])) {
      $errPseudo = 'Veuillez choisir un nom d\'utilisateur !';
      $errorList += ['errPseudo' => $errPseudo];
    } else {
      $loginManager = new LoginManager();
      $pseudoExist = $loginManager->pseudoCheck($_POST['pseudo']);
      if ($pseudoExist !== 0) {
        $errPseudo = 'Le nom d\'utilisateur est déjà enregistré. Veuillez choisir un nom d\'utilisateur different';
        $errorList += ['errPseudo' => $errPseudo];
      }
    }

    if(empty($_POST['email'])) {
      $errEmail = 'Veuillez renseigner un adresse email !';
      $errorList += ['errEmail' => $errEmail];
    } else {
      $loginManager = new LoginManager();
      $emailExist = $loginManager->emailCheck($_POST['email']);
      if ($emailExist !== 0) {
        $errEmail = 'L\'adresse email renseigné est déjà enregistré. Veuillez vous enregistrer avec un email different';
      } else {
        if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
          $errEmail = 'Veuillez renseigner un adresse email valide !';
          $errorList += ['errEmail' => $errEmail];
        }
      }
    }

    if(empty($_POST['email2'])) {
      $errEmail2 = 'Veuillez confirmer votre adresse email !';
      $errorList += ['errEmail2' => $errEmail2];
    } else {
      if($_POST['email'] !== $_POST['email2']){
        $errEmail2 = 'Les deux adresses email ne se correspondent pas !';
        $errorList += ['errEmail2' => $errEmail2];
      }
    }

    if(empty($_POST['password'])) {
      $errPassword = 'Veuillez introduire un mot de passe !';
      $errorList += ['errPassword' => $errPassword];
    }
    if(empty($_POST['password2'])) {
      $errPassword2 = 'Veuillez confirmer votre mot de passe !';
      $errorList += ['errPassword2' => $errPassword2];
    } else {
      if ($_POST['password'] !== $_POST['password2']){
        $errPassword2 = 'Les mot de passe ne se correspondent pas';
        $errorList += ['errPassword2' => $errPassword2];
      }
    }

    if (empty($errorList)) {
      $loginManager = new LoginManager();
      $errClear = $loginManager->newUser(array(
        'pseudo'    => $_POST['pseudo'],
        'email'     => $_POST['email'],
        'password'  => password_hash($_POST['password'],PASSWORD_DEFAULT)
      ));

      if($errClear) {
        $errClear = '<b>'.$_POST['pseudo'].'</b>, votre compte a été crée.';
        $errorList += ['errClear' => $errClear];
      } else {
        $errClear = 'Il y a eu un problème de connection, veuillez reesayer ultérieurement';
      }

    }
    $myView = new View('newUser');
    $myView->render($errorList);
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
    $errorList = array();
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
      $tailleMax = 2097152; // octets pour 2Mo
      $extensionsValides = array('jpg','jpeg','gif','png');
      if($_FILES['avatar']['size'] <= $tailleMax && $_FILES['avatar']['size'] != 0){
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'],'.'),1));
        // strchr: renvoyer extension du fichier après point '.' /
        // substr : ignorer le caractère "1" de la chaine /
        // strtolower : tout en minuscule /
        if(in_array($extensionUpload,$extensionsValides)){ // voir si l'extensionUpload contient un extensionValide
          $chemin = 'public/images/avatar/'.$_SESSION['user_session']['user_id'].'.'.$extensionUpload;
          $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'],$chemin);
          if($resultat){
            $loginManager = new LoginManager();

            $_SESSION['user_session']['user_avatar'] = $_SESSION['user_session']['user_id'].'.'.$extensionUpload;
            $loginManager->avatarUpdate($_SESSION['user_session']['user_avatar']);
            $user = $loginManager->getUser($_SESSION['user_session']['user_pseudo']);
          } else {
            $errAvatar = 'Erreur de chargement de photo';
          }
        } else {
          $errAvatar = 'Votre photo de profil doit être de format jpg, jpeg, gif ou png';
        }
      } else {
        $errAvatar = 'Votre photo de profil ne doit pas depasser 2 Mo';
      }
      if(isset($errAvatar)) $errorList += ['errAvatar' => $errAvatar];
    } else {

      //verifier si pseudo existe:
      $loginManager = new LoginManager();
      if ($_POST['pseudo'] !== $_SESSION['user_session']['user_pseudo']){
        $pseudoExist = (bool)$loginManager->pseudoCheck($_POST['pseudo']);
        if ($pseudoExist){
          $errPseudo = 'Ce nom d\'utilisateur est déjà pris. Veuillez choisir un autre';
          $errorList += ['errPseudo' => $errPseudo];
        }
      }
      //verifier si email existe:
      if ($_POST['email'] !== $_SESSION['user_session']['user_email']){
        $emailExist = (bool)$loginManager->emailCheck($_POST['email']);
        if ($emailExist){
          $errEmail = 'Cet adresse email est déjà pris. Veuillez renseigner un autre';
          $errorList += ['errEmail' => $errEmail];
        }
      }
      //verifier si password = valide:
      //verifier si pw = pw2:
      if ($_POST['password'] !== $_POST['password2']){
        $errPassword2 = 'Les mots de passe ne se correspondent pas';
        $errorList += ['errPassword2' => $errPassword2];
      }
    }
      if(empty($errorList)){
        $loginManager->userUpdate(array(
          'id'        => $_SESSION['user_session']['user_id'],
          'pseudo'    => $_POST['pseudo'],
          'email'     => $_POST['email'],
          'password'  => password_hash($_POST['password'],PASSWORD_DEFAULT)
        ));
        $_SESSION['user_session']['user_pseudo']  = $_POST['pseudo'];
        $_SESSION['user_session']['user_email']   = $_POST['email'];

        $errClear = '  Succès ! Le profil a été mis à jour.';
        $errorList += ['errClear' => $errClear];
      }
      $myView = new View ('editProfile');
      $myView->render($errorList);
  }
}
