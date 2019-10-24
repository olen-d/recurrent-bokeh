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
  
$fsfcms_options_output          = "";
$fsfcms_options_json            = fsfcms_admin_get_options();
$fsfcms_options                 = json_decode($fsfcms_options_json,true);

// Get the available templates
$fsfcms_options_templates_json    = fsfcms_admin_get_templates();
$fsfcms_options_templates         = json_decode($fsfcms_options_templates_json,true);

foreach($fsfcms_options_templates as $fsfcms_options_template)
  {
  ($fsfcms_options['currentTemplate'] == $fsfcms_options_template['templateSlug'] ? $fsfcms_options_template_selected = " selected" : $fsfcms_options_template_selected = "");
  $fsfcms_options_templates_output .= "<option value=\"" . $fsfcms_options_template['templateSlug'] . "\"" . $fsfcms_options_template_selected . ">" . $fsfcms_options_template['templateName'] . "</option>";
  }

//  Get the site contact information

$fsfcms_site_contact_info             = fsfcms_getSiteContact();

// Get the list of pages associated with the current template
$fsfcms_options_current_template_id   = fsfcms_getTemplateIDbySlug($fsfcms_options['currentTemplate']);
$fsfcms_options_template_pages        = fsfcms_cms_get_template_pages($fsfcms_options_current_template_id);
$fsfcms_options_template_pages_status = array_pop($fsfcms_options_template_pages);

if ($fsfcms_options_template_pages_status == 200)
  {
  foreach($fsfcms_options_template_pages as $fsfcms_options_template_page)
    {
    ($fsfcms_options['templateHomePage'] == $fsfcms_options_template_page['pageSlug'] ? $fsfcms_options_template_page_selected = " selected" : $fsfcms_options_template_page_selected = "");
    $fsfcms_options_template_pages_output .= "<option value=\"" . $fsfcms_options_template_page['pageSlug'] . "\"" . $fsfcms_options_template_page_selected . ">" . $fsfcms_options_template_page['pageName'] . "</option>";
    }
  } else  {
  $fsfcms_options_template_pages_output .= "<option value=\"\">No pages were found.</option>";
  }

// Generate a list of timezones
$fsfcms_options_timezones = timezone_identifiers_list(4096,"US"); // Just the US timezones for now, update this later and do something fancy like an AJAX continent/country dropdown

foreach($fsfcms_options_timezones as $fsfcms_options_timezone)
  {
  ($fsfcms_options['serverTimeZone'] == $fsfcms_options_timezone ? $fsfcms_options_timezone_selected = " selected" : $fsfcms_options_timezone_selected = "");
  $fsfcms_options_timezones_output .= "<option value=\"" . $fsfcms_options_timezone . "\"" . $fsfcms_options_timezone_selected . ">" . str_replace("_"," ",$fsfcms_options_timezone) . "</option>";
  }

//  Generate a list of possible date & time formats
$fsfcms_options_datetime_formats        = fsfcms_cms_get_datetime_formats();
$fsfcms_options_datetime_formats_status = array_pop($fsfcms_options_datetime_formats);

if ($fsfcms_options_datetime_formats_status['status'] == 200)
  {
  foreach($fsfcms_options_datetime_formats as $fsfcms_options_datetime_format)
    {
    ($fsfcms_options['dateTimeFormat'] == $fsfcms_options_datetime_format['dateTimeFormat'] ? $fsfcms_options_datetime_format_selected = " selected" : $fsfcms_options_datetime_format_selected = "");
    $fsfcms_options_datetime_formats_output .= "<option value=\"" . $fsfcms_options_datetime_format['dateTimeFormat'] . "\"" . $fsfcms_options_datetime_format_selected . ">" . $fsfcms_options_datetime_format['dateTimeFormatDescription'] . "</option>";
    }
  }
$fsfcms_options_datetime_formats_output .= "<option value=\"newDateTimeFormat\">Create a new date and time format...</option>";  

// Page thumbnails?
// Consider splitting this into global (no/yes) and a checkbox list of individual pages (archives, categories, etc.)
$fsfcms_options_page_thumbs_choices = array("no", "yes");

