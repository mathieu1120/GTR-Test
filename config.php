<?php

ini_set('display_errors', E_ALL);

define('URI', '/gtr/');

define('VIEW_DIR', dirname(__FILE__).'/view/');
define('CONTROLLER_DIR', dirname(__FILE__).'/controller/');
define('CLASS_DIR', dirname(__FILE__).'/class/');
define('CSS_URI', URI.'css/');
define('JS_URI', URI.'js/');
define('CSS_DIR', dirname(__FILE__).'/css/');
define('JS_DIR', dirname(__FILE__).'/js/');

define('SERVER_DB', 'localhost');
define('USER_DB', '');
define('PASSWORD_DB', '');
define('DATABASE_DB', 'gtr_form');

spl_autoload_register(function($class){

  $class = strtolower($class);
  if (($controllerPos = strpos($class, 'controller')) !== false)
  {
    if (!$controllerPos)
    $controllerName = $class;
    else
    $controllerName = substr($class, 0, $controllerPos);
    if (file_exists(CONTROLLER_DIR.'/'.$controllerName.'.controller.php'))
    require_once(CONTROLLER_DIR.'/'.$controllerName.'.controller.php');
    else
    d($controllerName.'.controller.php doesn\'t exists');
  }
  else
  {
    if (file_exists(CLASS_DIR.'/'.$class.'.php'))
    require_once(CLASS_DIR.'/'.$class.'.php');
    else
    d($class.'.php doesn\'t exists');
  }
});

function d($object, $die = true)
{
  echo '<pre>';
  var_dump($object);
  if ($die)
  die();
}

function dp($string) //display proctect
{
  return htmlspecialchars($string);
}

function pSQL($string)
{
  return Db::getInstance()->escape($string);
}