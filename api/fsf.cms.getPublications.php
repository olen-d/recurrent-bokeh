<?php

$fsfcms_get_publications_all_data_output  = array();

$fsfcms_get_publications_all_data_page    = $_GET['page'];
$fsfcms_get_publications_all_data_items   = $_GET['imagesPerPage'];

$fsfcms_get_publications_where_clause     = "";

require "../admin/cfg.php";
require "../admin/startDB.php";

/*  Autodetect if a publication ID or slug is passed and set the where clause accordingly */
if(isset($_GET['publicationId']))
  {
  $fsfcms_get_publications_where_clause = " WHERE " . FSFCMS_PUBLICATIONS_TABLE . ".id = "  . $_GET['publicationId'];
  }

if(isset($_GET['publicationSlug']))
  {
  $fsfcms_get_publications_where_clause = " WHERE " . FSFCMS_PUBLICATIONS_TABLE . ".title_slug = '" . $_GET['publicationSlug'] . "'";
  }
  
$fsfcms_get_publications_all_data_query = "SELECT " . FSFCMS_PUBLICATIONS_TABLE  . ".id,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".title,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".subtitle,
                                                  " . FSFCMS_USERS_TABLE         . ".name_first,
                                                  " . FSFCMS_USERS_TABLE         . ".name_middle,
                                                  " . FSFCMS_USERS_TABLE         . ".name_last,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".title_slug,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".volume,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".issue,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".publish_date,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".copyright,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".edition,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".sold,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".description,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".width,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".height,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".units,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".pages,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".binding,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".price,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".cover_thumb,
                                                  " . FSFCMS_PUBLICATIONS_TABLE  . ".purchase_link
                                                  FROM " . FSFCMS_PUBLICATIONS_TABLE . 
                                                  " LEFT JOIN " . FSFCMS_USERS_TABLE . 
                                                  " ON " . FSFCMS_PUBLICATIONS_TABLE . ".author_id = " . FSFCMS_USERS_TABLE . ".id LEFT JOIN " . FSFCMS_PUB_TYPES_TABLE . 
                                                  " ON " . FSFCMS_PUBLICATIONS_TABLE . ".type_id = " . FSFCMS_PUB_TYPES_TABLE . ".id " .   
                                                  $fsfcms_get_publications_where_clause . 
                                                  " ORDER BY " . FSFCMS_PUBLICATIONS_TABLE . ".publish_date DESC";

$fsfcms_get_publications_all_data_result  = mysql_query($fsfcms_get_publications_all_data_query);

if($fsfcms_get_publications_all_data_result)
    {
    if (mysql_num_rows($fsfcms_get_publications_all_data_result) > 0)
      {
      while ($fsfcms_get_publications_all_data_row = mysql_fetch_assoc($fsfcms_get_publications_all_data_result))
        {

        $fsfcms_get_publications_all_data_output[]  = array ( 
                                                      id                  => $fsfcms_get_publications_all_data_row['id'],
                                                      title               => $fsfcms_get_publications_all_data_row['title'],
                                                      subtitle            => $fsfcms_get_publications_all_data_row['subtitle'],
                                                      authorFirstName     => $fsfcms_get_publications_all_data_row['name_first'],
                                                      authorMiddleName    => $fsfcms_get_publications_all_data_row['name_middle'],
                                                      authorLastName      => $fsfcms_get_publications_all_data_row['name_last'],
                                                      titleSlug           => $fsfcms_get_publications_all_data_row['title_slug'],
                                                      volume              => $fsfcms_get_publications_all_data_row['volume'],
                                                      issue               => $fsfcms_get_publications_all_data_row['issue'],
                                                      publishDate         => $fsfcms_get_publications_all_data_row['publish_date'],
                                                      copyright           => $fsfcms_get_publications_all_data_row['copyright'],
                                                      edition             => $fsfcms_get_publications_all_data_row['edition'],
                                                      sold                => $fsfcms_get_publications_all_data_row['sold'],
                                                      description         => $fsfcms_get_publications_all_data_row['description'],
                                                      width               => $fsfcms_get_publications_all_data_row['width'],
                                                      height              => $fsfcms_get_publications_all_data_row['height'],
                                                      units               => $fsfcms_get_publications_all_data_row['units'],
                                                      pages               => $fsfcms_get_publications_all_data_row['pages'],
                                                      binding             => $fsfcms_get_publications_all_data_row['binding'],
                                                      price               => $fsfcms_get_publications_all_data_row['price'],
                                                      coverThumbFileName  => $fsfcms_get_publications_all_data_row['cover_thumb'],
                                                      purchaseLink        => $fsfcms_get_publications_all_data_row['purchase_link'],
                                                      type                => $fsfcms_get_publications_all_data_row['type'],
                                                      typeSlug            => $fsfcms_get_publications_all_data_row['type_slug']
                                                      );
        }
      } else  {
      // Empty set, print some sort of error.
      echo "<p>Empty set. </p>" . $fsfcms_get_publications_all_data_query;
      } 
    } else  {
    // Epic database fail, print some sort of error.
    // echo "<p>Epic fail. </p><p>" . $fsfcms_get_images_all_data_query . "</p><p>" . mysql_error($fsfcms_get_images_all_data_result) . "</p>"; 
    }
  header('Content-Type: application/json');
  echo json_encode($fsfcms_get_publications_all_data_output);

?>
