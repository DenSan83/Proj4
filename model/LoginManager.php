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

  public function getAvatar($pseudo)
  {
    $db = $this->dbConnect();

    $avatar = $db->prepare('SELECT avatar FROM membres WHERE pseudo = :pseudo');
    $avatar->bindValue(':pseudo',$pseudo);
    $avatar->execute();

    return $avatar->fetch();
  }

  public function loggedIn($user)
  {
    //login
    // envoyer au controlleur :
    $_SESSION['user_session']['user_pseudo'] = $user['pseudo'];
    $_SESSION['user_session']['user_avatar'] = $user['avatar'];
  }
}
