<?php
class User
{
  private $_id,
          $_pseudo,
          $_email,
          $_mdp,
          $_avatar,
          $_status,
          $_errors = array();

  public function getId()     { return $this->_id; }
  public function getPseudo() { return $this->_pseudo; }
  public function getEmail()  { return $this->_email; }
  public function getMdp()    { return $this->_mdp; }
  public function getAvatar() { return $this->_avatar; }
  public function getStatus() { return $this->_status; }
  public function getErrors() { return $this->_errors; }

  public function __construct($data)
  {
    $this->hydrate($data);
  }

  public function hydrate(array $data)
  {
    foreach($data as $key => $value)
    {
      $method = 'set'.ucfirst($key);

      if(method_exists($this,$method)){
        $this->$method($value);
      } else {
        $this->setErrors($key,$value);
      }
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

    if(is_string($pseudo)){
      if(strpos($pseudo,' ')){
        $this->setErrors('errPseudo','Le nom d\'utilisateur ne doit pas contenir des espaces');
      } else {
        $loginManager = new LoginManager();
        $pseudoExist = (bool)$loginManager->pseudoCheck($pseudo);
        if ($pseudoExist){
          $this->setErrors('errPseudo','Ce nom d\'utilisateur est déjà pris. Veuillez choisir un autre');
        } else {
          $this->_pseudo = htmlspecialchars($pseudo);
        }
      }
    }
  }

  public function setEmail($email)
  {
    $loginManager = new LoginManager();
    $emailExist = (bool)$loginManager->emailCheck($email);
    if ($emailExist){
      $this->setErrors('errEmail','Cet adresse email est déjà pris. Veuillez renseigner un autre');
    } else {
      $valide = filter_var($email, FILTER_VALIDATE_EMAIL);
      if($valide){
        $this->_email = $email;
      } else {
        $this->setErrors('errEmail','Veuillez insérer une adresse email valide.');
      }
    }
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

  public function setErrors($key,$error)
  {
    $errorList = $this->getErrors();
    $errorList += [ $key => $error];

    $this->_errors += $errorList;
  }
}
