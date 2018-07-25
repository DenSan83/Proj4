<?php

class LoginManager extends Manager
{
  public function usersCount()
  {
    $db = $this->dbConnect();

    $usersCount = $db->query('SELECT * FROM membres');
    return $usersCount->rowcount();
  }

  public function pseudoCheck($pseudo)
  {
    $db = $this->dbConnect();
    $pseudoCheck = $db->prepare('SELECT * FROM membres WHERE pseudo = :pseudo');
    $pseudoCheck->bindValue(':pseudo',$pseudo);
    $pseudoCheck->execute();
    return $pseudoCheck->rowcount();
  }

  public function emailCheck($email)
  {
    $db = $this->dbConnect();
    $pseudoCheck = $db->prepare('SELECT * FROM membres WHERE email = :email');
    $pseudoCheck->bindValue(':email',$email);
    $pseudoCheck->execute();
    return $pseudoCheck->rowcount();
  }

  public function newUser($data)
  {
    extract($data);
    $db = $this->dbConnect();
    $newMember = $db->prepare('INSERT INTO membres(pseudo,email,mdp) VALUES(:pseudo,:email,:password)');
    $verify = $newMember->execute(array(
      'pseudo' => $pseudo,
      'email' => $email,
      'password' => $password
    ));
    return $verify;
  }

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
