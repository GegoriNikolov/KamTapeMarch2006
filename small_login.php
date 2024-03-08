<?php 
require "needed/scripts.php";
if($_SESSION['uid'] != NULL) {
	header("Location: index.php");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>YouTube - Broadcast Yourself.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/css/styles_yts1164775696.css" rel="stylesheet" type="text/css">
<link href="/css/base_yts1164787633.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function hideDivs(){
  var arr = document.getElementsByTagName('div')
    for(var i=0; i<arr.length;i++){
    if (arr[i].id == 'hidelogin') {
      arr[i].style.display = (arr[i].style.display == 'block')? 'none':'block';
			if (arr[i].style.display== 'block') {
			  document.loginForm.username.focus();
			}
    }
  }
}
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" bgcolor="#ffffff" vlink="#0000ff" link="#0000ff">

<table width="200" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="100%">
<div class="label">Address Book</div><br />
			   
			<!--<strong><a href="#" onclick="hideDivs();return false;" style="border-bottom: dotted 1px;text-decoration: none;">Log In to YouTube</a></strong>
			   <br /> <br />
-->			<form method="post" name="loginForm" id="loginForm">
				<input type="hidden" name="current_form" value="loginForm" />
				<div id="hidelogin" style="display: block">
				<table width="98%" cellpadding="2" cellspacing="0" border="0">
					<tr>
						<td align="left" width="90"><span class="label">User Name:</span></td>
						<td align="left"><input tabindex="1" type="text" size="12" name="username" value=""></td>
					</tr>
					<tr>
						<td align="left" width="90"><span class="label">Password:</span></td>
						<td align="left"><input tabindex="2" type="password" size="12" name="password"></td>
					</tr>
					<tr>
						<td align="left" width="90"><span class="label">&nbsp;</span></td>
						<td><input type="submit" name="action_login" value="Log In"></td>
					</tr>
					<tr>
						<td align="center" colspan="2">
<div class="hpLoginForgot smallText">
					<b>Forgot:</b> <a href="forgot_username" target="_blank">Username</a> | <a href="/forgot" target="_blank">Password</a>
					</div>
<br></td>
					</tr>
       </table>
			 </div>
			      <table style="background-color: #EAEAEA;" width="200">
			          <tr>
				           <td style="text-align: center;">				
									   <div style="font-size: 12px; font-weight: normal; padding-top: 6px; padding-bottom: 6px;">
		          <div style="font-size: 14px; font-weight: bold; text-align: center; color: #000000; padding-bottom: 5px;">
							       <a href="javascript:window.parent.signup()">Sign up</a> for a free<br/ >YouTube account</div>
			       	       </div>
										 </div>
			           	</td>
			         </tr>
		        </table>
</body>
</html>