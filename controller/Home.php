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
    extract($params);
    $postManager = new PostManager();
    $idExist = (bool) $postManager->getPost($id);
    if ($id > 0 && $idExist) {
      $commentManager = new CommentManager();
      $post = $postManager->getPost($id);
      $comments = $commentManager->getComments($id);

      $avatarList = array();
      $loginManager = new LoginManager();
      for ($i = 1; $i <= $loginManager->usersCount(); $i++)
      {
          $avatar = $loginManager->getAvatar($i);
          if(empty($avatar)) { $avatar = 'default.png'; }
          $avatarList += [$i => $avatar];
      }

      $myView = new View('postView');
      $parametres = array('post' => $post,'comments' => $comments, 'avatarList' => $avatarList);
      if (isset($noCaptcha))
        $parametres += ['noCaptcha' => $noCaptcha];

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
        if ($resp != null && $resp->success)
        {
          $author = $prenom.' '.$nom;
          if (!empty($author) && !empty($comment)) {
            $commentManager = new CommentManager();
            $affectedLines = $commentManager->postComment($postId,$author,$authorId, $comment);
          } else {
            echo 'Veuillez remplir tous les champs';
          }

          if ($affectedLines === false) {
              throw new Exception('Impossible d\'ajouter le commentaire !');
          } else {
            $myView = new View('postView');
            $myView->redirect($postId.'#'.$comment['id']);
          }
        } else {
          header('Location: '.HOST.'post/id/'.$postId.'/noCaptcha/1#optionsSession');
        }
    }
  }

  public function onlineComment($params)
  {
    extract($params);
    if (!empty($pseudo) && !empty($comment)) {
      $commentManager = new CommentManager();
      $affectedLines = $commentManager->postComment($postId,$pseudo,$authorId,$comment);
    } else {
      echo 'no user or no comment detected';
    }

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');exit();
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
    $modified = $commentManager->commentUpdate($commentId,$updated);
    $modified .= '#comment'.$commentId;

    $myView = new View('postView');
    $myView->redirect($modified);
  }

  public function deleteComment($data)
  {
    extract($data);
    $commentManager = new CommentManager();
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
      $password = htmlspecialchars($password); //$password = password_hash($password,PASSWORD_DEFAULT);

      if (!empty($password) && !empty($login))
      {
        $reqlogin = new LoginManager();
        $hashedPw = $reqlogin->retrievePw($login);

        if (password_verify($password, $hashedPw))
        {
          // Tous les test passées, on fais login session
          $user = $reqlogin->login(array('login' => $login, 'password' => $hashedPw));
          unset($_SESSION['noUser']);

          $_SESSION['user_session']['user_pseudo']  = $user['pseudo'];
          $_SESSION['user_session']['user_avatar']  = $user['avatar'];
          $_SESSION['user_session']['user_id']      = $user['id'];
        } else {
          $_SESSION['noUser'] = 1;
        }

        $myView = new View('postView');
        if ($postId !== null){
          $myView->redirect($postId);
        } else {
          $myView->goHome();
        }
       }
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
}
