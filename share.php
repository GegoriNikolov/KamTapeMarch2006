<?php 
require "needed/scripts.php";
if (!isset($_GET['v']) && !isset($_GET['p'])) {
	session_error_index("Please specify something to share.");
	die();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>YouTube - Broadcast Yourself.</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/css/styles_yts1164775696.css" rel="stylesheet" type="text/css">
<link href="/css/base_yts1164787633.css" rel="stylesheet" type="text/css">
<!-- actb.js Powered By zichun http://www.codeproject.com/jscript/jsactb.asp -->
<script type="text/javascript" src="/js/autoComplete/actb.js"></script>
<script type="text/javascript" src="/js/autoComplete/common.js"></script>
</head>

<script type="text/javascript">
<!-- 

function fillAll()
{
	
	window.resizeTo(590,620); //resize the window to the correct size
	
	window.focus();
}
var remote;
function launch_share(n,u,w,h) {
	remote=window.open(u,n,'width='+w+',height='+h+',resizable=yes,scrollbars=no,status=0');
	remote.opener = self;
	if (remote != null) {
		if (remote.opener == null )
			remote.opener = self;
	}
	remote.focus();
}

function signup(){
	window.location = "/small_signup?next_url=" + escape(window.location)
}
//  -->
</script>

<body onload="fillAll();document.ShareForm.recipients.focus();" style="background-color:#FFFFFF;" topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" border="0">
	<tr>
 		<td  style="border-bottom: #999999 solid 1px;"><img src="/img/logo_tagline_small.gif" width="175" height="33" vspace="6" hspace="6"
border="0"></td>
	</tr>
	<tr></tr>
<td>
<table width="480" border="0" cellspacing="0" cellpadding="10" border="0">
	<tr>
	  <td width="280" valign="top">
<form id="ShareForm" name="ShareForm" method="POST">
	

		<strong>	<nobr>Email To:</nobr>
</strong> <br />
			Enter email addresses, separated by commas. Maximum 200 characters.<br />
<script type="text/javascript">
var customarray = new Array();
</script>

<textarea id="recipients" name="recipients" rows="8" cols="32" value="" size="60" maxlength="255" onchange="addressframe.updateCheckboxes();"></textarea>

<script type="text/javascript">
var obj = actb(document.getElementById('recipients'),customarray);

function update_addresses(addresses)
{
	obj.actb_keywords = addresses;
}
</script>


	
		<br /><br />
		<strong>	<nobr>Your First Name</nobr>
:</strong> (optional)<br>							
		<input type=text name="first_name" value="" maxlength="100" style="width :255px;">

	
    <br /><br />
		<strong>	<nobr>Add a personal message:</nobr>
</strong> (optional)<br>
		<textarea wrap="virtual" name="message" rows="3" cols="32">This video is awesome!</textarea>
		<br />
		<input type="submit" onClick="addressframe.removeCommas();" name="action_send" value="Send">

<?php if (isset($_GET['v'])) { ?>
			<input type="hidden" name="v" value="<?php echo htmlspecialchars($_GET['v']); ?>">
<?php } elseif (isset($_GET['p'])) { ?>
			<input type="hidden" name="p" value="<?php echo htmlspecialchars($_GET['p']); ?>">
<?php } ?>
	</form>
</td>
<td width="230"  valign="top">	
	
	<iframe id="addressframe"
  name="addressframe" style="width:230px; height:398px; border: 0px"
  src="share_addresses" scrolling="auto" frameborder="no" framespacing="0" border="0"></iframe>
		</td>
	</tr>
</table>	
</td>
</tr>
</table>

</body>
</html>