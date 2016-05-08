<?php
require_once 'vendor/autoload.php';
require_once 'Class/Database.php';

$queryDatabase = new \Forum\Database();

$results = $queryDatabase->getAllResults();

$numberOfEntries = $queryDatabase->getcount();
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8" />
      <title>Basic Image Posts Forum</title>
      <meta name="description" content="Image Post Forum">
      <meta name="author" content="Metzar">
      <link rel="icon" type="image/x-icon" href="/favicon.ico">
      <link type="text/css" rel="stylesheet" href="css/main.css">
      <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  </head>
  <header>
      <ul id="exportlist">
          <li >Posts: #<span id="counterPosts"><?php echo($numberOfEntries);?></span></li>
          <li id="active">
              <form id="csvForm" action="csvphp" method="post" enctype="multipart/form-data">
              
              <input type="submit" value="Export Button" class="largebutton">
              </form>
          </li>
          <li>Views: #<span id="counterViews">25</span></li>
      </ul>
  </header>
  <aside>
      <form id="dataForm" action="upload.php" method="post" enctype="multipart/form-data">
      <ul id="uploadlist">
          <li><input type="text" name="title" size="80" id="fileTitle"></li>
          <li>
              <input type="hidden" name="MAX_FILE_SIZE" value="2097152"/>
              <input type="file" name="fileToUpload" id="fileToUpload" required="required">
          </li>
      </ul>
      </form>
  </aside>
  <div id="response"></div>
  <section id="posts">
      <?php
      foreach ($results as $result) {
          $datotitle = $result->__get('title');
          $datoimageName = $result->__get('imageName');
          echo('<article><h2>'.$datotitle.'</h2><img src="images/'.$datoimageName.'" alt="'.$datotitle.'"/></article>');
          }
      ?>
  </section>
  <script src="js/upload.js"></script>
</html>