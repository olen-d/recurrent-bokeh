<?php

require "../admin/ac.php";

$fsfcms_status_CPUload_output = array();

if ($fsfcms_is_logged_in == TRUE)
  {
  $fsfcms_status_CPUload_output = sys_getloadavg();
  header('Content-Type: application/json');
  echo json_encode($fsfcms_status_CPUload_output);
  } else  {
  header("HTTP/1.0 403 Forbidden");  
  }

?>
