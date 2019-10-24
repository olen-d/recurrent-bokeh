<?php

header("Content-Type: application/rss+xml; charset=utf-8");

$fsfcms_port_siteURL              = fsfcms_getSiteURL();
$fsfcms_port_image_filepath       = fsfcms_getSiteImageFilePath();
$fsfcms_port_feed_number_of_items = fsfcms_getFeedNumberOfItems();

// Start forming the feed
$fsfcms_port_rssfeed  = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"; 
$fsfcms_port_rssfeed  .= "<rss version=\"2.0\" xmlns:dc=\"http://dublincore.org/documents/dcmi-namespace/\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
$fsfcms_port_rssfeed  .= "\t<channel>\n";
$fsfcms_port_rssfeed  .= "\t\t<title>" . fsfcms_getSiteTitle() . " RSS 2.0 Feed</title>\n";
$fsfcms_port_rssfeed  .= "\t\t<link>" . $fsfcms_port_siteURL . "</link>\n";
$fsfcms_port_rssfeed  .= "\t\t<description>" . fsfcms_getSiteBrief() . "</description>\n";
$fsfcms_port_rssfeed  .= "\t\t<language>en-us</language>\n";
$fsfcms_port_rssfeed  .= "\t\t<copyright>" . fsfcms_getSiteCopyright() . "</copyright>\n";
$fsfcms_port_rssfeed  .= "\t\t<atom:link href=\"" . $fsfcms_port_siteURL . "feed/rss\" rel=\"self\" type=\"application/rss+xml\" />\n";

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
  $fsfcms_port_permalink            = $fsfcms_port_siteURL . $fsfcms_port_image_link_clean_URL;
  $fsfcms_port_rssfeed  .= "\t\t<item>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<title>" . $fsfcms_port_image_title . "</title>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<link>" . $fsfcms_port_permalink . "</link>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<description>\n\t\t\t\t&lt;img src=&quot;" . fsfcms_getPortThumbsURL() . "thumb_" . $fsfcms_port_image_filename . "&quot; width=&quot;250&quot; height=&quot;257&quot; /&gt;&lt;br /&gt; " . trim($fsfcms_port_image['caption']) . "\n\t\t\t</description>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<enclosure url=\"" . $fsfcms_port_siteURL . "images/" . $fsfcms_port_image_filename . "\" length=\"" . filesize($fsfcms_port_image_filepath . $fsfcms_port_image_filename) . "\" type=\"" . $fsfcms_port_image['type'] . "\" />\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<dc:creator>" . $fsfcms_port_image['authorFirstName'] . " " . $fsfcms_port_image['authorLastName'] . "</dc:creator>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<guid isPermaLink=\"true\">" . $fsfcms_port_permalink . "</guid>\n";
  $fsfcms_port_rssfeed  .= "\t\t\t<pubDate>" . date("D, d M Y H:i:s O", strtotime($fsfcms_port_image['postedDate'])) . "</pubDate>\n";
  $fsfcms_port_rssfeed  .= "\t\t</item>\n";
  }

$fsfcms_port_rssfeed  .= "</channel>\n";
$fsfcms_port_rssfeed  .= "</rss>\n";
echo $fsfcms_port_rssfeed;
} else  {
  echo "<p>Something has gone horribly wrong and the RSS feed is broken. Please keep calm and contact the appropriate authorities. </p>";
}

?>
