<?php

function fsf_cms_accessAPI($fsfcms_api_options)
  {
  $fsfcms_api_file  = array_pop($fsfcms_api_options);
  $fsfcms_api_query_string  = "?";
  foreach($fsfcms_api_options as $fsfcms_api_option => $fsfcms_api_value)
    {
    $fsfcms_api_query_string  .=  $fsfcms_api_option . "=" . $fsfcms_api_value . "&";
    }
  $fsfcms_api_query_string  = rtrim($fsfcms_api_query_string,"&");
  $fsfcms_api_query_string  = rtrim($fsfcms_api_query_string,"?");
  $fsfcms_cookies = "";

  // Get the cookies and dump them into a string that CURL can understand 
  foreach($_COOKIE as $cookie_name => $cookie_value)
    {
    $fsfcms_cookies .= $cookie_name . "=" . $cookie_value . ";"; 
    }

  $fsfcms_curl_connect_timeout  = 1000;     // milliseconds
  $fsfcms_curl_request_timeout  = 1000;     // milliseconds
  session_write_close();                          // necessary so that CURL can access the cookies.
  $fsfcms_curl_handle = curl_init();
  
  curl_setopt($fsfcms_curl_handle,CURLOPT_COOKIE,$fsfcms_cookies);  
  curl_setopt($fsfcms_curl_handle,CURLOPT_URL,FSFCMS_API_URL . "v001/" . $fsfcms_api_file . $fsfcms_api_query_string);
  curl_setopt($fsfcms_curl_handle,CURLOPT_RETURNTRANSFER,TRUE);
  curl_setopt($fsfcms_curl_handle,CURLOPT_CONNECTTIMEOUT_MS,$fsfcms_curl_connect_timeout);
  curl_setopt($fsfcms_curl_handle,CURLOPT_TIMEOUT_MS,$fsfcms_curl_request_timeout);
  $fsfcms_curl_content = curl_exec($fsfcms_curl_handle);
  curl_close($fsfcms_curl_handle);

  return $fsfcms_curl_content;  
  }

  ?>