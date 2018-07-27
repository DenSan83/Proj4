<?php
class User
{
  private $_id,
          $_pseudo,
          $_email,
          $_mdp,
          $_avatar,
          $_status;

  public function getId()     { return $this->_id; }
  public function getPseudo() { return $this->_pseudo; }
  public function getEmail()  { return $this->_email; }
  public function getMdp()    { return $this->_mdp; }
  public function getAvatar() { return $this->_avatar; }
  public function getStatus() { return $this->_status; }

  public function __construct($data)
  {
    $this->hydrate($data);
  }

  public function hydrate(array $data)
  {
    foreach($data as $key => $value)
    {
      $method = 'set'.ucfirst($key);

      if(method_exists($this,$method))
        $this->$method($value);
    }
  }

  public function setId($id)
  {
    $id = (int) $id;
    if($id > 0)
      $this->_id = $id;
  }

  public function setPseudo($pseudo)
  {
    if(is_string($pseudo))
      $this->_pseudo = htmlspecialchars($pseudo);
  }

  public function setEmail($email)
  {
    $this->_email = filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  public function setMdp($mdp)
  {
    $this->_mdp = $mdp;
  }

  public function setAvatar($avatar)
  {
    $this->_avatar = $avatar;
  }

  public function setStatus($status)
  {
    $this->_status = $status;
  }
}
