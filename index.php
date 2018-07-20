<?php
session_start();

include_once('_config.php');

MyAutoload::start();

if(!isset($_GET['r']))
{
  $_GET['r'] = 'home';
}
$request = $_GET['r']; // index.php?r=...

$routeur = new Router($request);
$routeur->renderController();
