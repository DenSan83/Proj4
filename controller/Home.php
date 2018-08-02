<?php
//user
class user
{
  public function login($x)         {}
  public function logout($x)        {}
  public function onlineComment($x) {}
  public function modifyComment($x) {}
  public function commentUpdate($x) {}
  public function deleteComment($x) {}
  public function flagComment($x)   {}
  public function editProfile($x)   {}
  public function updateProfile($x) {}
}
//front
class front
{
  public function listPosts($x)     {}
  public function post($x)          {}
  public function addComment($x)    {}
  public function newUser($x)       {}
}
//back
class back
{
  public function admin($x)         {}
}
