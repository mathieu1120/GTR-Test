<h1><?php echo dp($viewVars['form-name']);?></h1>
<div id="forms">
<?php
if ($viewVars['validation'])
echo '<div id="validation"><ul><li>'.implode('</li><li>', $viewVars['validation']).'</li></ul></div>';
if ($viewVars['warning'])
echo '<div id="warning"><ul><li>'.implode('</li><li>', $viewVars['warning']).'</li></ul></div>';

echo $viewVars['FORM']['edit-form'].'<hr/>';
echo '<table><tr><th>ID</th><th>Date</th><th>Fields</th><tr>';
foreach ($viewVars['list-records'] as $record)
{
  $uData = unserialize($record['data']);
  $data = array();
  foreach ($uData as $k => $d)
  $data[] = dp($k.': '.$d);
  echo '<tr><td>'.dp($record['id_form_data']).'</td><td>'.dp($record['created']).'</td><td>'.implode('<br/>', $data).'</td></tr>';
}
echo '</table>';
echo $viewVars['FORM']['user-form'].'<hr/>';
?>
</div>
