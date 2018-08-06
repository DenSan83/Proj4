<?php
class PostManager extends Manager{
  public function getPosts(){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, title, content, DATE_FORMAT(creation_date, "%d/%m/%Y à %H:%i") AS creation_date_fr FROM posts ORDER BY creation_date DESC LIMIT 0, 7');
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
    $req = $db->prepare('SELECT id, title, content, DATE_FORMAT(creation_date, "%d/%m/%Y à %H:%i") AS creation_date_fr FROM posts WHERE id = :id');
    $req->bindValue(':id',$postId);
    $req->execute();
    $data = $req->fetch();
    $objPost = new Post($data);

    return $objPost;
  }

  public function newPost($postTitle,$newPost)
  {
    extract($post); // title,
    $db = $this->dbConnect();
    $req = $db->prepare('INSERT INTO posts(title, content, creation_date) VALUES (:title,:content,NOW())');
    $req->bindValue(':title',$postTitle);
    $req->bindValue(':content',$newPost);
    $req->execute();
  }
}
