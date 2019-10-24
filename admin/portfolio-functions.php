<?php

//
//
//  Functions for the portfolio section
//
//  Author:   Olen  Daelhousen
//  Version:  0.1
//
//  TODO: 
//            1.  Update getExistingKeywords to use MYSQLI
//            2.  Update getExistingKeywords to use parameters

//
//  KEYWORDS
//

function getExistingKeywords($fsfp_keywords_array)
  {
  $fsfp_existing_keywords = array();
  $fsfp_existing_keywords_query  = "";

  foreach($fsfp_keywords_array as $fsfp_keyword)
    {
    $fsfp_existing_keywords_query_wc .=  "keyword = '" . $fsfp_keyword . "' OR ";
    }
  $fsfp_existing_keywords_query_wc = substr($fsfp_existing_keywords_query_wc,0,-4);
    
  
  $fsfp_existing_keywords_query   = "SELECT id, keyword FROM " . FSFCMS_KEYWORDS_TABLE . " WHERE " . $fsfp_existing_keywords_query_wc;
  $fsfp_existing_keywords_result  = mysql_query($fsfp_existing_keywords_query);
  while($fsfcms_existing_keywords_row = mysql_fetch_row($fsfp_existing_keywords_result))
    {
    $fsfp_existing_keywords[$fsfcms_existing_keywords_row[0]] = $fsfcms_existing_keywords_row[1];   
    }

    return $fsfp_existing_keywords;    
  }

function  insertKeywordsMap($fsfp_existing_keywords,$fsfp_image_parent_id)
  {
  foreach($fsfp_existing_keywords as $fsfp_keyword_id => $fsfp_keyword)
    {  
    $fsfp_keywords_map_insert_values .= "('', " . $fsfp_image_parent_id . ", " . $fsfp_keyword_id . "),";
    }
  $fsfp_keywords_map_insert_values = rtrim($fsfp_keywords_map_insert_values, ",");   
  $fsfp_insert_keywords_map_query = "INSERT INTO " . FSFCMS_KEYWORDS_MAP_TABLE . " VALUES " . $fsfp_keywords_map_insert_values;

  mysql_query($fsfp_insert_keywords_map_query);  
  }

function  insertNewKeywords($fsfp_new_keywords)
  {
  $fsfp_keywords_stop_list     =  array("a","after","although","an","and","as","at","be","both","but","by","from","for","if","in","nor","of","on","or","over","so","the","though","to","up","via","when","while","would","yet");
  
  $fsfp_keywords_insert_values = "";
  foreach ($fsfp_new_keywords as $fsfp_keyword)
    {
    $fsfp_keyword_trimmed           =   trim($fsfp_keyword);
    $fsfp_keyword_slug              =   trim(preg_replace("/\b(" . implode("|",$fsfp_keywords_stop_list) . ")\b/","",$fsfp_keyword_trimmed));   //  Remove stop words from the keyword slug
    $fsfp_keyword_slug              =   trim(preg_replace("/\pP/","",$fsfp_keyword_slug));                                                      //  Remove punctuation from the keyword slug
    $fsfp_keyword_slug              =   preg_replace("/\s+/","-",$fsfp_keyword_slug);                                                           //  Replace any number of spaces with a single dash
    //$fsfp_keyword_slug              =   preg_replace("/[^a-zA-Z0-9]-/","",$fsfp_keyword_slug);                                                  //  Remove punctuation and anything non-numeric, except dashes
    $fsfp_keyword_slug              =   strtolower($fsfp_keyword_slug);   
    $fsfp_keywords_insert_values    .=  "('', '" . $fsfp_keyword_trimmed . "', '" . $fsfp_keyword_slug . "'),";
    }
  $fsfp_keywords_insert_values      =   rtrim($fsfp_keywords_insert_values, ",");
  $fsfp_insert_keywords_query       =   "INSERT INTO " . FSFCMS_KEYWORDS_TABLE . " VALUES " . $fsfp_keywords_insert_values;

  mysql_query($fsfp_insert_keywords_query);
  }

function  processKeywords($fsfp_image_keywords)
  {
  $fsfp_keywords_array      = preg_split("/(,\s+|,)/", $fsfp_image_keywords);     // Splits on commas  
  return  $fsfp_keywords_array;             
  }
?>