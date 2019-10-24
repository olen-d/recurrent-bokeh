<?php

require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getImageAuthors_output = array();

//  Get the list of authors
$fsfcms_getImageAuthors_query  = "SELECT image_parent_id, " . $fsfcms_users_table. ".id as user_id, username, name_first, name_middle, name_last, author_slug FROM " .
                                            $fsfcms_authors_table . " INNER JOIN " . $fsfcms_users_table . " ON " . $fsfcms_authors_table . ".user_id = " . 
                                            $fsfcms_users_table . ".id INNER JOIN " . $fsfcms_images_table . " ON " . $fsfcms_authors_table . ".image_parent_id = " . 
                                            $fsfcms_images_table . ".id ORDER BY post DESC, name_last ASC, name_first ASC, name_middle ASC";

$fsfcms_getImageAuthors_result = mysql_query($fsfcms_getImageAuthors_query);
if($fsfcms_getImageAuthors_result)
  {
  if(mysql_num_rows($fsfcms_getImageAuthors_result) > 0)
    {
    while($fsfcms_getImageAuthors_row = mysql_fetch_assoc($fsfcms_getImageAuthors_result))
      {
      $fsfcms_current_image_parent_id = $fsfcms_getImageAuthors_row['image_parent_id'];
      $fsfcms_authors_user_id = $fsfcms_getImageAuthors_row['user_id'];
      $fsfcms_authors_output[$fsfcms_current_image_parent_id][$fsfcms_authors_user_id] = array  (
                                                                                                userId => $fsfcms_authors_user_id,
                                                                                                userName => $fsfcms_getImageAuthors_row['username'],
                                                                                                firstName => $fsfcms_getImageAuthors_row['name_first'],
                                                                                                middleName => $fsfcms_getImageAuthors_row['name_middle'],
                                                                                                lastName => $fsfcms_getImageAuthors_row['name_last'],
                                                                                                authorSlug => $fsfcms_getImageAuthors_row['author_slug']
                                                                                                );
      }
    $fsfcms_authors_output['-99']['status']  = 200;
    } else  {
    $fsfcms_authors_output['-99']['status']  = 404;
    } 
  } else  {
  $fsfcms_authors_output['-99']['status']  = 500;
  }

header('Content-Type: application/json');
echo json_encode($fsfcms_authors_output);
?>