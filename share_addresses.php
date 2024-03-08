<?php
require "needed/scripts.php";
if($_SESSION['uid'] == NULL) {
	header("Location: small_login.php?next=share_addresses");
}
?>
address book coming soon