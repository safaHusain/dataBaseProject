<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'debugging.php';
?>

<html>
    <head>
        <title>online newspaper</title>
        <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <div id="header"> <!--header div, containing title and menu-->

            <h1>Online newspaper</h1>
            <br>
            <p>
                <a href="index.php" class="links">Home</a> <span class="bar">|</span>
                
                <?php if (!isset($_SESSION['uid'])) { ?> 
                <a href="login.php" class="links">Login</a> <span class="bar">|</span>
                <?php } ?>
                
                <?php if (isset($_SESSION['uid'])) { ?> 
                <a href="logout.php" class="links">Logout</a> <span class="bar">|</span>
                <?php } ?>
                
                <a href="register.php" class="links">Register</a> <span class="bar">|</span>
                
                <?php if (isset($_SESSION['role']) && ($_SESSION['role']) == "admin") { ?> 
                  <a href="admin_panel.php" class="links">Admin panel</a> <span class="bar">|</span>
                <?php } ?>
                  
                  <?php if (isset($_SESSION['role']) && ($_SESSION['role']) == "author") { ?> 
                  <a href="author_panel.php" class="links">Author panel</a> <span class="bar">|</span>
                <?php } ?>
                  
                  <a href="politics_page.php" class="links">Politics</a> <span class="bar">|</span>
                  <a href="business_page.php" class="links">Business</a> <span class="bar">|</span>
                  <a href="sports_page.php" class="links">Sports</a> <span class="bar">|</span>
                  <a href="art_page.php" class="links">Art</a> <span class="bar">|</span>
                   
            </p>
        </div><!--end of header div-->
    </body>
</html>