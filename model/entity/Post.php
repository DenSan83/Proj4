<?php
class Post
{
  private $_id,
          $_title,
          $_content,
          $_image,
          $_creation_date,
          $_errors = array();

  public function getId()           { return $this->_id; }
  public function getTitle()        { return $this->_title; }
  public function getContent()      { return $this->_content; }
  public function getImage()        { return $this->_image; }
  public function getErrors()       { return $this->_errors; }
  public function getCreationDate() {
    setlocale(LC_TIME,'fr');
    $date = utf8_encode(strftime('%d %B %Y &agrave; %Hh%M',strtotime($this->_creation_date)));
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

  public function setTitle($title)
  {
    if(is_string($title)){
      if (strlen($title) >= 75 || strlen($title) <= 0 || ctype_space($title)){
        $this->setErrors('errTitle','Le titre doit avoir entre 1 et 75 caractères.');
      } else {
        $this->_title = htmlspecialchars($title);
      }
    } else {
      $this->setErrors('errTitle','Le titre doit être en format texte.');
    }
  }

  public function setContent($content)
  {
    if ($content !== ''){
      $this->_content = nl2br(htmlspecialchars($content));
    } else {
      $this->setErrors('errContent','Le contenu du post est vide');
    }
  }

  public function setImage($image)
  {
    $this->_image = $image;
  }

  public function setCreation_date($date)
  {
    $this->_creation_date = $date;
  }

  public function getShortContent($max_words)
  {
    $max_words = (int) $max_words;
    $content_array = explode(' ',$this->getContent());

    if(count($content_array) > $max_words && $max_words > 0){
      $shorted = array_slice($content_array, 0, $max_words);
      $excerpt = implode(' ',$shorted).'...';
    } else {
      $excerpt = $this->_content;
   }

   return $excerpt;
  }

  public function setErrors($key,$error)
  {
    $errorList = $this->getErrors();
    $errorList += [ $key => $error];

    $this->_errors += $errorList;
  }
}
