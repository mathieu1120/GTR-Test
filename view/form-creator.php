<?php
echo '<ul id="formList">';
foreach ($viewVars['formList'] as $form)
echo '<li><a href="?controller=form&id='.(int)$form['id_form'].'">'.dp($form['name']).'</a></li>';
echo '</ul>';
?>
<h1>Create a Form</h1>
<div id="forms">
<?php
if ($viewVars['validation'])
echo '<div id="validation"><ul><li>'.implode('</li><li>', $viewVars['validation']).'</li></ul></div>';
if ($viewVars['warning'])
echo '<div id="warning"><ul><li>'.implode('</li><li>', $viewVars['warning']).'</li></ul></div>';

foreach ($viewVars['FORM'] as $form)
echo $form;

?>
</div>