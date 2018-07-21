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

    $myView = new View('editCommentView');
    $myView->render(array('post' => $post,'comments' => $comments, 'commentId' => $commentId));
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

  public function login($params)
  {
    extract($params); //$password = password_hash($password,PASSWORD_DEFAULT);
    if (isset($login))
    {
      $login = htmlspecialchars($login);
      $password = htmlspecialchars($password);

      if (!empty($password) && !empty($login))
      {
        $reqlogin = new LoginManager;
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
}
