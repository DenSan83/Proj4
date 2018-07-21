<?php

class LoginManager extends Manager
{
  public function loginCount($params)
  {
    extract($params);
    $db = $this->dbConnect();

    $reqlogin = $db->prepare('SELECT pseudo,mdp FROM membres WHERE pseudo = :login AND mdp = :password');
    $reqlogin->bindValue(':login',$login);
    $reqlogin->bindValue(':password',$password);
    $reqlogin->execute();
    return $reqlogin->rowcount();
  }

  public function login($params)
  {
    extract($params);
    $db = $this->dbConnect();

    $reqlogin = $db->prepare('SELECT * FROM membres WHERE pseudo = :login AND mdp = :password');
    $reqlogin->bindValue(':login',$login);
    $reqlogin->bindValue(':password',$password);
    $reqlogin->execute();

    return $reqlogin->fetch();
  }

  public function retrievePw($login)
  {
    $db = $this->dbConnect();
    $reqlogin = $db->prepare('SELECT mdp FROM membres WHERE pseudo = ?');
    $reqlogin->execute(array($login));
    $mdpHache = $reqlogin->fetch();

    return $mdpHache[0];
  }

  public function getAvatar($id)
  {
    $db = $this->dbConnect();

    $avatar = $db->prepare('SELECT avatar FROM membres WHERE id = :id');
    $avatar->bindValue(':id',$id);
    $avatar->execute();
    $pic = $avatar->fetch();

    return $pic[0];
  }
}
