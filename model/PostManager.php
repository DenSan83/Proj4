<?php
class PostManager extends Manager{
  public function getPosts(){
    $db = $this->dbConnect();
    $req = $db->prepare('SELECT id, title, content, image, DATE_FORMAT(creation_date, "%d/%m/%Y à %H:%i") AS creation_date_fr FROM posts ORDER BY creation_date DESC LIMIT 0, 7');
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
    $req = $db->prepare('SELECT id, title, content, image, DATE_FORMAT(creation_date, "%d/%m/%Y à %H:%i") AS creation_date_fr FROM posts WHERE id = :id');
    $req->bindValue(':id',$postId);
    $req->execute();
    $data = $req->fetch();
    $objPost = new Post($data);

    return $objPost;
  }

  public function newPost($postTitle,$newPost)
  {
    $db = $this->dbConnect();
    $req = $db->prepare('INSERT INTO posts(title, content, creation_date) VALUES (:title,:content,NOW())');
    $req->bindValue(':title',$postTitle);
    $req->bindValue(':content',$newPost);
    $req->execute();
  }

  public function postUpdate($data)
  {
    extract($data);
    $db = $this->dbConnect();
    $req = $db->prepare('UPDATE posts SET title = :title, content = :content, image = :image WHERE id = :id');
    if(empty($image))
      $req = $db->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id');
    $req->bindValue(':id',$id);
    $req->bindValue(':title',$title);
    $req->bindValue(':content',$content);
    if (!empty($image))
      $req->bindValue(':image',$image);
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
