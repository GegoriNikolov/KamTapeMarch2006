<?php 
require "needed/start.php";
force_login();
if($_SERVER["REQUEST_METHOD"] == "POST") {
echo '		<div class="confirmBox">
		A confirmation email has been sent to your email address.
	</div>';
}
?>
<div id="pageTable">

	<h1>Please Confirm Your Email</h1>
	

	<p>Before you can use certain features on YouTube, we need to verify your email address. Enter it below, and when you receive the confirmation email, check your email and click on the link provided to confirm your account. If you do not receive the confirmation message within a few minutes, please check your bulk or spam folders.</p>
	
	<div class="dataEntryTable">
		<form method="post" action="/email_confirm">
		<input type="hidden" name="next" value="<?php echo htmlspecialchars($_GET["next"]); ?>">
		<input type="hidden" name="origin" value="">
		<div id="emailEntry" style="">
		<table cellspacing='0' cellpadding='3' border='0' width='100%'>
			<tr>
				<td align='right' class="formLabel"><label for="email">Send a confirmation email to:</label></td>
				<td align='left'><input id="email" type="text" size="40" maxlength="60" name="email" value="<?php echo htmlspecialchars($session['email']) ?>"></td>
		</tr>
		<tr>
			<td class="formLabel">&nbsp;</td>
			<td colspan="2">
				<input name="action_send" type="submit" value="Send Email">
			</td>
		</tr>
		</table>
		</div>
		</form>
	</div>
</div>

<?php 
require "needed/end.php";
?>