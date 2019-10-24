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
  
$fsfcms_cat_categories_output = "";
$fsfcms_cat_categories_json   = fsf_port_getCategoriesList();
$fsfcms_cat_categories        = json_decode($fsfcms_cat_categories_json,true);
$fsfcms_cat_categories_status = array_pop($fsfcms_cat_categories);

if($fsfcms_cat_categories_status  ==  200)
  {
  foreach ($fsfcms_cat_categories as $fsfcms_cat_category)
    {          
    $fsfcms_cat_categories_output .=  "<div class=\"portfolio-categories-item\"><div class=\"portfolio-categories-text\"><div class=\"portfolio-category-delete\"><a href=\"/admin/portfolio/categories/deleteCategory/" . $fsfcms_cat_category['categoryId'] . "\" onclick=\"return confirmCategoryDelete('" . $fsfcms_cat_category['categoryName'] . "')\">delete</a></div><div class=\"portfolio-category-edit\"><a href=\"/admin/portfolio/categories/editCategory/" . $fsfcms_cat_category['categoryId'] . "\">edit</a></div><span class=\"portfolio-categories-category-name\">" . $fsfcms_cat_category['categoryName'] . "</span><br />" . 
                                      "<span class=\"portfolio-categories-meta\">Category ID:&nbsp;" . $fsfcms_cat_category['categoryId'] . "</span><br />" .
                                      "<span class=\"portfolio-categories-meta\">Sort Priority:&nbsp;" . $fsfcms_cat_category['categoryPriority'] . "</span><br />" .
                                      "<p>" . $fsfcms_cat_category['categoryDescription'] . "</p></div></div>";
    }
  } else  {
  $fsfcms_cat_categories_output .=    "div class=\"portfolio-categories-item\"><span class=\"portfolio-categories-category-name\">" . $fsfcms_cat_categories_status . " Error. No categories to list. </span></div>";
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
            require "categories-menu.php";
      ?>
      <div id="page-content">    
        <?php
        if ($fsfcms_page_request_parts[3] == "newCategory")
          {
          require "portfolioNewCategory.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "addCategory") {
          require "portfolioAddCategory.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "editCategory") {
          require "portfolioEditCategory.php";
          exit;
          } elseif ($fsfcms_page_request_parts[3] == "updateCategory") {
          require "portfolioUpdateCategory.php";
          exit;
          }
        ?>
        <div id="portfolio-categories"> 
          <?php echo $fsfcms_cat_categories_output; ?>
        </div>
      </div>  <!-- End Page Content -->
    </div>  <!-- End Wrapper -->
  </body>
</html>