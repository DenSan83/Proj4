<?php
class Comment
{
  private $_id,
          $_post_id,
          $_author,
          $_author_id,
          $_comment,
          $_comment_date_fr;

  public function getId()       { return $this->_id; }
  public function getPostId()   { return $this->_post_id; }
  public function getAuthor()   { return $this->_author; }
  public function getAuthorId() { return $this->_author_id; }
  public function getComment()  { return $this->_comment; }
  public function getDateCom()  { return $this->_comment_date_fr; }

  public function __construct($data)
  {
    $this->hydrate($data);
  }

  public function hydrate(array $data)
  {
    foreach($data as $key => $value)
    {
      $method = 'set'.ucfirst($key);

      if(method_exists($this, $method))
        $this->$method($value);
    }
  }

  public function setId($id)
  {
    $id = (int) $id;
    if($id > 0)
      $this->_id = $id;
  }

  public function setPost_id($postId)
  {
    $postId = (int) $postId;
    if($postId > 0)
      $this->_post_id = $postId;
  }

  public function setAuthor($author)
  {
    if(is_string($author))
      $this->_author = htmlspecialchars($author);
  }

  public function setAuthor_id($authorId)
  {
    $authorId = (int) $authorId;
    if($authorId > 0)
      $this->_author_id = $authorId;
  }

  public function setComment($comment)
  {
     $this->_comment = nl2br(htmlspecialchars($comment));
  }

  public function setComment_date_fr($date)
  {
    $this->_comment_date_fr = $date;
  }

  public function getDateFr()
  {
    $dateFr = explode(' ',$this->_comment_date_fr);
    $heure = $dateFr[2];

    $date = explode('/',$dateFr[0]);
    switch($date[1]){
      case 1:
        $mois = 'janvier';
        break;
      case 2:
        $mois = 'février';
        break;
      case 3:
        $mois = 'mars';
        break;
      case 4:
        $mois = 'avril';
        break;
      case 5:
        $mois = 'mai';
        break;
      case 6:
        $mois = 'juin';
        break;
      case 7:
        $mois = 'juillet';
        break;
      case 8:
        $mois = 'août';
        break;
      case 9:
        $mois = 'septembre';
        break;
      case 10:
        $mois = 'octobre';
        break;
      case 11:
        $mois = 'novembre';
        break;
      case 12:
        $mois = 'décembre';
        break;
    }
    $readable = $date[0].' '.$mois.' '.$date[2].' à '.$heure;
    return $readable;
  }
}