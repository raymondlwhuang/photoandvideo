<?php
	 @session_start();
	 $GV_first_name = $_SESSION["first_name"];
	 $GV_last_name = $_SESSION["last_name"];
	 $GV_id = $_SESSION["id"];
	 $GV_owner_path = $_SESSION["owner_path"];
	 $GV_name = $_SESSION["name"];
	 $GV_profile_picture = $_SESSION["profile_picture"];
	 $GV_username = $_SESSION["username"];
	 $GV_email_address = $_SESSION["email_address"];
	 $GV_admin = $_SESSION["admin"];
	 $GV_discoverable = $_SESSION["discoverable"];
?>