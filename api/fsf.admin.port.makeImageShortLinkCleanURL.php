<?php

require "../admin/cfg.php";
require "../admin/startDB.php";
require "../includes/fsf_cms_functions.php";

// Initialize Script Variables
$fsfcms_makeImageShortLinkCleanURL_output             = array();
$fsfcms_makeImageShortLinkCleanURL_long_base_url      = fsfcms_getSiteURL();
$fsfcms_makeImageShortLinkCleanURL_short_base_url     = fsfcms_getSiteURLshortenerURL();
$fsfcms_makeImageShortLinkCleanURL_key_prefix   = "i";

$fsfcms_makeImageShortLinkCleanURL_status         = "FAIL";

// Initialize Get Variables
$fsfcms_current_image_id       = $_GET['image_id'];

//
//
//  Functions
//
//

function fsfcms_alphaID($in, $pad_up = false)
{ 
	$index = "bcdfghjklmnpqrstvwxyz0123456789BCDFGHJKLMNPQRSTVWXYZ";
	$base  = strlen($index);
 
	// Digital number  -->>  alphabet letter code
	if (is_numeric($pad_up)) {
		$pad_up--;
		if ($pad_up > 0) {
			$in += pow($base, $pad_up);
		}
	}
 
	$out = "";
	for ($t = floor(log($in, $base)); $t >= 0; $t--) {
		$bcp = bcpow($base, $t);
		$a   = floor($in / $bcp) % $base;
		$out = $out . substr($index, $a, 1);
 		$in  = $in - ($a * $bcp);
		}
//
//
// IMPORTANT - REMEMBER TO PUT SOMETHING IN HERE THAT RESTRICTS THE PAD UP AND RETURNS AN
// ERROR IN THE UNLIKELY EVENT THAT THE NUMBER SPACE IS EXCEEDED - E.G. STRLEN(OUT) > PAD_UP
//
	$out = strrev($out); // reverse
 	return $out;
}
function fsfcms_make_short_url($fsfcms_mk_item_id,$fsfcms_long_url)
	{                        
	global $fsfcms_port_redirect_table;
	global $fsfcms_makeImageShortLinkCleanURL_key_prefix;
  global $fsfcms_makeImageShortLinkCleanURL_short_base_url;	

	//Create the URL
	$fsfcms_short_url_pad = 4;
	$fsfcms_key = fsfcms_alphaID($fsfcms_mk_item_id,$fsfcms_short_url_pad);
	$fsfcms_url = $fsfcms_makeImageShortLinkCleanURL_short_base_url . $fsfcms_makeImageShortLinkCleanURL_key_prefix . "/" . $fsfcms_key;
	$fsfcms_original_url = $fsfcms_long_url;
	$fsfcms_url_timestamp = time();

//
//
// REMEMBER TO CHECK AND SEE IF THE IMAGE ID, CATEGORY ID, WHATEVER, ACTUALLY EXISTS
//
//
//
	//Put all the BS in the database
	// Check to see if the shortened URL already exists in the database
	$fsfcms_shortener_query = "SELECT short_key, long_url FROM " . $fsfcms_port_redirect_table . " WHERE short_key = '" . $fsfcms_key . "'";
//	echo "<p>" . $fsfcms_shortener_query . "<p>";
  $fsfcms_shortener_result = mysql_query($fsfcms_shortener_query);
	$fsfcms_shortener_query_num_rows = mysql_num_rows($fsfcms_shortener_result);
	if ($fsfcms_shortener_query_num_rows == 0)
		{
		$fsfcms_shortener_insert_url_query = "INSERT INTO " .  $fsfcms_port_redirect_table . " (id, image_id, key_prefix, short_key, long_url, date_created) VALUES('', " . $fsfcms_mk_item_id . ", '" . $fsfcms_makeImageShortLinkCleanURL_key_prefix . "', '" . $fsfcms_key . "', '" . $fsfcms_original_url . "', '" . $fsfcms_url_timestamp . "')";
//echo "<p>" . $fsfcms_shortener_insert_url_query . "</p>";
		$fsfcms_shortener_insert_url_query_result = mysql_query($fsfcms_shortener_insert_url_query);
		}
	return $fsfcms_url;
	}


// Set Up the DB Queries
// TODO: SET UP TO USE EITHER ID OR PRETTY PERMALINK
// In the pretty permalink, reset $fsfcms_current_image_id to a number and put the pretty permalink in a seperate variable)
if(is_numeric($fsfcms_current_image_id))
  {
  $fsfcms_makeImageShortLinkCleanURL_query      = "SELECT id, title, YEAR(" . $fsfcms_images_table . ".post) AS imageYear, DATE_FORMAT(" . $fsfcms_images_table . ".post,'%m') AS imageMonth, title_slug FROM " . $fsfcms_images_table . " WHERE (" . $fsfcms_images_table . ".id = " . $fsfcms_current_image_id . ") LIMIT 1";
  //echo "<p>" . $fsfcms_makeImageShortLinkCleanURL_query . "</p>";
  $fsfcms_makeImageShortLinkCleanURL_result     = mysql_query($fsfcms_makeImageShortLinkCleanURL_query);
  if(mysql_num_rows($fsfcms_makeImageShortLinkCleanURL_result) > 0)
    {
    $fsfcms_makeImageShortLinkCleanURL_row                = mysql_fetch_row($fsfcms_makeImageShortLinkCleanURL_result);
    $fsfcms_makeImageShortLinkCleanURL_image_id           = $fsfcms_makeImageShortLinkCleanURL_row[0];
    
    $fsfcms_makeImageShortLinkCleanURL_image_title                  = $fsfcms_makeImageShortLinkCleanURL_row[1];
    $fsfcms_makeImageShortLinkCleanURL_long_url = $fsfcms_makeImageShortLinkCleanURL_long_base_url . $fsfcms_makeImageShortLinkCleanURL_row[2] . "/" . $fsfcms_makeImageShortLinkCleanURL_row[3] . "/" . $fsfcms_makeImageShortLinkCleanURL_row[4];
    $fsfcms_makeImageShortLinkCleanURL_URL = fsfcms_make_short_url($fsfcms_makeImageShortLinkCleanURL_image_id,$fsfcms_makeImageShortLinkCleanURL_long_url);



    
 
    $fsfcms_makeImageShortLinkCleanURL_status     = "OK";
    } else  {
    $fsfcms_makeImageShortLinkCleanURL_status     = "FAIL";
    $fsfcms_makeImageShortLinkCleanURL_URL        = null;
    }
  }
$fsfcms_makeImageShortLinkCleanURL_output['imageShortLinkStatus']     = $fsfcms_makeImageShortLinkCleanURL_status;
$fsfcms_makeImageShortLinkCleanURL_output['imageShortLinkURL']        = $fsfcms_makeImageShortLinkCleanURL_URL;

header('Content-Type: application/json');
echo json_encode($fsfcms_makeImageShortLinkCleanURL_output);
?>
