<?php

require_once('model/PostManager.php');
require_once('model/CommentManager.php');
require_once('classes/View.php');

class Home
{
  public function listPosts(){
    $postManager = new \OpenClassrooms\Blog\Model\PostManager();
    $posts = $postManager->getPosts();

    $myView = new View;
    $myView->listPosts(array('posts' => $posts));
  }

  public function post($params){
    extract($params);
    $postManager = new \OpenClassrooms\Blog\Model\PostManager();
    $idExist = (bool) $postManager->getPost($id);
    if ($id > 0 && $idExist) {
      $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
      $post = $postManager->getPost($id);
      $comments = $commentManager->getComments($id);

      $myView = new View;
      $myView->postView(array('post' => $post,'comments' => $comments));
    } else {
      echo '404';
    }
  }

  public function addComment($params) {
    extract($params);
    if (!empty($author) && !empty($comment)) {
      $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
      $affectedLines = $commentManager->postComment($postId,$author,$comment);
    } else {
      echo 'Veuillez remplir tous les champs';
    }

    if ($affectedLines === false) {
        throw new Exception('Impossible d\'ajouter le commentaire !');
    } else {
      $myView = new View;
      $myView->redirect($postId);
    }
  }

  public function modifyComment($params){
    extract($params);
    $postManager = new \OpenClassrooms\Blog\Model\PostManager();
    $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();

    $postExist = (bool) $postManager->getPost($postId);
    $commExist = $commentManager->linkComments($commId); // when "true" returns post_id

    if ($postId > 0 && $commId > 0 && $postExist && $commExist)
    {
      if($commExist !== $postId) $postId = $commExist;
      $post = $postManager->getPost($postId);
      $comments = $commentManager->getComments($postId);

      $myView = new View;
      $myView->postView(array('post' => $post,'comments' => $comments, 'commId' => $commId));
    } else {
      echo 'Impossible trouver le commentaire';
    }
  }

  public function commentUpdate($params){
    extract($params);
    var_dump($params); //exit();
    $commentManager = new \OpenClassrooms\Blog\Model\CommentManager();
    $modified = $commentManager->commentUpdate($commentId,$updated);

    if ($modified > 0) {
      $myView = new View;
      $myView->redirect($modified);
    } else {
      throw new Exception('Post non trouvé');
    }
  }
}
