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

      $myView = new View('postView');
      $parametres = array('post' => $post,'comments' => $comments);
      if (isset($noCaptcha))
      {
        $parametres = array('post' => $post,'comments' => $comments, 'noCaptcha' => $noCaptcha);
      }
      if (isset($noUser))
      {
        $parametres = array('post' => $post,'comments' => $comments, 'noUser' => $noUser);
      }
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
            $affectedLines = $commentManager->postComment($postId,$author,$comment);
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
      $affectedLines = $commentManager->postComment($postId,$pseudo,$comment);
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

    $postExist = (bool) $postManager->getPost($postId);
    $commExist = $commentManager->linkComments($commId); // when "true" returns post_id

    if ($postId > 0 && $commId > 0 && $postExist && $commExist)
    {
      if($commExist !== $postId) $postId = $commExist;
      $post = $postManager->getPost($postId);
      $comments = $commentManager->getComments($postId);

      $myView = new View('postView');
      $myView->render(array('post' => $post,'comments' => $comments, 'commId' => $commId));
    } else {
      echo 'Impossible trouver le commentaire';
    }
  }

  public function commentUpdate($params)
  {
    extract($params);
    $commentManager = new CommentManager();
    $modified = $commentManager->commentUpdate($commentId,$updated);

    if ($modified > 0) {
      $myView = new View('postView');
      $myView->redirect($modified);
    } else {
      throw new Exception('Post non trouvé');
    }
  }

  public function deleteComment($data)
  {
    extract($data);
    $commentManager = new CommentManager();
    $reponse = $commentManager->deleteComment($commId);

    $myView = new View('postView');
    $myView->redirect($postId);
  }

  public function login($params)
  {
    var_dump($params);exit();
    extract($params);

    if (isset($login))
    {
      $login = htmlspecialchars($login);
      $password = htmlspecialchars($password);

      if (!empty($password) && !empty($login))
      {
        $reqlogin = new LoginManager;
        $userExist = (bool) $reqlogin->loginCount(array('login' => $login, 'password' => $password));
        //$verify = password_very($password, [mot de passe hachée]);
        if ($userExist) //ici
        {
          // Tous les test passées, on fais login session
          $userinfo = $reqlogin->login(array('login' => $login, 'password' => $password));
          $reqlogin->loggedIn($userinfo);
          $myView = new View('postView');
          if ($postId !== null){
            $myView->redirect($postId);
          } else {
            $myView->goHome();
          }
         } else {
           header('Location: '.HOST.'post/id/'.$postId.'/noUser/1#optionsSession');
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
}
