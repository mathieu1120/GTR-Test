<?php

class form extends MySQL
{
  public $table = 'form';
  public $id;

  public $fields = '';
  public $fieldsArray = '';
  public $method = 'post';
  public $action = './';
  public $file = false;
  public $name = '';
  public $title = '';

  public function create()
  {
    return parent::create();
  }
}