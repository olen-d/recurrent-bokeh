<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

$fsfcms_getSiteContactInfo_loginFlag  = 0;

if ($fsfcms_is_logged_in)
  {
  // $fsfcms_getSiteContactInfo_loginFlag  = 1;
  $fsfcms_gsci_fields_where_clause  = "";
  } else  {
  $fsfcms_gsci_fields_where_clause  = " WHERE visible = 1";
  }

// Initialize Script Variables
$fsfcms_getSiteContactInfo_output = array();
$fsfcms_db_error                  = "Request could not be completed because of a database error.";

$fsfcms_gsci_fields = "";

if($fsfcms_gsci_fields_result = $fsfcms_db_link->query("SELECT field_name, field_alias FROM " . FSFCMS_SITE_CONTACT_CONFIG_TABLE . $fsfcms_gsci_fields_where_clause))
  {                 
  while($fsfcms_gsci_row = $fsfcms_gsci_fields_result->fetch_row())
    {                           
    $fsfcms_gsci_fields .= $fsfcms_gsci_row[0] . " AS " . $fsfcms_gsci_row[1] . ", ";
    }
  $fsfcms_gsci_fields = rtrim($fsfcms_gsci_fields,", ");
  if($fsfcms_gsci_contact_info_result = $fsfcms_db_link->query("SELECT " . $fsfcms_gsci_fields . ",name_first AS firstName, name_middle AS middleName, name_last AS lastName FROM " . FSFCMS_SITE_CONTACT_TABLE . 
                                                              " INNER JOIN " . FSFCMS_USERS_TABLE . " ON " . FSFCMS_SITE_CONTACT_TABLE . ".user = " .
                                                              FSFCMS_USERS_TABLE . ".id LIMIT 1"))
    {       
    while($fsfcms_gsci_row = $fsfcms_gsci_contact_info_result->fetch_assoc())
      {
      foreach($fsfcms_gsci_row as $fsfcms_gsci_key => $fsfcms_gsci_value)
        {                          
        $fsfcms_getSiteContactInfo_output[$fsfcms_gsci_key] = $fsfcms_gsci_value;
        }
      }
    }
  
          //$fsfcms_getSiteContactInfo_output['option']  = $value;
          //$fsfcms_getSiteContactInfo_output['status']  = 200;
    } else  {
    $fsfcms_getSiteContactInfo_output['errorMessage']    = "Request could not be completed because no results were found in the database.";
    $fsfcms_getSiteContactInfo_output['status']          = 404;
    }
/*        } else  {
        $fsfcms_getSiteContactInfo_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
        $fsfcms_getSiteContactInfo_output['status']            = 500;
        }
      } else  {
      $fsfcms_getSiteContactInfo_output['errorMessage']        = $fsfcms_db_error . " Bind failed.";
      $fsfcms_getSiteContactInfo_output['status']              = 500;
      }   
    } else  {
    $fsfcms_getSiteContactInfo_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
    $fsfcms_getSiteContactInfo_output['status']                = 500;
    }
*/
  $fsfcms_gsci_fields_result->close();
  
header('Content-Type: application/json');
echo json_encode($fsfcms_getSiteContactInfo_output);
?>