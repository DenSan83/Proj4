<?php
class CommentManager extends Manager{
  public function getComments($postId){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, post_id, author, author_id, comment, DATE_FORMAT(date_com, "%d/%m/%Y à %H:%i") AS comment_date_fr FROM comments WHERE post_id = :id ORDER BY date_com');
    $req->bindValue(':id',$postId);
    $req->execute();
    $comments = $req->fetchAll();
    $arrObjet = array();
    foreach ($comments as $comment) {
      $monObjet = new Comment($comment);
      array_push($arrObjet,$monObjet);
    }

    return $arrObjet;
  }

  public function postComment($postId, $author, $authorId, $comment){
    $db = $this->dbConnect();
    $comments = $db->prepare('INSERT INTO comments(post_id, author, author_id, comment, date_com) VALUES(:postId, :author, :authorId, :comment, NOW())');
    $comments->bindValue(':postId',$postId);
    $comments->bindValue(':author',$author);
    $comments->bindValue(':authorId',$authorId);
    $comments->bindValue(':comment',$comment);
    $affectedLines = $comments->execute();

    return $affectedLines;
  }

  public function commentUpdate($commentId,$commentContent){
    $db = $this->dbConnect();
    $comments = $db->prepare('UPDATE comments SET comment = :comment WHERE id = :id');
    $comments->bindValue(':id',$commentId);
    $comments->bindValue(':comment',$commentContent);
    $comments->execute();

    $db = $this->dbConnect();
    $req = $db->prepare('SELECT post_id FROM comments WHERE id = ?');
    $req->execute(array($commentId));
    $post = $req->fetch();

    return $post['post_id'];
  }

  public function deleteComment($id){
    $db = $this->dbConnect();
    $deleted = $db->prepare('DELETE FROM comments WHERE id = :id');
    $deleted->bindValue(':id',$id);
    $del = $deleted->execute();
//user_id = $_session['user']
    return $del;
  }

  public function flagComment($commentId, $flagger){
    $db = $this->dbConnect();

    $comments = $db->prepare('INSERT INTO flagged(comment_id, flagger, flag_date) VALUES(:commentId, :flagger, NOW())');
    $comments->bindValue(':commentId',$commentId);
    $comments->bindValue(':flagger',$flagger);
    $affectedLines = $comments->execute();

    return $affectedLines;
  }
}
