<?php

  // Make some preformatted dates and times as well as the fuzzy time
  $fsfcms_apidt_image_posted_date_unix_timestamp  = strtotime($fsfcms_api_image_posted_date);
  $fsfcms_apidt_image_postedDateUnixTimestamp     = $fsfcms_apidt_image_posted_date_unix_timestamp;
  $fsfcms_apidt_image_postedDateMMDDYYYY          = date("m-d-Y",$fsfcms_apidt_image_posted_date_unix_timestamp);
  $fsfcms_apidt_image_postedDateLong              = date("l, F jS, Y",$fsfcms_apidt_image_posted_date_unix_timestamp);
  $fsfcms_apidt_image_postedTime12Hour            = date("g:i a",$fsfcms_apidt_image_posted_date_unix_timestamp);
  $fsfcms_apidt_image_postedTime24Hour            = date("H:i",$fsfcms_apidt_image_posted_date_unix_timestamp);

  // Fuzzy time generator
  $fsfcms_apidt_time_hours_twentyfour = date("G",$fsfcms_apidt_image_posted_date_unix_timestamp);
  $fsfcms_apidt_fuzzy_prefix = "in the&nbsp;";

  if ($fsfcms_apidt_time_hours_twentyfour < 6)
    {
    $fsfcms_apidt_fuzzy_time = "o'dark thirty";
    $fsfcms_apidt_fuzzy_prefix = "at&nbsp;";
    } elseif ($fsfcms_apidt_time_hours_twentyfour < 12) {
    $fsfcms_apidt_fuzzy_time = "morning";
    } elseif ($fsfcms_apidt_time_hours_twentyfour < 17) {
    $fsfcms_apidt_fuzzy_time = "afternoon";
    } elseif ($fsfcms_apidt_time_hours_twentyfour < 21) {
    $fsfcms_apidt_fuzzy_time = "evening";
    } elseif ($fsfcms_apidt_time_hours_twentyfour < 24) {
    $fsfcms_apidt_fuzzy_time = "night";
    $fsfcms_apidt_fuzzy_prefix = "at&nbsp;";
    } else  {
    $fsfcms_apidt_fuzzy_time = "";
    $fsfcms_apidt_fuzzy_prefix = "";
    }

  $fsfcms_apidt_image_postedTimeFuzzy = $fsfcms_apidt_fuzzy_prefix . $fsfcms_apidt_fuzzy_time; 
?>
