<?php

header("Content-Type: application/atom+xml; charset=utf-8");

// Configuration variables
$fsfcms_port_siteURL              = fsfcms_getSiteURL();
$fsfcms_port_site_minimum_URL     = fsfcms_getSiteMinimumURL();
$fsfcms_port_image_filepath       = fsfcms_getSiteImageFilePath();
$fsfcms_port_feed_number_of_items = fsfcms_getFeedNumberOfItems();

// Start forming the feed
$fsfcms_port_atomfeed  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$fsfcms_port_atomfeed  .= "<feed xml:lang=\"eng-US\" xmlns=\"http://www.w3.org/2005/Atom\">\n";
$fsfcms_port_atomfeed  .= "\t\t<title>" . fsfcms_getSiteTitle() . " Atom 1.0 Feed</title>\n";
$fsfcms_port_atomfeed  .= "\t\t<subtitle>" . fsfcms_getSiteBrief() . "</subtitle>\n";
$fsfcms_port_atomfeed  .= "\t\t<link href=\"" . $fsfcms_port_siteURL . "feed/atom\" rel=\"self\" />\n";
$fsfcms_port_atomfeed  .= "\t\t<updated>" . date("c") . "</updated>\n";
$fsfcms_port_atomfeed  .= "\t\t<author>\n\t\t\t<name>" . fsfcms_getSiteTitle() . "</name>\n\t\t\t<email>fake@fake.com</email>\n\t\t</author>\n";
$fsfcms_port_atomfeed  .= "\t\t<id>tag:" . $fsfcms_port_site_minimum_URL . ",2012:" . $fsfcms_port_siteURL . "feed/atom</id>\n";

// Get the images for the feed
$fsfcms_port_images_json  = fsf_port_getImages("DESC", $fsfcms_port_feed_number_of_items);
$fsfcms_port_images       = json_decode($fsfcms_port_images_json,true);

// The array of images includes a status code at the end, pop that off and check it. Codes include: 200 = Good, 404 = Failure
$fsfcms_port_images_status  = array_pop($fsfcms_port_images);

if ($fsfcms_port_images_status == "200")
  {
  foreach($fsfcms_port_images as $fsfcms_port_image)
    {
    $fsfcms_port_image_filename       = $fsfcms_port_image['filename'];
    $fsfcms_port_image_title          = $fsfcms_port_image['title'];
    $fsfcms_port_image_link_json      = fsf_port_getImageLinkCleanURL($fsfcms_port_image['id']);
    $fsfcms_port_image_link           = json_decode($fsfcms_port_image_link_json,true);
    $fsfcms_port_image_link_clean_URL = $fsfcms_port_image_link['imageLink'];
    $fsfcms_port_permalink            = $fsfcms_port_siteURL . $fsfcms_port_image_link_clean_URL;    $fsfcms_port_atomfeed   .= "\t\t<entry>\n";
    $fsfcms_port_atomfeed   .= "\t\t\t<title>" . $fsfcms_port_image_title . "</title>\n";
    $fsfcms_port_atomfeed   .= "\t\t\t<link type=\"text/html\" href=\"" . $fsfcms_port_permalink . "\" />\n";
    $fsfcms_port_atomfeed   .= "\t\t\t<id>tag:" . $fsfcms_port_site_minimum_URL . "," . date("Y",strtotime($fsfcms_port_image['postedDate'])) . ":" . $fsfcms_port_permalink . "</id>\n"; 
    $fsfcms_port_atomfeed   .= "\t\t\t<content type=\"html\">\n\t\t\t\t<![CDATA[\n\t\t\t\t<img src=\"" . fsfcms_getPortThumbsURL() . "thumb_" . $fsfcms_port_image_filename . "\" /><br />" . trim($fsfcms_port_image['caption']) . "<br />\n\t\t\t\t]]>\n\t\t\t</content>\n";
    $fsfcms_port_atomfeed   .= "\t\t\t<link rel=\"enclosure\" href=\"" . $fsfcms_port_siteURL . "images/" . $fsfcms_port_image_filename . "\" length=\"" . filesize($fsfcms_port_image_filepath . $fsfcms_port_image_filename) . "\" type=\"" . $fsfcms_port_image['type'] . "\" />\n";
    $fsfcms_port_atomfeed   .= "\t\t\t<updated>" . date("c",strtotime($fsfcms_port_image['postedDate'])) . "</updated>\n";
    $fsfcms_port_atomfeed  .= "\t\t\t<author>\n\t\t\t\t<name>" . $fsfcms_port_image['authorFirstName'] . " " . $fsfcms_port_image['authorLastName'] . "</name>\n\t\t\t</author>\n";
    $fsfcms_port_atomfeed  .= "\t\t</entry>\n";
    }
  $fsfcms_port_atomfeed  .= "</feed>\n";
  echo $fsfcms_port_atomfeed;
  } else  {
  echo "<p>Something has gone horribly wrong and the Atom feed is broken. Please keep calm and contact the appropriate authorities. </p>";
  }
?>
