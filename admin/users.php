<?php

if(count(get_included_files()) == 1)
  {
  echo "Software Failure. Press left mouse button to continue. <br />Guru Meditation #00000001.00000021";
  exit;
  }

require 'pass/phpass-0.3/PasswordHash.php';

$fsfcms_users_list_output = "";
$fsfcms_users_list_json   = fsfcms_cms_getUsersList();
$fsfcms_users_list        = json_decode($fsfcms_users_list_json,true);
$fsfcms_users_list_status = array_pop($fsfcms_users_list);

if ($fsfcms_users_list_status == 200)
  {
  foreach ($fsfcms_users_list as $fsfcms_user)
    {          
    $fsfcms_users_list_output     .=  "<div class=\"users-list-item\"><div class=\"users-list-text\"><div class=\"users-list-delete\"><a href=\"/admin/users/deleteUser/" . $fsfcms_user['userId'] . "\" onclick=\"return confirmUserDelete('" . $fsfcms_user['firstName'] . " " . $fsfcms_user['lastName'] . "')\">delete</a></div><div class=\"users-list-edit\"><a href=\"/admin/users/editUser/" . $fsfcms_user['userId'] . "\">edit</a></div><span class=\"users-list-name\">" . $fsfcms_user['lastName'] . ", " . $fsfcms_user['firstName']. " " . $fsfcms_user['middleName'] . "</span><br />" . 
                                      "<p>" . $fsfcms_user['userName'] . "</p>" .
                                      "<span class=\"users-list-meta\">User ID:&nbsp;" . $fsfcms_user['userId'] . "</span><br />" .
                                      "<span class=\"users-list-meta\">Email Address:&nbsp;" . $fsfcms_user['emailAddress'] . "</span><br />" .
                                      "<p>" . $fsfcms_user['biography'] . "</p></div></div>";
    }
  }
?>
<html>
  <head>
    <title>
      <?php echo $fsfcms_this_page_title; ?>
    </title>

    <!--  STYLES          -->
    <link rel="stylesheet" href="/admin/admin-style.css" type="text/css" />
    <link rel="stylesheet" href="/admin/admin-color-schemes.css" type="text/css" />
    
    <!--  CUSTOM FONTS    -->
    <link href='http://fonts.googleapis.com/css?family=Lato:400,900' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400,700' rel='stylesheet' type='text/css'>

    <!--  JAVASCRIPT      -->
    <script src="javaScript/confirmUserDelete.js"></script>

		<script type='text/javascript'>
			<!-- BEGIN
