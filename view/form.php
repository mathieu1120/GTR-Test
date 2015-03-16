<?php

function getFormFieldSelect($fieldName, $field)
{
  $select = '<select name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').'>';
  foreach ($field['items'] as $item)
  {
    if (is_array($item))
    $select .= '<option value="'.dp($item['value']).'"'.(isset($field['value']) && $field['value'] == $item['value'] ? 'selected="selected"' : '').'>'.dp($item['text']).'</option>';
    else
    $select .= '<option value="'.dp($item).'"'.(isset($field['value']) && $field['value'] == $item ? 'selected="selected"': '').'>'.dp($item).'</option>';
  }
  $select .= '</select>';
  return $select;
}

function getFormFieldText($fieldName, $field)
{
  return '<input type="text" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').''.(isset($field['value']) ? ' value="'.$field['value'].'"' : (isset($field['default']) ? 'value="'.$field['default'].'"' : '')).' />';
}

function getFormFieldFile($fieldName, $field)
{
  return '<input type="file" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').' />';
}

function getFormFieldHidden($fieldName, $field)
{
  return '<input type="hidden" name="'.$fieldName.'" value="'.$field['value'].'"  />';
}

function getFormFieldCheckbox($fieldName, $field)
{
  return '<input type="checkbox" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').''.(isset($field['value']) && $field['value'] ? ' checked="checked"' : '').' />';
}

function getFormFieldRadio($fieldName, $field)
{
  $radio = '';
  foreach ($field['items'] as $item)
  {
    if (is_array($item))
    $radio .= '<input type="radio" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').' value="'.$item['value'].'"'.(isset($field['value']) && $field['value'] == $item['value']? ' checked="checked"' : (isset($field['default']) && $field['default'] == $item['value'] ? 'checked="checked"' : '')).' /> '.$item['text'];
    else
    $radio .= '<input type="radio" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').' value="'.$item.'"'.(isset($field['value']) && $field['value'] == $item? ' checked="checked"' : (isset($field['default']) && $field['default'] == $item ? 'checked="checked"' : '')).' /> '.$item;
  }
  return $radio;
}

function getFormFieldTextarea($fieldName, $field)
{
  return '<textarea name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').'>'.(isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default']: '')).'</textarea>';
}

function getFormFieldCustom($fieldName, $field)
{
  return '<input type="'.$field['type'].'" name="'.$fieldName.'"'.(isset($field['required']) && $field['required'] ? 'class="required"' : '').(isset($field['value']) ? ' value="'.$field['value'].'"' : (isset($field['default']) ? 'value="'.$field['default'].'"' : '')).' />';
}

$viewVars['FORM'] = array();

foreach ($viewVars['forms'] as $name => $formObj)
{
  $viewVars['FORM'][dp($name)] = ($formObj->title ? '<h2>'.$formObj->title.'</h2>': '').'<form name="'.dp($name).'" action="'.dp($formObj->action).'" method="'.dp($formObj->method).'"'.($formObj->file ? 'enctype="multipart/form-data"' : '').'>';
  if ($formObj->fieldsArray)
  {
    $arrayFieldOptions = array('text', 'radio', 'checkbox', 'textarea', 'hidden', 'select');
    foreach ($formObj->fieldsArray as $fieldName => $field)
    {
      if(!in_array($field['type'], $arrayFieldOptions))
      continue;
      $function = 'getFormField'.ucfirst(dp($field['type']));
      $viewVars['FORM'][dp($name)] .= '<div class="field"><label>'.dp($field['label']).':</label>'.$function(dp($fieldName), $field).(isset($field['description']) ? '<p class="description">'.dp($field['description']).'</p>' : '').'</div>';
    }
    $viewVars['FORM'][$name] .= '<input type="submit" name="'.dp($name).'" value="Submit"/></form>';
  }
}