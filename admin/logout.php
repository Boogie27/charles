
<?php
require_once $_SERVER["DOCUMENT_ROOT"]."/boutique/connection.php";
include "include/header.php";

   session_start();
   session_unset();
   session_destroy();
   header("Location: login.php");
?>