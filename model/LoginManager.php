<?php

class LoginManager extends Manager
{
  public function usersCount()
  {
    $db = $this->dbConnect();

    $usersCount = $db->prepare('SELECT * FROM membres');
    $usersCount->execute();
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

  public function getUser($pseudo)
  {
    $db = $this->dbConnect();
    $user = $db->prepare('SELECT * FROM membres WHERE pseudo = ?');
    $user->execute(array($pseudo));
    $data = $user->fetch();
    $user = new User($data);

    return $user;
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

  public function getAvatar($id)
  {
    $db = $this->dbConnect();

    $avatar = $db->prepare('SELECT avatar FROM membres WHERE id = :id');
    $avatar->bindValue(':id',$id);
    $avatar->execute();
    $pic = $avatar->fetch();

    return $pic[0];
  }

  public function avatarUpdate($avtName)
  {
    $db = $this->dbConnect();

    $updateAvatar = $db->prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
    $updateAvatar->bindValue(':avatar',$avtName);
    $updateAvatar->bindValue(':id',$_SESSION['user_session']['user_id']);
    $updateAvatar->execute();
  }

  public function userUpdate($data)
  {
    extract($data);
    $db = $this->dbConnect();

    $updateUser = $db->prepare('UPDATE membres SET pseudo = :pseudo,email = :email,mdp = :mdp WHERE id = :id ');
    $updateUser->bindValue(':id',$id);
    $updateUser->bindValue(':pseudo',$pseudo);
    $updateUser->bindValue(':email',$email);
    $updateUser->bindValue(':mdp',$password);
    $updateUser->execute();
  }
}
