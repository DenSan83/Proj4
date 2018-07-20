<?php
class Post
{
  private $_id,
          $_title,
          $_content,
          $_creation_date_fr;

  public function getId()           { return $this->_id; }
  public function getTitle()        { return $this->_title; }
  public function getContent()      { return $this->_content; }
  public function getCreationDate() { return $this->_creation_date_fr; }

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
    if(is_string($title))
      $this->_title = htmlspecialchars($title);
  }

  public function setContent($content)
  {
     $this->_content = nl2br(htmlspecialchars($content));
  }

  public function setCreation_date_fr($date)
  {
    $this->_creation_date_fr = $date;
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

  public function getDateFr()
  {
    $dateFr = explode(' ',$this->_creation_date_fr);
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
