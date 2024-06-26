<?php
class PostManager extends Manager{
  public function getPosts($limits = null){
    if(isset($limits)) extract($limits); //$limits = $limFrom,$limTo
    $db = $this->dbConnect();
    if(isset($limits)){
      $req = $db->prepare('SELECT id, title, content, image, creation_date FROM posts ORDER BY creation_date DESC LIMIT '.$limFrom.','.$limTo);
    } else {
      $req = $db->prepare('SELECT id, title, content, image, creation_date FROM posts ORDER BY creation_date DESC');
    }
    $req->execute();
    $posts = $req->fetchAll();
    $arrObjet = array();
    foreach ($posts as $post) {
      $monObjet = new Post($post);
      array_push($arrObjet,$monObjet);
    }

    return $arrObjet;
  }

  public function getPost($postId){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, title, content, image, creation_date FROM posts WHERE id = :id');
    $req->bindValue(':id',$postId);
    $req->execute();
    $data = $req->fetch();
    if($data){
      $objPost = new Post($data);
    } else {
      $objPost = 404;
    }

    return $objPost;
  }

  public function newPost($data)
  {
    extract($data);
    $db = $this->dbConnect();
    $req = $db->prepare('INSERT INTO posts(title, content, image, creation_date) VALUES (:title,:content,:image, NOW())');
    $req->bindValue(':title',$title);
    $req->bindValue(':content',$content);
    $req->bindValue(':image',$image);
    $req->execute();
  }

  public function postUpdate($data)
  {
    extract($data);
    $db = $this->dbConnect();
    if(empty($image)){
      $req = $db->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id');
    } else {
      $req = $db->prepare('UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id');
    }
    $req->bindValue(':id',$id);
    $req->bindValue(':title',$title);
    $req->bindValue(':content',$content);
    if (!empty($image)) {
      $req->bindValue(':image',$image);
    }
    $req->execute();
  }

  public function delPost($id)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('DELETE FROM posts WHERE id = :id');
    $req->bindValue(':id',$id);
    $req->execute();
  }
}
