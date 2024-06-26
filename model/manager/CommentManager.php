<?php
class CommentManager extends Manager{
  public function getComments($postId){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, post_id, author, author_id, comment, date_com FROM comments WHERE post_id = :id ORDER BY date_com');
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
  }

  public function deleteComment($id){
    $db = $this->dbConnect();
    $deleted = $db->prepare('DELETE FROM comments WHERE id = :id');
    $deleted->bindValue(':id',$id);
    $del = $deleted->execute();

    return $del;
  }

  public function flagComment($commentId, $flagger){
    $db = $this->dbConnect();

    $check = $db->prepare('UPDATE comments SET flag = :flag WHERE id = :id');
    $check->bindValue(':flag',1);
    $check->bindValue(':id',$commentId);
    $check->execute();

    $comments = $db->prepare('INSERT INTO flagged(comment_id, flagger, flag_date) VALUES(:commentId, :flagger, NOW())');
    $comments->bindValue(':commentId',$commentId);
    $comments->bindValue(':flagger',$flagger);
    $affectedLines = $comments->execute();

    return $affectedLines;
  }

  public function verifyAuthor($commId,$author){
    $db = $this->dbConnect();

    $req = $db->prepare('SELECT author_id FROM comments WHERE id = :id');
    $req->bindValue(':id',$commId);
    $req->execute();
    $check = $req->fetch();
    $authorId = (int)$check[0];

    if($authorId === (int)$author){
      return (bool) true;
    } else {
      return (bool) false;
    }
  }

  public function getLast($num = null)
  {
    $db = $this->dbConnect();
    if (isset($num)){
      $req = $db->prepare('SELECT id, post_id, author, author_id, comment, date_com FROM comments ORDER BY date_com DESC LIMIT '.(int)$num);
    } else {
      $req = $db->prepare('SELECT id, post_id, author, author_id, comment, date_com FROM comments ORDER BY date_com');
    }
    $req->execute();
    $comments = $req->fetchAll();
    $arrObjet = array();
    foreach ($comments as $comment) {
      $monObjet = new Comment($comment);
      array_push($arrObjet,$monObjet);
    }

    return $arrObjet;
  }

  public function getFlagged()
  {
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id,comment_id,flagger,DATE_FORMAT(flag_date, "%d/%m/%Y à %H:%i") AS flag_date_fr FROM flagged ORDER BY flag_date DESC');
    $req->execute();

    return $req->fetchAll();
  }

  public function getComment($id)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT * FROM comments WHERE id = :id');
    $req->bindValue(':id',$id);
    $req->execute();

    return $req->fetch();
  }

  public function unflag($id)
  {
    $db = $this->dbConnect();
    $reqComm = $db->prepare('UPDATE comments SET flag = :flag WHERE id = :id');
    $reqComm->bindValue(':flag',0);
    $reqComm->bindValue(':id',$id);
    $reqComm->execute();

    $reqFlag = $db->prepare('DELETE FROM flagged WHERE comment_id = :id');
    $reqFlag->bindValue(':id',$id);
    $reqFlag->execute();
  }
}
