<?php

class IndexController extends Controller
{
  public function run()
  {
    if (!isset($_GET['controller']))
    $controller = new FormCreatorController();
    else
    {
      $class = dp($_GET['controller']).'Controller';
      $controller = new $class();
    }
    $controller->init();

    if (isset($_POST) && $_POST)
    $controller->postProcess();

    $this->displayHeader();
    $controller->run();

    $this->displayFooter();                    
  }

  public function displayHeader()
  {
    parent::display('header');
  }

  public function displayFooter()
  {
    parent::display('footer');
  }
}