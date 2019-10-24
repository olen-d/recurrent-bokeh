<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
//$fsfcms_current_time_mysql_format 

// These are not log specific, move them to fsf_port_getCloudKeywords_functions under api/includes OR POSSIBLY JUST MAKE THEM AN INCLUDE
$fsfcms_getStatesByHexID_states         = array();
$fsfcms_getStatesByHexID_output         = array();
$fsfcms_getStatesByHexID_none           = "No states were found. ";

//  Prepare the statement and access the database

$fsfcms_getStatesByHexID_query  = "SELECT hex_id,key_slug,name,ansi,gpo,image_count FROM fsf_cms_states LEFT JOIN
                                    (SELECT name_2, key_slug, COUNT(keyword_id) AS image_count FROM 
                                    fsf_cms_keywords_map INNER JOIN 
                                      (SELECT fsf_cms_states.name AS name_2,fsf_cms_states.ansi,fsf_cms_states.gpo,fsf_cms_states.hex_id,fsf_cms_keywords.id AS key_id,fsf_cms_keywords.keyword,fsf_cms_keywords.keyword_slug AS key_slug FROM 
                                      fsf_cms_states INNER JOIN fsf_cms_keywords ON fsf_cms_states.name = fsf_cms_keywords.keyword) AS DORITOS 
                                    ON fsf_cms_keywords_map.keyword_id = DORITOS.key_id
                                    INNER JOIN fsf_cms_images ON fsf_cms_keywords_map.image_parent_id = fsf_cms_images.id WHERE fsf_cms_images.post < ?
                                    GROUP BY keyword_id) as NINJA ON NINJA.name_2 = fsf_cms_states.name";

if($fsfcms_getStatesByHexID_statement  = $fsfcms_db_link->prepare($fsfcms_getStatesByHexID_query))
  {
  if($fsfcms_getStatesByHexID_statement->bind_param("s",$fsfcms_current_time_mysql_format))
    {
    if($fsfcms_getStatesByHexID_statement->execute())
      {
      if($fsfcms_getStatesByHexID_statement->bind_result($hex_id,$key_slug,$name,$ansi,$gpo,$image_count))
        { 
        while ($fsfcms_getStatesByHexID_statement->fetch())
          {
          $fsfcms_getStatesByHexID_states[$hex_id] = array (
                                                              stateSlug         =>  $key_slug,
                                                              stateName         =>  $name,
                                                              stateAbbreviation =>  $ansi,
                                                              stateShortName    =>  $gpo,
                                                              stateImageCount   =>  $image_count
                                                              ); 
          }
        $fsfcms_getStatesByHexID_states_output  = $fsfcms_getStatesByHexID_states;
        $fsfcms_getStatesByHexID_statement->free_result();
        $fsfcms_getStatesByHexID_states_output['status']          = 200;
        } else  {
        $fsfcms_getStatesByHexID_states_output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $fsfcms_getStatesByHexID_states_output['status']          = 500;        
        }
      } else  {
      $fsfcms_getStatesByHexID_states_output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $fsfcms_getStatesByHexID_states_output['status']            = 500;      
      }
    } else  {
    $fsfcms_getStatesByHexID_states_output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $fsfcms_getStatesByHexID_states_output['status']              = 500;
    }
  } else  {
   $fsfcms_db_error  = $fsfcms_db_link->error;
  $fsfcms_getStatesByHexID_states_output['errorMessage']          = $fsfcms_db_error . " Prepare failed.";
  $fsfcms_getStatesByHexID_states_output['status']                = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_getStatesByHexID_states_output);
?>