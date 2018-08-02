<?php
require_once('public/recaptchalib.php');

class FrontController
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
          $myView->redirect('post/id/'.$postId.'#'.$comment['id']);
        }
      } else {
        $parametres += ['noCaptcha' => 1];
        $this->post($parametres);
      }
    }
  }

  public function newUser($errors = null)
  {
    if (!isset($errors)){
      $myView = new View('newUser');
      $myView->render();
      exit();
    }
    $data = array();

    if(empty($_POST['pseudo'])) {                                               // verif pseudo
      $data += ['errPseudo' => 'Veuillez choisir un nom d\'utilisateur !'];
    } else {
      $data += ['pseudo' => $_POST['pseudo']];
    }

    if(empty($_POST['email'])) {                                                // verif email
      $data += ['errEmail' => 'Veuillez renseigner un adresse email !'];
    } else {
      $data += ['email' => $_POST['email']];
    }
    if(empty($_POST['email2'])) {                                               // verif email2
      $data += ['errEmail2' => 'Veuillez confirmer votre adresse email !'];
    } else {
      if($_POST['email'] !== $_POST['email2']){
        $data += ['errEmail2' => 'Les deux adresses email ne se correspondent pas !'];
      }
    }

    if(empty($_POST['password'])) {                                             // verif password
      $data += ['errPassword' => 'Veuillez introduire un mot de passe !'];
    } else {
      if(strlen($_POST['password']) >= 6){ // min 6 caractères
        $hashedPw = password_hash($_POST['password'],PASSWORD_DEFAULT,['cost' => 12]);
      } else {
        $data += ['errPassword' => 'Le mot de passe doit avoir au moins 6 caractères.'];
      }
    }
    if(empty($_POST['password2'])) {                                            // verif password2
      $data += ['errPassword2' => 'Veuillez confirmer votre mot de passe !'];
    } else {
      if ($_POST['password'] !== $_POST['password2']){
        $data += ['errPassword2' => 'Les mot de passe ne se correspondent pas'];
      }
    }

    $newUser = new User($data);
    $arrErrors = $newUser->getErrors();

    if(empty($arrErrors)){
      $loginManager = new LoginManager();
      $errClear = $loginManager->newUser(array(
        'pseudo'    => $_POST['pseudo'],
        'email'     => $_POST['email'],
        'password'  => $hashedPw
      ));
      $arrErrors += ['errClear' => '<b>'.$_POST['pseudo'].'</b>, votre compte a été crée.'];
    }
    $myView = new View('newUser');
    $myView->render($arrErrors);
  }
}
