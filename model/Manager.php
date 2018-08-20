<?php

class Manager
{
  protected function dbConnect()
  {
    $db = new \PDO('mysql:host=db750475439.db.1and1.com;dbname=db750475439;charset=utf8', 'dbo750475439', 'Hi5locoo.');
    date_default_timezone_set('Europe/Paris');
    return $db;
  }
}
