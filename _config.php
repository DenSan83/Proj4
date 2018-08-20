<?php
/***** Configuration *****/
ini_set('display_errors','on');
error_reporting(E_ALL);

class MyAutoload
{
  public static function start()
  {

    spl_autoload_register(array(__CLASS__, 'autoload'));

    $root = $_SERVER['DOCUMENT_ROOT'];
    $host = $_SERVER['HTTP_HOST'];

    define('HOST','http://'.$host.'/');
    define('ROOT',$root.'/');

    define('MODEL',ROOT.'model/');
    define('ENTITY',MODEL.'entity/');
    define('MOD_MANAGER',MODEL.'manager/');
    define('VIEW',ROOT.'view/frontend/');
    define('VIEW_BCK',ROOT.'view/backend/');
    define('CONTROLLER',ROOT.'controller/');
    define('CLASSES',ROOT.'classes/');
    define('ASSETS',ROOT.'public/');


  }

  public static function autoload($class)
  {
    if(file_exists(MODEL.$class.'.php'))
    {
      include_once(MODEL.$class.'.php');
    } else if (file_exists(CLASSES.$class.'.php'))
    {
      include_once(CLASSES.$class.'.php');
    } else if (file_exists(CONTROLLER.$class.'.php'))
    {
      include_once(CONTROLLER.$class.'.php');
    } else if (file_exists(ASSETS.$class.'.php'))
    {
      include_once(ASSETS.$class.'.php');
    }
    else if (file_exists(ENTITY.$class.'.php'))
    {
      include_once(ENTITY.$class.'.php');
    }
    else if (file_exists(MOD_MANAGER.$class.'.php'))
    {
      include_once(MOD_MANAGER.$class.'.php');
    }
  }
}
