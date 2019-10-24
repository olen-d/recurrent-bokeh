<?php

require "../admin/ac.php";

$fsfcms_status_DB_output = array();

function fsfcms_DB_status_query($fsfcms_DB_query, $fsfcms_DB_item_name)
  {
  global $fsfcms_status_DB_output;
  
  $fsfcms_DB_result = mysql_query($fsfcms_DB_query);
  $fsfcms_DB_row    = mysql_fetch_row($fsfcms_DB_result);
  $fsfcms_status_DB_output[$fsfcms_DB_item_name]  = $fsfcms_DB_row[1];
  }

if ($fsfcms_is_logged_in == TRUE)
  {
  require "../admin/cfg.php";
  require "../admin/startDB.php";

  fsfcms_DB_status_query("SHOW GLOBAL STATUS LIKE 'threads_connected'","threadsConnected");
  fsfcms_DB_status_query("SHOW GLOBAL STATUS LIKE 'max_used_connections'","maxUsedConnections");
  fsfcms_DB_status_query("SHOW GLOBAL VARIABLES LIKE 'max_connections'","maxConnections");
  fsfcms_DB_status_query("SHOW GLOBAL STATUS LIKE 'slow_queries'","slowQueries");
    
  /*
  $fsfcms_status_DB_threads_conn_query  = "SHOW GLOBAL STATUS LIKE 'threads_connected'";
  $fsfcms_status_DB_threads_conn_result = mysql_query($fsfcms_status_DB_threads_conn_query);
  $fsfcms_status_DB_threads_conn_row    = mysql_fetch_row($fsfcms_status_DB_threads_conn_result);
  $fsfcms_status_DB_output['threadsConnected']  = $fsfcms_status_DB_threads_conn_row[1];

  $fsfcms_status_DB_max_used_conn_query  = "SHOW GLOBAL STATUS LIKE 'max_used_connections'";
  $fsfcms_status_DB_max_used_conn_result = mysql_query($fsfcms_status_DB_max_used_conn_query);
  $fsfcms_status_DB_max_used_conn_row    = mysql_fetch_row($fsfcms_status_DB_max_used_conn_result);
  $fsfcms_status_DB_output['maxUsedConnections']  = $fsfcms_status_DB_max_used_conn_row[1];

  $fsfcms_status_DB_max_conn_query      = "SHOW GLOBAL VARIABLES LIKE 'max_connections'";
  $fsfcms_status_DB_max_conn_result = mysql_query($fsfcms_status_DB_max_conn_query);
  $fsfcms_status_DB_max_conn_row    = mysql_fetch_row($fsfcms_status_DB_max_conn_result);
  $fsfcms_status_DB_output['maxConnections']  = $fsfcms_status_DB_max_conn_row[1];

  $fsfcms_status_DB_slow_query      = "SHOW GLOBAL STATUS LIKE 'slow_queries'";
  $fsfcms_status_DB_slow_result = mysql_query($fsfcms_status_DB_slow_query);
  $fsfcms_status_DB_slow_row    = mysql_fetch_row($fsfcms_status_DB_slow_result);
  $fsfcms_status_DB_output['slowQueries']  = $fsfcms_status_DB_slow_row[1];
  */
  
  header('Content-Type: application/json');
  echo json_encode($fsfcms_status_DB_output);
  } else  {
  header("HTTP/1.0 403 Forbidden");  
  }

?>
