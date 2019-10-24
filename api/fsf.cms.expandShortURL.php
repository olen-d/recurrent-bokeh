<?php
require "../admin/cfg.php";
require "../admin/startDBi.php";
require "../includes/fsf_cms_functions.php";

// Initialize Script Variables
$fsfcms_expandShortURL_output = array();
$fsfcms_db_error              = "Request could not be completed because of a database error.";

$fsfcms_key_prefix  = $_GET['keyPrefix'];
$fsfcms_short_key   = $_GET['shortKey'];

if(isset($_GET['shortKey']))
{
  if($fsfcms_cfg_stmt = $fsfcms_db_link->prepare("SELECT long_url FROM " . FSFCMS_PORT_REDIRECT_TABLE . " WHERE key_prefix = ? AND short_key = ? LIMIT 1"))
    { 
    if($fsfcms_cfg_stmt->bind_param("ss",$fsfcms_key_prefix,$fsfcms_short_key))
      {
      if($fsfcms_cfg_stmt->execute())
        {
        $fsfcms_cfg_stmt->bind_result($long_url);
        if($fsfcms_cfg_stmt->fetch())
          {
          $fsfcms_expandShortURL_output['longURL']  = $long_url;
          $fsfcms_expandShortURL_output['status']  = 200;
          } else  {
          $fsfcms_expandShortURL_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
          $fsfcms_expandShortURL_output['status']          = 404;
          }
        } else  {
        $fsfcms_expandShortURL_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_expandShortURL_output['status']            = 500;
        }
      } else  {
      $fsfcms_expandShortURL_output['errorMessage']        = $fsfcms_db_error . " Bind failed.";
      $fsfcms_expandShortURL_output['status']              = 500;
      }   
    } else  {
    $fsfcms_expandShortURL_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_expandShortURL_output['status']                = 500;
    }
  $fsfcms_cfg_stmt->close(); 
} elseif(isset($_GET['keyPrefix']))  {
  if($fsfcms_cfg_stmt = $fsfcms_db_link->prepare("SELECT page_slug FROM " . FSFCMS_PAGES_TABLE . " WHERE page_shortkey_prefix = ? LIMIT 1"))
    { 
    if($fsfcms_cfg_stmt->bind_param("s",$fsfcms_key_prefix))
      {
      if($fsfcms_cfg_stmt->execute())
        {
        $fsfcms_cfg_stmt->bind_result($page_slug);
        if($fsfcms_cfg_stmt->fetch())
          {
          $fsfcms_expandShortURL_output['longURL']  = fsfcms_getSiteURL() . $page_slug;
          $fsfcms_expandShortURL_output['status']  = 200;
          } else  {
          $fsfcms_expandShortURL_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
          $fsfcms_expandShortURL_output['status']          = 404;
          }
        } else  {
        $fsfcms_expandShortURL_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_expandShortURL_output['status']            = 500;
        }
      } else  {
      $fsfcms_expandShortURL_output['errorMessage']        = $fsfcms_db_error . " Bind failed.";
      $fsfcms_expandShortURL_output['status']              = 500;
      }   
    } else  {
    $fsfcms_expandShortURL_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_expandShortURL_output['status']                = 500;
    }
  $fsfcms_cfg_stmt->close(); 
}

/*
$fsfcms_expandShortURL_row = mysql_fetch_assoc(mysql_query("SELECT long_url FROM " . $fsfcms_port_redirect_table . " WHERE key_prefix = '" . $fsfcms_key_prefix . "' AND short_key='" . $fsfcms_short_key . "'"));

$fsfcms_expandShortURL_output['longURL']  = $long_url;  
*/
header('Content-Type: application/json');
echo json_encode($fsfcms_expandShortURL_output);
?>