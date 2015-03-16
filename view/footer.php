<p id="footer">GTR Test. Mathieu Bertholino</p>
<?php
foreach ($viewVars['media']['js'] as $js)
{
  if (file_exists(JS_DIR.$js.'.js'))
  echo '<script type="text/javascript" src="'.JS_URI.$js.'.js?'.time().'"></script>';
}
?>
</body>
</html>