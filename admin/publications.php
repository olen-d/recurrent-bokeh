<?php

if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }
if (!$fsfcms_is_logged_in)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000654";
  exit;
  }

$fsfcms_publication_cover_thumb_path = "/pubs/covers/"; //  TODO: ADD THIS TO THE CONFIG DATABASE AND ALSO UPDATE THE FRONT END PUBLICATIONS.PHP
$fsfcms_publication_date_format       = "F Y"; //TODO: ADD THIS TO THE CONFIG DATABASE AND ALSO ADD A SEPERATE OPTION FOR THE FRONT END

//  Default to the entire list of publications
$fsfcms_pub_identifier  = "all";

//
//
//  New Publication
//
//

if($fsfcms_page_request_parts[3] == "new")
  {
  $fsfcms_publication_form_action       = "/admin/content/publications/create";
  $fsfcms_publication_form_submit_value = "Create Publication";  

  $fsfcms_publication_id = "";

  require FSFCMS_ADMIN_INCLUDE_PATH . "publication-form.php";  
  // Build the list of authors and preselect the person currently logged in

    $fsfcms_announcement_authors_json = fsf_port_getAuthorsCleanURL();
    $fsfcms_announcement_authors      = json_decode($fsfcms_announcement_authors_json,true);
    $fsfcms_announcement_checkbox_id  = 0;

  foreach($fsfcms_announcement_authors as $fsfcms_announcement_author)
    {
    $fsfcms_announcement_authors_box_checked = "";
    (FSFCMS_USER_ID == $fsfcms_announcement_author['authorID'] ? $fsfcms_announcement_authors_box_checked = "checked=\"checked\"" : $fsfcms_announcement_authors_box_checked = "");
    $fsfcms_announcement_authors_output .= "<input type=\"checkbox\" name=\"announcementAuthors[]\" id=\"author" . $fsfcms_author_checkbox_id . "\" value=\"" . $fsfcms_announcement_author['authorID'] . "\" " . $fsfcms_announcement_authors_box_checked . "/><label for=\"author" . $fsfcms_announcement_checkbox_id . "\">" . $fsfcms_announcement_author['authorFirstName'] . "&nbsp;" . $fsfcms_announcement_author['authorLastName'] . "</label><br />";
    $fsfcms_announcement_checkbox_id++;
    }
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form.php";
  exit;
  } elseif  ($fsfcms_page_request_parts[2] == "createAnnouncement")  {
  $fsfp_announcement_created_unix_ts = time();

  //  Get the contents of the form
  require FSFCMS_ADMIN_INCLUDE_PATH . "announcement-form-post.php";

// Now insert all the associated data into the DB

$fsfp_insert_query  = "INSERT INTO " . FSFCMS_ANNOUNCEMENTS_TABLE . 
                      "(id, headline, article, post_date, expiration_date, created_date)
                      VALUES
                      ('', '" . $fsfp_announcement_headline . "', '" . $fsfp_announcement_article . "', " . $fsfp_announcement_post_unix_ts . ", " . 
                      $fsfp_announcement_expiration_unix_ts . ", " . $fsfp_announcement_created_unix_ts . ")";
                       
// REMEMBER TO ERROR TRAP THIS
mysql_query($fsfp_insert_query);
$fsfp_announcement_parent_id = mysql_insert_id();

// Now perform the insert to the author table - the author defaults to whatever user is currently logged in
// REMEMBER TO FIGURE OUT [1] WHO CAN CHANGE AUTHORS AND [2] HOW TO IMPLEMENT THAT FUNCTIONALITY.
$fsfp_announcement_authors_insert_values = "";
if (count($fsfp_announcement_authors) < 1)
  {
  $fsfp_announcement_authors[] = $fsfcms_userID;   //  Default to the currently logged in author if no checkbox is selected
  }
foreach ($fsfp_announcement_authors as $fsfp_announcement_author)
  {
  $fsfp_announcement_author_trimmed = trim($fsfp_announcement_author);
  $fsfp_announcement_authors_insert_values .= "('', " . $fsfp_announcement_parent_id . ", " . $fsfp_announcement_author_trimmed . "),";
  }
        
  // Important - drop the last comma off the string
  $fsfp_announcement_authors_insert_values = rtrim($fsfp_announcement_authors_insert_values, ",");             
  $fsfp_announcement_authors_query = "INSERT INTO " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . " VALUES " . $fsfp_announcement_authors_insert_values;
  // mysql_query($fsfp_announcement_authors_query);
  }
//
//
//  Edit Publication
//
//

/*  Check for a publication ID or slug, TODO autodetect regular or pretty  */
if($fsfcms_page_request_parts[3] == "edit")
  {
  $fsfcms_pub_identifier  = $fsfcms_page_request_parts[4];
  }

if(isset($_GET['publicationId']))
  {
  
  }

if(isset($_GET['publicationSlug']))
  {
  
  }


$fsfcms_publications  = json_decode(fsf_cms_getPublications($fsfcms_pub_identifier),true);

foreach($fsfcms_publications as $fsfcms_publication)
  {
  $fsfcms_publication_id                  = $fsfcms_publication['id'];
  $fsfcms_publication_title               = $fsfcms_publication['title'];
  $fsfcms_publication_subtitle            = $fsfcms_publication['subtitle'];
  $fsfcms_publication_title_slug          = $fsfcms_publication['titleSlug'];
  $fsfcms_publication_author_first_name   = $fsfcms_publication['authorFirstName'];
  $fsfcms_publication_author_middle_name  = $fsfcms_publication['authorMiddleName'];
  $fsfcms_publication_author_last_name    = $fsfcms_publication['authorLastName'];
  $fsfcms_publication_title_slug          = $fsfcms_publication['titleSlug'];
  $fsfcms_publication_volume              = $fsfcms_publication['volume'];
  $fsfcms_publication_issue               = $fsfcms_publication['issue'];
  $fsfcms_publication_publish_date_ts     = $fsfcms_publication['publishDate'];
  $fsfcms_publication_copyright           = $fsfcms_publication['copyright'];
  $fsfcms_publication_edition             = $fsfcms_publication['edition'];
  $fsfcms_publication_sold                = $fsfcms_publication['sold'];
  $fsfcms_publication_description         = $fsfcms_publication['description'];
  $fsfcms_publication_width               = $fsfcms_publication['width'];
  $fsfcms_publication_height              = $fsfcms_publication['height'];
  $fsfcms_publication_units               = $fsfcms_publication['units'];
  $fsfcms_publication_pages               = $fsfcms_publication['pages'];
  $fsfcms_publication_binding             = $fsfcms_publication['binding'];
  $fsfcms_publication_price               = $fsfcms_publication['price'];
  $fsfcms_publication_cover_thumb_fn      = $fsfcms_publication['coverThumbFileName'];
  $fsfcms_publication_purchase_link       = $fsfcms_publication['purchaseLink'];
  
  echo  "
        <div id=\"publications-available\">
        <div class=\"publication-item\">
          <div class=\"publication-thumb-container\">
            <div class=\"publication-thumb-container-image\">
              <a href=\"\"><img class=\"publication-cover\" alt=\"The cover of " . $fsfcms_publication_title . ": " . $fsfcms_publication_subtitle . "\" src=\"" . $fsfcms_publication_cover_thumb_path . $fsfcms_publication_cover_thumb_fn . "\" /></a>
              <p class=\"publication-id\">" . str_pad($fsfcms_publication_id,4,0,STR_PAD_LEFT) . "</p>
            </div>
          </div>
          <div class=\"publication-text-container\">
            <div class=\"content-publications-delete\"><a href=\"/admin/content/publications/delete/" . $fsfcms_publication_id . 
              "\" onclick=\"return confirmPublicationDelete('" . $fsfcms_publication_title . ": " . $fsfcms_publication_subtitle . "')\">delete</a></div><div class=\"content-publications-edit\"><a href=\"/admin/content/publications/edit/" . $fsfcms_publication_id . "\">edit</a></div>
            <span class=\"publication-title\">" . $fsfcms_publication_title . ":</span> <span class=\"publication-subtitle\">" . $fsfcms_publication_subtitle . "</span><br />
            <span class=\"publication-meta\">Volume " . $fsfcms_publication_volume . " Issue " . $fsfcms_publication_issue . "</span><br />
            <span class=\"publication-meta\">" . date($fsfcms_publication_date_format,$fsfcms_publication_publish_date_ts) . "</span><br />
            <span class=\"publication-meta\">" . $fsfcms_publication_author_first_name . " " . $fsfcms_publication_author_last_name . "</span>
            <p>" . $fsfcms_publication_description . "</p>
            <span class=\"publication-meta\">" . trim(trim($fsfcms_publication_width,"0"),".") . " x " . trim(trim($fsfcms_publication_height,"0"),".") . " " . $fsfcms_publication_units . "</span><br />
            <span class=\"publication-meta\">" . $fsfcms_publication_pages . " pages</span><br />
            <span class=\"publication-meta\">" . $fsfcms_publication_binding . "</span><br />
            <span class=\"publication-meta\">$" . $fsfcms_publication_price . "</span><br />
          </div>
        </div><!--  End Published Item          -->
        </div><!--  End Publications Available  -->
        ";          
  }

?>