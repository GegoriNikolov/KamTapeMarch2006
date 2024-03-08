<?php
require "needed/start.php";


if($_SESSION['uid'] != NULL) {
	header("Location: index.php");
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['username']) && isset($_POST['password'])) {
		$member = $conn->prepare("SELECT uid, username, password, termination FROM users WHERE username LIKE :username");
		$member->execute([":username" => trim($_POST['username'])]);
        
		if($member->rowCount() > 0) {
			$member = $member->fetch(PDO::FETCH_ASSOC);
            if($member['termination'] == 1) {
            $username_err = "This account has been terminated.";    
            }
			if(password_verify(trim($_POST['password']), $member['password']) && $member['termination'] !== 1) {
				$_SESSION['uid'] = $member['uid'];
				header("Location: index.php");
				$lastlogin = $conn->prepare("UPDATE users SET lastlogin = CURRENT_TIMESTAMP WHERE uid = ?");
				$lastlogin->execute([$member['uid']]);
			} else {
                if($member['termination'] !== 1) {
				$password_err = "Password is incorrect!";
                }
			}
		} else {
			$username_err = "That user doesn't exist!";
		}
	}
}

if(!empty($username_err) || !empty($password_err) || !empty($confirm_password_err) || !empty($email_err)){ 
      if(!empty($username_err)) { alert(htmlspecialchars($username_err), "error"); }
      if(!empty($password_err)) { alert(htmlspecialchars($password_err), "error"); }
      if(!empty($confirm_password_err)) { alert(htmlspecialchars($confirm_password_err), "error"); }
      if(!empty($email_err)) { alert(htmlspecialchars($email_err), "error"); }
}
?>
<h1>Login</h1>

<div id="siSignupDiv">
	<h2>New to YouTube?</h2>
	
	<p>YouTube is a way to get your videos to the people who matter to you. With YouTube you can:</p>

	<ul>			
		<li>Upload, tag and share your videos worldwide</li>
		<li>Browse thousands of original videos uploaded by community members</li>
		<li>Find, join and create video groups to connect with people with similar interests</li>
		<li>Customize your experience with playlists and subscriptions</li>
		<li>Integrate YouTube with your website using video embeds or APIs</li>
	</ul>
		
	<h3><a href="/signup">Sign up now</a> and open a free account.</h3>
		
	<p>To learn more about our service, please see our <a href="/t/help_center">Help Center</a>.</p>
</div>

<div class="contentBox" style="float: right; background-color: #EEE;">
	<h2 class="marT0">YouTube Members</h2>
	<p>Login to access your account.</p>
	
	<table class="dataEntryTableSmall">
		<form name="loginForm" id="loginForm" method="post">
		<input type="hidden" name="current_form" value="loginForm" />
			
		
	
		
	
		
	
		
	
		
	
		
	

		<tr>
			<td class="formLabel">	<nobr>User Name:</nobr>
</td>
			<td class="formFieldSmall"><input tabindex="1" type="text" size="20" name="username" value=""></td>
		</tr>
		<tr>
			<td class="formLabel">	<nobr>Password:</nobr>
</td>
			<td class="formFieldSmall"><input tabindex="2" type="password" size="20" name="password"></td>
		</tr>
		<tr>
			<td class="formLabel">&nbsp;</td>
			<td class="formFieldSmall"><input type="submit" name="action_login" value="Log In">
				<p class="smallText"><b>Forgot:</b>&nbsp;<a href="/forgot_username">Username</a> | <a href="/forgot">Password</a></p>
			</td>
		</tr>
		</form>
	</table>
</div>

<script type="text/javascript">
document.loginForm.username.focus();
</script>
<?php
require "needed/end.php";
?>
