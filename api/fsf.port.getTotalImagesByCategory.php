<?php
require "../admin/ac.php";
require "../admin/cfg.php";
require "../admin/startDBi.php";

// Initialize Script Variables
$category_id                  = "";
$category_slug                = "";
$category_value               = "";
$column                       = "";
$db_error                     = "";
$output                       = array();
$type                         = "";

if (isset($_GET['categorySlug']))
  {
  $category_slug              = $_GET['categorySlug'];
  $category_value             = mysqli_real_escape_string($fsfcms_db_link,$category_slug);
  $column                     = FSFCMS_CATEGORY_NAMES_TABLE . ".category_slug";
  $type                       = "ss"; 
  } elseif (isset($_GET['categoryId'])) {
  $category_id                = $_GET['categoryId'];
  $category_value             = mysqli_real_escape_string($fsfcms_db_link,$category_id);
  $column                     = FSFCMS_CATEGORY_NAMES_TABLE . ".id";
  $type                       = "is"; 
  }

// Get the total number of images
$query  = "SELECT " . FSFCMS_CATEGORY_NAMES_TABLE . ".id AS category_id, category_slug, category_name, COUNT(" . FSFCMS_IMAGES_TABLE . ".id) AS total_images_by_category FROM " . FSFCMS_IMAGES_TABLE .  
                        " INNER JOIN " . FSFCMS_CATEGORIES_TABLE . " ON " . FSFCMS_IMAGES_TABLE . ".id = " . FSFCMS_CATEGORIES_TABLE . ".parent_id" .
                        " INNER JOIN " . FSFCMS_CATEGORY_NAMES_TABLE . " ON " . FSFCMS_CATEGORIES_TABLE . ".category_id = " . FSFCMS_CATEGORY_NAMES_TABLE . ".id" . 
                        " WHERE " . $column . " = ? AND " . FSFCMS_IMAGES_TABLE . ".post < ?";

if($statement  = $fsfcms_db_link->prepare($query))
  {
  if($statement->bind_param($type,$category_value,$fsfcms_current_time_mysql_format))
    {
    if($statement->execute())
      {
      if($statement->bind_result($category_id,$category_slug,$category_name,$total_images_by_category))
        { 
        while ($statement->fetch())
          {
          $output = array (
                          categoryId      =>  $category_id,
                          categorySlug    =>  $category_slug,
                          categoryName    =>  $category_name,
                          totalImages     =>  $total_images_by_category
                          ); 
          }
        $statement->free_result();
        $output['status']          = 200;
        } else  {
        $output['errorMessage']    = $fsfcms_db_error . " Bind results failed.";
        $output['status']          = 500;        
        }
      } else  {
      $output['errorMessage']      = $fsfcms_db_error . " Execute failed.";
      $output['status']            = 500;      
      }
    } else  {
    $output['errorMessage']        = $fsfcms_db_error . " Bind parameters failed.";
    $output['status']              = 500;
    }
  } else  {
  //  $db_error  = $fsfcms_db_link->error;
  $output['errorMessage']          = $db_error . " Prepare failed.";
  $output['status']                = 500;
  }
mysqli_close($fsfcms_db_link);

header('Content-Type: application/json');
echo json_encode($output);
?>