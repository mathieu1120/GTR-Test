<?php

abstract class Controller
{
  public $view = 'home';

  static $media = array('css' => array('home'), 'js' => array());
  static $viewVars = array();
  static $validation = array();
  static $warning = array();
  static $forms = array();  

  public function init()
  {
  }

  public function run()
  {
    $this->display();
  }

  public function postProcess()
  {
    return true;
  }

  public function display($view = null, $dir = VIEW_DIR)
  {
    if (self::$forms)
    {
      $viewVars['forms'] = self::$forms;
      require VIEW_DIR.'form.php';
      unset($viewVars['forms']);
      self::$forms = array();
      self::$viewVars['FORM'] = $viewVars['FORM'];
    }
    self::$viewVars['warning'] = self::$warning;
    self::$viewVars['validation'] = self::$validation;
    self::$viewVars['media'] = self::$media;

    $viewVars = self::$viewVars;
    require($dir.($view ? $view : $this->view).'.php');
  }
}