<?php
require "needed/start.php";

force_login();
if($session['staff'] == 1){
    redirect("my_profile.php");
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
if(password_verify(trim($_POST['field_login_password']), $session['password'])) {
    alert("Your account was deleted successfully!<br>You are welcome to register a new account at anytime!");
    // Get a flamethrower on this shit
    $killitwithfire = $conn->prepare("DELETE FROM comments WHERE comments.uid = ?");
	$killitwithfire->execute([$session['uid']]);

    $killitwithfire = $conn->prepare("DELETE FROM favorites WHERE favorites.uid = ?");
	$killitwithfire->execute([$session['uid']]);

    $killitwithfire = $conn->prepare("DELETE FROM videos WHERE videos.uid = ?");
	$killitwithfire->execute([$session['uid']]);

    $killitwithfire = $conn->prepare("DELETE FROM messages WHERE messages.sender = ?");
	$killitwithfire->execute([$session['uid']]);

    $killitwithfire = $conn->prepare("DELETE FROM messages WHERE messages.receiver = ?");
	$killitwithfire->execute([$session['uid']]);

    $killitwithfire = $conn->prepare("DELETE FROM tickets WHERE tickets.sender = ?");
	$killitwithfire->execute([$session['email']]);

    $killitwithfire = $conn->prepare("DELETE FROM views WHERE views.uid = ?");
	$killitwithfire->execute([$session['uid']]);

    // I almost forgot this, somehow.
    $killitwithfire = $conn->prepare("DELETE FROM users WHERE users.uid = ?");
	$killitwithfire->execute([$session['uid']]);

    session_start();
    session_destroy();
    die();
} else {
alert("Failure to close your account.<br>You have entered the wrong password.", "error");
}
}
?>
<div class="formTable">
<table cellpadding="5" width="700" cellspacing="0" border="0" align="center">
		<div class="tableSubtitle"><span style="float:right; font-size: 12px; font-weight: normal;"><a href="/my_profile.php">Go back</a></span> Delete my account</div>
		<div class="tableSubtitleInfo"><b><font color="#FF0000">THIS PAGE IS VERY DANGEROUS.<br>Deleting your KamTape account will permanently remove your complete profile information (Videos, Comments, Favourites etc.) from KamTape.
		This cannot be undone. </font></b></div>
		<p>
		</p><form id="tosForm" name="tosForm" method="post" onsubmit="return confirm('ARE YOU COMPLETELY SURE YOU WANT TO ERASE EVERYTHING RELATING TO YOUR ACCOUNT FROM EXISTENCE?!!!!????');">
		Please enter your password: <input type="password" name="field_login_password" size="20"><p></p>
		<p><input type="submit" value="Delete My Account" name="action_close" ></p>
        </form><p>&nbsp;</p></table>
<?php
require "needed/end.php"; ?>