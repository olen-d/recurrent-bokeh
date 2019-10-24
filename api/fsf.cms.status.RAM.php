<?php

require "../admin/ac.php";

$fsfcms_status_RAM_output = array();

if ($fsfcms_is_logged_in == TRUE)
  {
  $fsfcms_status_RAM_raw    = shell_exec("free");
  $fsfcms_status_RAM_proc   = preg_replace("/\s+/"," ", trim($fsfcms_status_RAM_raw));
  $fsfcms_status_RAM        = explode(" ", $fsfcms_status_RAM_proc);

  $fsfcms_status_RAM_output['total']      = $fsfcms_status_RAM[7];
  $fsfcms_status_RAM_output['used']       = $fsfcms_status_RAM[15];   // -/+ buffers/cache 
  $fsfcms_status_RAM_output['free']       = $fsfcms_status_RAM[16];   // -/+ buffers/cache
  $fsfcms_status_RAM_output['swapTotal']  = $fsfcms_status_RAM[18];
  $fsfcms_status_RAM_output['swapUsed']   = $fsfcms_status_RAM[19];   
  
  header('Content-Type: application/json');
  echo json_encode($fsfcms_status_RAM_output);
  } else  {
  header("HTTP/1.0 403 Forbidden");  
  }

?>
