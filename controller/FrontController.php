<?php
require_once('public/recaptchalib.php');

class FrontController
{
  public function listPosts()
  {
    $postManager = new PostManager();
    $posts = $postManager->getPosts(array(
      'limFrom' => 1,
      'limTo' => 3
    ));
    $last = $postManager->getPosts(array(
      'limFrom' => 0,
      'limTo' =>1
    ));
    $data = array('posts' => $posts, 'last' => $last);

    $myView = new View('listPostsView');
    $myView->render($data);
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
      $idList = $loginManager->idList();

      foreach ($idList as $id => $value) {
        $value = (int) $value;
        $userAv = $loginManager->getAvatar($value);
        $avatarList += [$value => $userAv];
      }

      $myView = new View('postView');
      $parametres = array('post' => $post,'comments' => $comments, 'avatarList' => $avatarList);
      if (isset($noUser))
        $parametres += ['noUser' => $noUser];
      if (isset($success))
        $parametres += ['success' => $success];

      $myView->render($parametres);
    } else {
      echo '404';
    }
  }

  public function addComment($params)
  {
    extract($params);
    $errors = array();
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
        if(empty($prenom) || empty($nom) || empty($comment)){
          $errors += ['errcomment' =>'Veuillez remplir tous les champs'];
        }
        if(!preg_match("#^\D+$#",$nom)){
          $errors += ['errCommentNom' => 'Les chiffres ne sont pas autorisées au nom !'];
        }
        if(!preg_match("#^\D+$#",$prenom)){
          $errors += ['errCommentPrenom' => 'Les chiffres ne sont pas autorisées au prénom !'];
        }
        if(strlen($comment) > 260){
          $errors += ['errCommentContent' => 'Le commentaire ne doit pas dépasser les 260 caractères'];
        }

        if (!isset($errors)){
          $author = $prenom.' '.$nom;
          $authorId = null;
          $commentManager = new CommentManager();
          $commentManager->postComment($postId,$author,$authorId, $comment);
          $_SESSION['comment']['success'] = 1;
        }
      } else {
        $errors += ['errCommentCaptcha' => 'Veuillez completer le Captcha !'];
      }var_dump($errors);exit();
      $_SESSION['comment']['error'] = $errors;
      $myView = new View('postView');
      $myView->redirect('post/id/'.$postId);
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

  public function showPosts()
  {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    $myView = new View('showPosts');
    $myView->render($posts);
  }
}
