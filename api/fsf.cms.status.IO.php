<?php

require "../admin/ac.php";

$fsfcms_status_IO_output = array();

if ($fsfcms_is_logged_in == TRUE)
  {
  $fsfcms_status_IO_raw    = shell_exec("iostat");
  $fsfcms_status_IO_proc   = preg_replace("/\s+/"," ", trim($fsfcms_status_IO_raw));
  $fsfcms_status_IO        = explode(" ", $fsfcms_status_IO_proc);

//  print_r($fsfcms_status_IOWait);

  $fsfcms_status_IO_output['iowait']  = $fsfcms_status_IO[17];
//  $fsfcms_status_IO_output['used']   = $fsfcms_status_IO[8];
//  $fsfcms_status_IO_output['free']   = $fsfcms_status_IO[9];   
      
  header('Content-Type: application/json');
  echo json_encode($fsfcms_status_IO_output);
  } else  {
  header("HTTP/1.0 403 Forbidden");  
  }

?>