function authorToSlug()
  {
  var authorSlug            = "";
  var authorFirstNameField  = document.getElementById('firstName');
  var authorMiddleNameField = document.getElementById('middleName');
  var authorLastNameField   = document.getElementById('lastName');
  var authorSlugField       = document.getElementById('authorSlug');

  var authorSlug = authorLastNameField.value.trim() + '-' + authorFirstNameField.value.trim() + '-' + authorMiddleNameField.value.trim();
  var authorSlug = authorSlug.trim();
  var authorSlug = authorSlug.replace(/-/g,' ');                                                  //  Change dashes to spaces
  var authorSlug = authorSlug.replace(/\b(a|after|although|an|and|as|at|be|both|but|by|from|for|if|in|nor|of|on|or|over|so|the|though|to|up|via|when|while|would|yet)\b/gi,'')
  .replace(/[^\w\s]/g, '')                                                                        // Remove punctuation
  .trim()
  .replace(/\s+/g, '-')                                                                           // Change spaces to dashes
  .replace(/\-\-+/g, '-')                                                                         // Replace multiple dashes with a single dash
  .toLowerCase()
  .substring(0,255)
  .trim();

  authorSlugField.value  = authorSlug; 
  }


			// End -->
		</script>
  </head>

  <body>
    <div id="wrapper">
      <div id="title-bar">
        <h1>
          <?php echo $fsfcms_breadcrumbs; ?>
        </h1>         
      </div>
      <div id="author-bar">
        <p>
          Hola, <?php echo FSFCMS_AUTHOR_USER_NAME; ?>!  <!-- REM Make the username a link to the profile -->     <a href="/admin/index.php?gc=logout">Log Out</a>   
        </p>
      </div>
      <?php 
      require "top-menu.php";
      require "user-menu.php"
      ?>
      <div id="page-content">    
        <?php
        if ($fsfcms_page_request_parts[2] == "newUser")
          {
          //
          //  New User
          //
          $fsfcms_user_form_action       = "/admin/users/addUser";
          $fsfcms_user_form_submit_value = "Add User";

          $fsfcms_user_id             = "";
          $fsfcms_user_name           = "";
          $fsfcms_user_first_name     = "";
          $fsfcms_user_middle_name    = "";
          $fsfcms_user_last_name      = "";
          $fsfcms_user_author_slug    = "";
          $fsfcms_user_email_address  = "";
          $fsfcms_user_biography      = "";

          require FSFCMS_ADMIN_INCLUDE_PATH . "user-form.php"; 
          exit;
          } elseif ($fsfcms_page_request_parts[2] == "addUser") {
          //
          //  Add User to the Database
          //
          require FSFCMS_ADMIN_INCLUDE_PATH . "user-form-post.php";

          $db_port = 3306;
    
          // Base-2 logarithm of the iteration count used for password stretching
          $hash_cost_log2 = 8;

          // Do we require the hashes to be portable to older systems (less secure)?
          $hash_portable = FALSE;
          $hasher = new PasswordHash($hash_cost_log2, $hash_portable);
          // REMEMBER TO BLOW UP IF NO PASSWORD IS PROVIDED.
          $hash = $hasher->HashPassword($fsfcms_user_password);

          if (strlen($hash) < 20)
            fail('Failed to hash new password');
          unset($hasher);

          function fail($cheese,$burger)
            {echo "epic fail." . $cheese . " " . $burger;}
            
          $db = new mysqli(FSFCMS_DB_HOST, FSFCMS_DB_USERNAME, FSFCMS_DB_PASSWORD, FSFCMS_DB_NAME, $db_port);

          if (mysqli_connect_errno())
            fail('MySQL connect', mysqli_connect_error());

          ($stmt = $db->prepare('insert into fsf_cms_users (username, password, name_first, name_middle, name_last, author_slug, email_address, biography) values (?, ?, ?, ?, ?, ?, ?, ?)'))
  	       || fail('MySQL prepare', $db->error);
          $stmt->bind_param('ssssssss', $fsfcms_user_name, $hash, $fsfcms_user_first_name, $fsfcms_user_middle_name, $fsfcms_user_last_name, $fsfcms_user_author_slug, $fsfcms_user_email_address, $fsfcms_user_biography)
  	       || fail('MySQL bind_param', $db->error);
          $stmt->execute()
  	       || fail('MySQL execute', $db->error);
	
          $stmt->close();
          $db->close();
          exit;
          } elseif ($fsfcms_page_request_parts[2] == "editUser") {
          //
          //  Edit User
          //
          $fsfcms_user_form_action       = "/admin/users/updateUser";
          $fsfcms_user_form_submit_value = "Update User";
          $fsfcms_user_id                = $fsfcms_page_request_parts[3];
                 
          $fsfcms_user_info_json          = fsf_cms_getUserInfo($fsfcms_user_id);
          $fsfcms_user_info               = json_decode($fsfcms_user_info_json,true);
          $fsfcms_user_info_status        = array_pop($fsfcms_user_info);
          if ($fsfcms_user_info_status == 200)
            {
            $fsfcms_user_id             = trim($fsfcms_user_info['userId']);
            $fsfcms_user_name           = trim($fsfcms_user_info['userName']);
            $fsfcms_user_first_name     = trim($fsfcms_user_info['firstName']);
            $fsfcms_user_middle_name    = trim($fsfcms_user_info['middleName']);
            $fsfcms_user_last_name      = trim($fsfcms_user_info['lastName']);
            $fsfcms_user_author_slug    = trim($fsfcms_user_info['authorSlug']);
            $fsfcms_user_email_address  = trim($fsfcms_user_info['emailAddress']);
            $fsfcms_user_biography      = trim($fsfcms_user_info['biography']);      
          
            require FSFCMS_ADMIN_INCLUDE_PATH . "user-form.php";
            } else  {
            echo "<div id=\"error-admin\">";
            echo "<h1>error code: " . $fsfcms_user_info_status . "</h1>";
            echo "<p>" . $fsfcms_user_info['errorMessage'] . "</p>"; 
            }          
          exit;
          } elseif ($fsfcms_page_request_parts[2] == "updateUser") {
          require FSFCMS_ADMIN_INCLUDE_PATH . "user-form-post.php";
          $fsfcms_user_update_query = "UPDATE " . $fsfcms_users_table . 
                                " SET username = '" . $fsfcms_user_name . "', name_first = '" . $fsfcms_user_first_name . 
                                "', name_middle = '" . $fsfcms_user_middle_name . "', name_last = '" . $fsfcms_user_last_name .
                                "', author_slug = '" . $fsfcms_user_author_slug . 
                                "', email_address = '" . $fsfcms_user_email_address . "', biography = '" . $fsfcms_user_biography . 
                                "' WHERE id = " .
                                $fsfcms_user_id . " LIMIT 1";

          // REMEMBER TO ERROR TRAP THIS
          mysql_query($fsfcms_user_update_query);
          exit;
          } elseif ($fsfcms_page_request_parts[2] == "deleteUser") {
          //
          //  Delete User
          //
          $fsfcms_user_id             = $fsfcms_page_request_parts[3];
          $fsfcms_delete_user_status  = array();

          $fsfcms_user_delete_query = "DELETE FROM " . FSFCMS_USERS_TABLE . " WHERE id = " . $fsfcms_user_id . " LIMIT 1";
          if(mysql_query($fsfcms_user_delete_query))
            {
            $fsfcms_delete_user_status['user']  = 200;
            } else  {
            $fsfcms_delete_user_status['user']  = 500;
            }
          $fsfcms_user_associations_delete_query = "DELETE FROM " . FSFCMS_AUTHORS_TABLE . " WHERE user_id = " . $fsfcms_user_id;
          if(mysql_query($fsfcms_user_associations_delete_query))
            {
            $fsfcms_delete_user_status['associations']  = 200;
            } else  {
            $fsfcms_delete_user_status['associations']  = 500;
            }
          print_r($fsfcms_delete_user_status);
          exit;
          }
        ?>
        <div id="users-list"> 
          <?php echo $fsfcms_users_list_output; ?>
        </div>
      </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->  
  </body>
</html>