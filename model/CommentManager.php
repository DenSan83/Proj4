<?php
namespace OpenClassrooms\Blog\Model;
require_once("model/Manager.php");
class CommentManager extends Manager{
  public function getComments($postId){
    $db = $this->dbConnect();

    $comments = $db->prepare('SELECT id, author, comment, DATE_FORMAT(date_com, "%d/%m/%Y à %H:%i") AS comment_date_fr FROM comments WHERE post_id = ? ORDER BY date_com');
    $comments->execute(array($postId));

    return $comments;
  }

  public function linkComments($commentId){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT post_id FROM comments WHERE id = ?');
    $req->execute(array($commentId));
    $commentsNum = $req->fetch();
    $data = $commentsNum['post_id'];

    return $data;
  }

  public function postComment($postId, $author, $comment){
    $db = $this->dbConnect();
    $comments = $db->prepare('INSERT INTO comments(post_id, author, comment, date_com) VALUES(?, ?, ?, NOW())');
    $affectedLines = $comments->execute(array($postId, $author, $comment));

    return $affectedLines;
  }

  public function commentUpdate($commentId,$commentContent){
    $db = $this->dbConnect();
    $comments = $db->prepare('UPDATE comments SET comment = :comment WHERE id = :id');
    $comments->execute(array(
      'id' => $commentId,
      'comment' => $commentContent
    ));

    $db = $this->dbConnect();
    $req = $db->prepare('SELECT post_id FROM comments WHERE id = ?');
    $req->execute(array($commentId));
    $post = $req->fetch();

    return $post['post_id'];
  }
}