foreach($fsfcms_options_page_thumbs_choices as $fsfcms_options_page_thumbs_choice)
  {
  ($fsfcms_options['globalPaged'] == $fsfcms_options_page_thumbs_choice ? $fsfcms_options_page_thumbs_selected = " selected" : $fsfcms_options_page_thumbs_selected = "");
  $fsfcms_options_page_thumbs_output .= "<option value=\"" . $fsfcms_options_page_thumbs_choice . "\"" . $fsfcms_options_page_thumbs_selected . ">" . ucwords($fsfcms_options_page_thumbs_choice) . "</option>";
  }

if ($fsfcms_page_request_parts[2] == "updateOptions")
  {
  $fsfcms_options_new_site_url                = $_POST['optionSiteURL'];
  $fsfcms_options_new_site_minimum_url        = $_POST['optionSiteMinimumURL'];
  $fsfcms_options_new_site_url_shortener_url  = $_POST['optionSiteURLshortenerURL'];
  $fsfcms_options_new_site_title              = $_POST['optionSiteTitle'];
  $fsfcms_options_new_site_brief              = $_POST['optionSiteBrief'];    
  $fsfcms_options_new_site_copyright          = $_POST['optionSiteCopyright'];
  $fsfcms_options_new_site_templates          = $_POST['optionTemplates'];
  $fsfcms_options_new_site_templates_path     = $_POST['optionTemplatesPath'];
  $fsfcms_options_new_site_home_page          = $_POST['optionTemplateHomePages'];
  $fsfcms_options_new_site_image_url          = $_POST['optionSiteImageURL'];
  $fsfcms_options_new_site_image_path         = $_POST['optionSiteImagePath'];
  $fsfcms_options_new_site_thumbs_url         = $_POST['optionSiteThumbsURL'];
  $fsfcms_options_new_site_thumbs_path        = $_POST['optionSiteThumbsPath'];
  $fsfcms_options_new_site_thumbs_width       = $_POST['optionSiteThumbsWidth'];
  $fsfcms_options_new_site_thumbs_height      = $_POST['optionSiteThumbsHeight'];
  $fsfcms_options_new_site_page_thumbs        = $_POST['optionSitePageThumbs'];
  $fsfcms_options_new_site_thumbs_per_page    = $_POST['optionThumbsPerPage'];
  $fsfcms_options_new_site_server_timezone    = $_POST['optionServerTimezone'];
  $fsfcms_options_new_site_date_time_format   = $_POST['optionDateTimeFormat'];
  $fsfcms_options_new_feed_items              = $_POST['optionFeedItems'];
  $fsfcms_options_new_site_API_URL            = $_POST['optionSiteAPIURL'];
  $fsfcms_options_new_site_admin_include_path = $_POST['optionSiteAdminIncludePath'];
  $fsfcms_options_new_backup_database_path    = $_POST['optionBackupDatabasePath'];
  
  //  TODO  Fix this mess - make it a prepared statement using mysqli  
  $fsfcms_options_update_queries =  array();
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_url . "' WHERE `setting` = 'siteURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_minimum_url . "' WHERE `setting` = 'siteMinimumURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_url_shortener_url . "' WHERE `setting` = 'siteURLshortenerURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_title . "' WHERE `setting` = 'siteTitle' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_brief . "' WHERE `setting` = 'siteBrief' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_copyright . "' WHERE `setting` = 'siteCopyright' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_templates . "' WHERE `setting` = 'currentTemplate' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_templates_path . "' WHERE `setting` = 'templatesPath' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_home_page . "' WHERE `setting` = 'templateHomePage' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_image_url . "' WHERE `setting` = 'portImageURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_image_path . "' WHERE `setting` = 'portImagePath' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_thumbs_url . "' WHERE `setting` = 'portThumbsURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_thumbs_path . "' WHERE `setting` = 'portThumbsPath' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_thumbs_width . "' WHERE `setting` = 'portThumbsWidth' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_thumbs_height . "' WHERE `setting` = 'portThumbsHeight' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_page_thumbs . "' WHERE `setting` = 'globalPaged' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_thumbs_per_page . "' WHERE `setting` = 'globalItemsPerPage' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_server_timezone . "' WHERE `setting` = 'serverTimeZone' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_date_time_format . "' WHERE `setting` = 'dateTimeFormat' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_feed_items . "' WHERE `setting` = 'feedItems' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_API_URL . "' WHERE `setting` = 'siteAPIURL' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_site_admin_include_path . "' WHERE `setting` = 'adminIncludePath' LIMIT 1";
  $fsfcms_options_update_queries[] = "UPDATE " . $fsfcms_config_table . " SET `value` = '" . $fsfcms_options_new_backup_database_path . "' WHERE `setting` = 'backupDatabasePath' LIMIT 1";

  foreach($fsfcms_options_update_queries AS $fsfcms_options_update_query)
    {  
    mysql_query($fsfcms_options_update_query);
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

    <script type='text/javascript'>
      <!-- BEGIN

      function createNewDateTimeFormat()
        {
        var selectedValue = optionDateTimeFormat.options[optionDateTimeFormat.selectedIndex].value;
        
        if(selectedValue == "newDateTimeFormat")
          {
          document.getElementById('newDateTimeFormatContainer').style.display = 'block';
          }        
        }

      function addDateTimeFormat()
        {
        var newDTFFormat          = document.getElementById('optionNewDateTimeFormat').value;
        var newDTFDescription     = document.getElementById('optionNewDateTimeFormatDescription').value;
        var newDTFSortPriority    = document.getElementById('optionNewDateTimeFormatSortPriority').value;
        var dateTimeFormatSelect  = document.getElementById('optionDateTimeFormat');
        // send the request to the database via XMLHttpRequest
        var http = new XMLHttpRequest();
        var url   = '<?php echo FSFCMS_API_URL ?>fsf.admin.createNewDateTimeFormat.php';
        var parameters  = 'optionNewDateTimeFormat=' + newDTFFormat + '&optionNewDateTimeFormatDescription=' + newDTFDescription + '&optionNewDateTimeFormatSortPriority=' + newDTFSortPriority;
        http.open('post',url,true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function() {//Call a function when the state changes.
          if(http.readyState == 4 && http.status == 200) {
            /* alert(http.responseText);*/

            var resultJSON = JSON.parse(http.responseText);
            if (resultJSON.status == 200)
              {        
              document.getElementById('newDateTimeFormatResult').innerHTML = '<p>' + resultJSON.message + '</p>';
              } else  {
              document.getElementById('newDateTimeFormatResult').innerHTML = '<p>' + resultJSON.errorMessage + '</p><p>' + resultJSON.errorDetail + '</p>';
              }
            } else  {
            document.getElementById('newDateTimeFormatResult').innerHTML = '<p class=&quot;fail&quot;>' + http.responseText + '</p>'
            }
          }
        http.send(parameters);        
        
        dateTimeFormatSelect.options[dateTimeFormatSelect.options.length] = new Option(newDTFDescription,newDTFFormat);
        for(i = 0; i < dateTimeFormatSelect.options.length; i++)
          {
          if(dateTimeFormatSelect.options[i].text == newDTFDescription)
            {
            dateTimeFormatSelect.selectedIndex = i;
            break;
            }
          }
        document.getElementById('newDateTimeFormatContainer').style.display = 'none';
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
      <?php require "top-menu.php";
          // require "options-menu.php";
      ?>
      <div id="page-content">
        <div id="options-form-container">
          <form name="optionsForm" id="optionsForm" method="post" enctype="multipart/form-data" action="/admin/options/updateOptions">
            <input type="hidden" name="page" value="all">
            <fieldset>
              <legend>Site Options</legend>
              <div class="form-field">
                <label for="optionSiteURL">Site URL</label><br />
                <input name="optionSiteURL" id="optionSiteURL" type="text" value="<?php echo $fsfcms_options['siteURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteMinimumURL">Site Minimum URL</label><br />
                <input name="optionSiteMinimumURL" id="optionSiteMinimumURL" type="text" value="<?php echo $fsfcms_options['siteMinimumURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteURL">URL for Short Links</label><br />
                <input name="optionSiteURLshortenerURL" id="optionSiteURLshortenerURL" type="text" value="<?php echo $fsfcms_options['siteURLshortenerURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteTitle">Site Name</label><br />
                <input name="optionSiteTitle" id="optionSiteTitle" type="text" value="<?php echo $fsfcms_options['siteTitle']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteBrief">Brief Description of the Site</label><br />
                <textarea name="optionSiteBrief" id="optionSiteBrief" rows="4" cols="60" /><?php echo $fsfcms_options['siteBrief']; ?></textarea>
              </div>
              <div class="form-field">
                <label for="optionSiteCopyright">Site Copyright Message</label><br />
                <input name="optionSiteCopyright" id="optionSiteCopyright" type="text" value="<?php echo $fsfcms_options['siteCopyright']; ?>" size="40" />
              </div>
            </fieldset>
            <fieldset>
              <legend>Contact Information</legend>
              <div class="form-field">
                <label for="optionAddress1">Address Line 1</label><br />
                <input name="optionAddress1" id="optionAddress1" type="text" value="<?php echo $fsfcms_site_contact_info['address1']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionAddress2">Address Line 2</label><br />
                <input name="optionAddress2" id="optionAddress2" type="text" value="<?php echo $fsfcms_site_contact_info['address2']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionCity">City</label><br />
                <input name="optionCity" id="optionCity" type="text" value="<?php echo $fsfcms_site_contact_info['city']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionState">State</label><br />
                <input name="optionState" id="optionState" type="text" value="<?php echo $fsfcms_site_contact_info['state']; ?>" size="20" />
              </div>
              <div class="form-field">
                <label for="optionZipCode">Zip Code</label><br />
                <input name="optionZipCode" id="optionZipCode" type="text" value="<?php echo $fsfcms_site_contact_info['zipCode']; ?>" size="20" />
              </div>
              <div class="form-field">
                <label for="optionEmail">Email Address</label><br />
                <input name="optionEmail" id="optionEmail" type="text" value="<?php echo $fsfcms_site_contact_info['siteEmail']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionPhone">Phone Number</label><br />
                <input name="optionPhone" id="optionPhone" type="text" value="<?php echo $fsfcms_site_contact_info['sitePhone']; ?>" size="40" />
              </div>
            </fieldset>
            <fieldset>
              <legend>Templates</legend>
              <div class="form-field">
                <label for="optionTemplates">Current Template</label><br />
                <select name="optionTemplates" id="optionTemplates">
                  <?php echo $fsfcms_options_templates_output; ?>
                </select>
              </div>
              <div class="form-field">
                <label for="optionTemplatesPath">Templates Path</label><br />
                <input name="optionTemplatesPath" id="optionTemplatesPath" type="text" value="<?php echo $fsfcms_options['templatesPath']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionTemplateHomePages">Current Home Page</label><br />
                <select name="optionTemplateHomePages" id="optionTemplateHomePages">
                  <?php echo $fsfcms_options_template_pages_output; ?>
                </select>
              </div>
            </fieldset>
            <fieldset>
              <legend>Images and Thumbnails</legend>
              <div class="form-field">
                <label for="optionSiteImageURL">Images URL</label><br />
                <input name="optionSiteImageURL" id="optionSiteImageURL" type="text" value="<?php echo $fsfcms_options['portImageURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteImagePath">Images Path</label><br />
                <input name="optionSiteImagePath" id="optionSiteImagePath" type="text" value="<?php echo $fsfcms_options['portImagePath']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteThumbsURL">Thumbnails URL</label><br />
                <input name="optionSiteThumbsURL" id="optionSiteThumbsURL" type="text" value="<?php echo $fsfcms_options['portThumbsURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteThumbsPath">Thumbnails Path</label><br />
                <input name="optionSiteThumbsPath" id="optionSiteThumbsPath" type="text" value="<?php echo $fsfcms_options['portThumbsPath']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteThumbsWidth">Thumbnail Width (in Pixels)</label><br />
                <input name="optionSiteThumbsWidth" id="optionSiteThumbsWidth" type="text" value="<?php echo $fsfcms_options['portThumbsWidth']; ?>" size="10" />
              </div>
              <div class="form-field">
                <label for="optionSiteThumbsHeight">Thumbnail Height (in Pixels)</label><br />
                <input name="optionSiteThumbsHeight" id="optionSiteThumbsHeight" type="text" value="<?php echo $fsfcms_options['portThumbsHeight']; ?>" size="10" />
              </div>
              <div class="form-field">
                <label for="optionSitePageThumbs">Page Thumbnails?</label><br />
                  <select name="optionSitePageThumbs" id="optionSitePageThumbs">
                    <?php echo $fsfcms_options_page_thumbs_output; ?>  
                  </select>
              </div>
              <div class="form-field">
                <label for="optionThumbsPerPage">Number of Thumbnails per Page</label><br />
                <input name="optionThumbsPerPage" id="optionThumbsPerPage" type="text" value="<?php echo $fsfcms_options['globalItemsPerPage']; ?>" size="10" />
              </div>
            </fieldset>
            <fieldset>
              <legend>Time and Date Settings</legend>
              <div class="form-field">
                <label for="optionServerTimezone">Server Timezone</label><br />
                <select name="optionServerTimezone" id="optionServerTimezone">
                  <?php echo $fsfcms_options_timezones_output; ?>
                </select>
              </div>
              <div class="form-field">
                <label for="optionDateTimeFormat">Date and Time Format</label><br />
                <select name="optionDateTimeFormat" id="optionDateTimeFormat" onchange="createNewDateTimeFormat();">
                  <?php echo $fsfcms_options_datetime_formats_output; ?>
                </select>
              </div>              
              <div id="newDateTimeFormatContainer">
                  <div class="form-field">
                    <label for="optionNewDateTimeFormat">New Date and Time Format</label><br />
                    <input name="optionNewDateTimeFormat" id="optionNewDateTimeFormat" type="text" value="" size="20" />
                  </div>
                  <div class="form-field">
                    <label for="optionNewDateTimeFormatDescription">New Format Description</label><br />
                    <input name="optionNewDateTimeFormatDescription" id="optionNewDateTimeFormatDescription" type="text" value="" size="40" />                    
                  </div>
                  <div class="form-field">
                    <label for="optionNewDateTimeFormatSortPriority">New Format Sort Priority</label><br />
                    <input name="optionNewDateTimeFormatSortPriority" id="optionNewDateTimeFormatSortPriority" type="text" value="" size="10" />                    
                  </div>
                  <div class="form-field">
                    <input type="button" class="button" name="addFormat" value="Add New Format" onclick="addDateTimeFormat();">
                  </div>
              </div>
              <div id="newDateTimeFormatResult">
              </div>
            </fieldset>
            <fieldset>
              <legend>Feed Settings (RSS and Atom)</legend>
              <div class="form-field">
                <label for="optionFeedItems">Number of Items in the Feed</label><br />
                <input name="optionFeedItems" id="optionFeedItems" type="text" value="<?php echo $fsfcms_options['feedItems']; ?>" size="10" />
              </div>
            </fieldset>
            <fieldset>
              <legend>Administrative Settings</legend>
              <div class="form-field">
                <label for="optionSiteAPIURL">API URL</label><br />
                <input name="optionSiteAPIURL" id="optionSiteAPIURL" type="text" value="<?php echo $fsfcms_options['siteAPIURL']; ?>" size="40" />
              </div>
              <div class="form-field">
                <label for="optionSiteAdminIncludePath">Administrative Includes Path</label><br />
                <input name="optionSiteAdminIncludePath" id="optionSiteAdminIncludePath" type="text" value="<?php echo $fsfcms_options['adminIncludePath']; ?>" size="60" />
              </div>
              <div class="form-field">
                <label for="optionBackupDatabasePath">Database Backups Path</label><br />
                <input name="optionBackupDatabasePath" id="optionBackupDatabasePath" type="text" value="<?php echo $fsfcms_options['backupDatabasePath']; ?>" size="60" />
              </div>
            </fieldset>
            <div class="form-field">
              <input type="submit" class="button" name="submit" value="Update">
            </div>
          </form>
        </div>  
      </div>  <!-- End Page Content -->
    </div>    <!-- End Wrapper      -->
  </body>
</html>    
