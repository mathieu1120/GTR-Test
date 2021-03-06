<?php

class FormController extends Controller
{
  public $view = 'user-form';

  public $form = null;

  public function init()
  {
    self::$media['css'][] = 'form';
    if (isset($_GET['id']))
    $this->form = new Form((int)$_GET['id']);

    if (!$this->form)
    self::$warning[] = 'The form you are trying to access doesn\'t exists';
    else
    {
      $this->form->fieldsArray = json_decode($this->form->fields, true);
      $this->form->action = '?controller=form&id='.$this->form->id;
    }
  }

  public function run()
  {
    self::$viewVars['list-records'] = array();

    $editForm = new Form();
    $editForm->title = 'Edit The Form';
    if ($this->form)
    {
      $editForm->action = '?controller=form&id='.(int)$this->form->id;
      $editForm->fieldsArray = array('name' => array('type' => 'text', 'label' => 'Name of the form', 'required' => true, 'value' => dp($this->form->name)),
                                   'fields' => array('type' => 'textarea', 'required' => true, 'label' => 'Fields in JSON', 'value' => dp($this->form->fields)));
      $formData = new FormData();
      self::$viewVars['list-records'] = $formData->getList(array('id_form' => (int)$this->form->id));
    }
    self::$viewVars['form-name'] = $this->form ? dp($this->form->name) : '';
    self::$forms['edit-form'] = $editForm;
    self::$forms['user-form'] = $this->form;    
    parent::run();
  }

  public function postProcess()
  {
    if (!isset($_POST['user-form']) && !isset($_POST['edit-form']))
    return false;

    if (isset($_POST['user-form']))
    {
      $formData = new FormData();
      $data = array();

      foreach ($this->form->fieldsArray as $fieldName => $field)
      {
        if (isset($_POST[$fieldName]) && $_POST[$fieldName])
        {          
          $data[$fieldName] = pSQL($_POST[$fieldName]);
        }
        else if (isset($field['required']) && $field['required'])
        self::$warning[] = (isset($field['label']) ? dp($field['label']) : $fieldName).' is required';
      }
      if (self::$warning)
      return false;
      $formData->id_form = (int)$this->form->id;
      $formData->data = pSQL(serialize($data));
      if ($formData->create())
      self::$validation[] = 'Record successfully saved';
      else
      self::$warning[] = 'Sorry, we couldn\'t save your record, please try again later.';
    }
    if (isset($_POST['edit-form']))
    {
      $error = false;
      if (!isset($_POST['name']) || !$_POST['name'])
      {
        self::$warning[] = 'Please provide a name for your form';
        $error = true;
      }
    
      if (!isset($_POST['fields']) || !$_POST['fields'])
      {
        self::$warning[] = 'Please provide information about your form fields';
        $error = true;
      }
      if ($error)
      return false;
      
      $editForm = new Form((int)$this->form->id);
      $editForm->name = pSQL($_POST['name']);
      $editForm->fields = pSQL($_POST['fields']);
      if ($editForm->update())
      {
        self::$validation[] = 'Form updated sucessfully';
        $this->form->name = dp($editForm->name);
        $this->form->fields = $_POST['fields'];
        $this->form->fieldsArray = json_decode($this->form->fields, true);
      }
      else
      self::$warning[] = 'Sorry, we couldn\'t update your form, please try again later.';
    }
  }
}