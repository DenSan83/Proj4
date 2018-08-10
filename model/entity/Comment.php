<?php
class Comment
{
  private $_id,
          $_post_id,
          $_author,
          $_author_id,
          $_comment,
          $_flag,
          $_date_com;

  public function getId()       { return $this->_id; }
  public function getPostId()   { return $this->_post_id; }
  public function getAuthor()   { return $this->_author; }
  public function getAuthorId() { return $this->_author_id; }
  public function getComment()  { return $this->_comment; }
  public function getFlag()     { return $this->_flag; }
  public function getDateCom()  {
    setlocale(LC_TIME,'fr');
    $date = utf8_encode(strftime('%d %B %Y &agrave; %Hh%M',strtotime($this->_date_com)));
    return $date;
  }

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

  public function setFlag($flag)
  {
    $this->_flag = (bool) $flag;
  }

  public function setDate_com($date)
  {
    $this->_date_com = $date;
  }
}
