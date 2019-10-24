<?php

require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDB.php";

// Initialize Script Variables
$fsfcms_getAnnouncements_output = array();
$fsfcms_getAnnouncements_now    = time();

if(isset($_GET['announcement_id']))
  {
  $fsfcms_getAnnouncements_announcement_id = $_GET['announcement_id'];
  if($fsfcms_getAnnouncements_announcement_id != "all")
    {
    $fsfcms_getAnnouncements_where_clause = " WHERE " . FSFCMS_ANNOUNCEMENTS_TABLE . ".id = " . $fsfcms_getAnnouncements_announcement_id . " LIMIT 1";   
    } else  {
    $fsfcms_getAnnouncements_where_clause = " ORDER BY post_date DESC";
    }
  } else  {
  $fsfcms_getAnnouncements_where_clause = " WHERE expiration_date > " . $fsfcms_getAnnouncements_now . " AND post_date < " . $fsfcms_current_time . " ORDER BY post_date DESC";
  }  

// Get the announcements
$fsfcms_getAnnouncements_query  = "SELECT " . FSFCMS_ANNOUNCEMENTS_TABLE . ".id AS announcementId, headline, article, post_date, expiration_date, " . $fsfcms_users_table . ".id AS authorId, name_first, name_middle, name_last FROM " . 
                                  FSFCMS_ANNOUNCEMENTS_TABLE . " INNER JOIN " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . " ON " . FSFCMS_ANNOUNCEMENTS_TABLE . ".id = "
                                  . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . ".announcement_parent_id INNER JOIN " .
                                  $fsfcms_users_table . " ON " . FSFCMS_ANNOUNCEMENT_AUTHORS_TABLE . ".user_id = " . $fsfcms_users_table . 
                                  ".id" . $fsfcms_getAnnouncements_where_clause;

$fsfcms_getAnnouncements_result = mysql_query($fsfcms_getAnnouncements_query);

if($fsfcms_getAnnouncements_result)
  {
  $fsfcms_total_announcements = mysql_num_rows($fsfcms_getAnnouncements_result);
  if($fsfcms_total_announcements > 0)
    { 
    while($fsfcms_getAnnouncements_row = mysql_fetch_assoc($fsfcms_getAnnouncements_result))
      {
      $fsfcms_getAnnouncements_output[] = array(
                                                    id                =>  $fsfcms_getAnnouncements_row['announcementId'],
                                                    headline          =>  $fsfcms_getAnnouncements_row['headline'],
                                                    article           =>  $fsfcms_getAnnouncements_row['article'],
                                                    postedDate        =>  $fsfcms_getAnnouncements_row['post_date'],
                                                    expirationDate    =>  $fsfcms_getAnnouncements_row['expiration_date'],
                                                    authorId          =>  $fsfcms_getAnnouncements_row['authorId'],
                                                    authorFirstName   =>  $fsfcms_getAnnouncements_row['name_first'],
                                                    authorMiddleName  =>  $fsfcms_getAnnouncements_row['name_middle'],
                                                    authorLastName    =>  $fsfcms_getAnnouncements_row['name_last']
                                                    );            
      }
    $fsfcms_getAnnouncements_output['status'] = 200;
    } else  {
      $fsfcms_getAnnouncements_output['status'] = 404; 
    }  
  } else  {
  $fsfcms_getAnnouncements_output['status'] = 503;
  }   

header('Content-Type: application/json');
echo json_encode($fsfcms_getAnnouncements_output); 
?>
