<?php
/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */
include 'debugging.php';
if (!isset($_SESSION['uid'])) {
  echo '**************text***************';
  $_SESSION['uid'] = 69;
}

?>

<html>

<head>
  <title>online newspaper</title>
  <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>




  <!--div for the navBar-->
  <div class="navbar">

    <!--logo image linked to the home page-->
    <div class="logo">
      <a href="index.php"> <img src="images/zoro.png" width="170" height="150" alt="Logo" /> </a>
    </div>

    <nav>

      <!--unorderd list contains main pages for the website-->
      <ul id="MenuItems">
        <li><a href="index.php">Home</a></li>
        <?php if (isset($_SESSION['uid']) && $_SESSION['uid'] == 69) { ?>
          <li><a href="login.php">Login</a></li>

        <?php
        } ?>

        <?php if (isset($_SESSION['uid']) && $_SESSION['uid'] != 69) {  ?>
          <li><a href="logout.php">Logout</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['uid']) && $_SESSION['uid'] == 69) {  ?>
          <li><a href="register.php">Register</a></li>
        <?php } ?>

        <?php if (isset($_SESSION['role']) && ($_SESSION['role']) == "admin") { ?>
          <li><a href="admin_panel.php">Admin Panel</a></li>
        <?php } ?>
        <?php if (isset($_SESSION['role']) && ($_SESSION['role']) == "author") { ?>
          <li><a href="author_panel.php">Author Panel</a></li>
        <?php } ?>
        <li><a href="politics_page.php">Politics</a></li>
        <li><a href="business_page.php">Business</a></li>
        <li><a href="sports_page.php">Sports</a></li>
        <li><a href="art_page.php">Art</a></li>
      </ul>
    </nav>
  </div>

</body>

</html>