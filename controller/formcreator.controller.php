<?php

class FormCreatorController extends Controller
{
  public $view = 'form-creator';

  public $form;

  public function init()
  {
    self::$media['css'][] = 'form';
    $this->form = new Form();
    $this->form->fieldsArray = array('name' => array('type' => 'text',
                                                'label' => 'Form Name',
                                                'required' => true),
                                     'fields' => array('type' => 'textarea',
                                                       'required' => true,
                                                       'label' => 'Please enter the form fields information with a JSON format',
                                                       'default' => json_encode(array('fieldname' => array('type' => 'text',
                                                                                                           'label' => 'field display name',
                                                                                                           ),
                                                                                      'fieldname2' => array('type' => 'select',
                                                                                                            'label' => 'field 2 display name',
                                                                                                            'items' => array('item1', 'item2'),
                                                                                                            ),
                                                                                      'fieldname3' => array('type' => 'text',
                                                                                                            'label' => 'field 2 display name',
                                                                                                            'items' => array('item1', 'item2'),
                                                                                                            )
                                                                                      )
                                                                                )
                                                       )
                                     );
    $this->form->file = true;
  }
  
  public function run()
  {
    self::$forms['formCreator'] = $this->form;
    self::$viewVars['formList'] = $this->form->getList();
    parent::run();
  }
  
  public function postProcess()
  {
    if (!isset($_POST['formCreator']))
    return false;
    
    $error = false;
    if (!isset($_POST['name']) || !$_POST['name'])
    {
      self::$warning[] = 'Please provide a name to your form';
      $error = true;
    }
    
    if (!isset($_POST['fields']) || !$_POST['fields'])
    {
      self::$warning[] = 'Please provide information for your form fields';
      $error = true;
    }

    if ($error)
    return false;

    $newForm = new Form();
    $newForm->name = pSQL($_POST['name']);
    $newForm->fields = pSQL($_POST['fields']);

    if ($newForm->create())
    {
      self::$validation[] = dp($newForm->name).' has been created with success';
      return true;
    }
    self::$warning[] = 'Your form couldn\'t be created';
    return false;
  }
}