<?php 
require(__DIR__ . '/../needed/scripts.php');

force_login();

if(!isset($session['staff']) || $session['staff'] != 1) {
	redirect("Location: /index.php"); 
    exit;
}
$inbox = $conn->prepare("SELECT * FROM messages WHERE receiver = ? AND isRead = 0 ORDER BY created DESC");
$inbox->execute([$session['uid']]);
$inbox = $inbox->rowCount();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if ($current_page !== 'watch')  { ?>
<title>KamTape - Televise Yourself.</title>
<? } ?>
<meta name="description" content="Share your videos with friends and family">
<link rel="icon" href="../../favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon">
<link href="../../styles.css" rel="stylesheet" type="text/css">
<link rel="alternate" type="application/rss+xml" title="KamTape "" Recently Added Videos [RSS]" href="../http://www.kamtape.com/rss/global/recently_added.rss">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2537513323123758"
     crossorigin="anonymous"></script>
</head>


<body>

<table width="800" cellpadding="0" cellspacing="0" border="0" align="center">
	<tr>
		<td bgcolor="#FFFFFF" style="padding-bottom: 25px;">
		

<table width="91%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="130" rowspan="2" style="padding: 0px 5px 5px 5px;"><a href="../index.php"><img src="../img/logo_sm.gif" width="124" height="48" alt="KamTape" border="0" style="vertical-align: right; "></a></td>
		<td valign="top">
		
		<table width="670" cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td style="padding: 0px 5px 0px 5px; font-style: italic;">Upload, tag and share your videos worldwide!</td>
				<td align="right">
				
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
		
							
						<?php if(isset($session)) { ?>
                <td>Hello, <a href="../profile.php?user=<?php echo htmlspecialchars($session['username']) ?>"><?php echo htmlspecialchars($session['username']) ?></a>!&nbsp;<img src="../img/mail<? if($inbox > 0) { echo '_unread'; } ?>.gif" id="mailico" border="0">&nbsp;(<a href="../my_messages.php"><?php echo htmlspecialchars($inbox) ?></a>)</td>
                <? if ($session['staff'] == 1) {?><td style="padding: 0px 5px 0px 5px;">|</td>
                <td><a href="../" class="bold" style="color: #006f09;">Exit</a></td><? } ?>
                <td style="padding: 0px 5px 0px 5px;">|</td>
                <td><a href="../logout.php?next=<?php echo $_SERVER['REQUEST_URI'] ?>">Log Out</a></td>
            <?php } else if(!isset($session)){ ?>
                            <td><a href="../signup.php" class="bold">Sign Up</a></td>
               <td style="padding: 0px 5px 0px 5px;">|</td>
                <td><a href="../login.php">Log In</a></td>
                <?php } ?>
						<td style="padding: 0px 5px 0px 5px;">|</td>
						<td style="padding-right: 5px;"><a href="../help.php">Help</a></td>
		
										
					</tr>
				</table>
				
				<!--
								
				<table cellpadding="2" cellspacing="0" border="0">
					<tr>
						<form method="GET" action="results.php">
						<td><input type="text" value="" name="search" size="30" maxlength="128" style="color:#ff3333; font-size: 12px; padding: 2px;"></td>
						<td><input type="submit" value="Search Videos"></td>
						</form>
					</tr>
				</table>
				
										-->
				
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr valign="bottom">
		<td>
		
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td>
				<table style=" <?php if ($current_page != 'sex') { echo 'background-color: #DDDDDD; margin: 5px 2px 0px 5px; border-bottom: 1px solid #DDDDDD;'; } else { echo "background-color: #BECEEE; margin: 5px 2px 1px 0px;"; }?> " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="../img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="../img/pixel.gif" width="1" height="5"></td>
						<td><img src="../img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="../index.php">Home</a></td>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" <?php if ($current_page == 'browse' || $current_page == 'watch') { echo 'background-color: #DDDDDD; margin: 5px 2px 0px 5px; border-bottom: 1px solid #DDDDDD;'; } else { echo "background-color: #BECEEE; margin: 5px 2px 1px 0px;"; }?> " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="../img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="../img/pixel.gif" width="1" height="5"></td>
						<td><img src="../img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="../browse.php" >Watch&nbsp;Videos</a></td>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" <?php if ($current_page == 'my_videos_upload') { echo 'background-color: #DDDDDD; margin: 5px 2px 0px 5px; border-bottom: 1px solid #DDDDDD;'; } else { echo "background-color: #BECEEE; margin: 5px 2px 1px 0px;"; }?> " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="../img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="../img/pixel.gif" width="1" height="5"></td>
						<td><img src="../img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="../my_videos_upload.php">Upload&nbsp;Videos</a></td>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
				<td>
				<table style=" <?php if ($current_page == 'my_friends_invite') { echo 'background-color: #DDDDDD; margin: 5px 2px 0px 5px; border-bottom: 1px solid #DDDDDD;'; } else { echo "background-color: #BECEEE; margin: 5px 2px 1px 0px;"; }?> " cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><img src="../img/box_login_tl.gif" width="5" height="5"></td>
						<td><img src="../img/pixel.gif" width="1" height="5"></td>
						<td><img src="../img/box_login_tr.gif" width="5" height="5"></td>
					</tr>
					<tr>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
						<td style="padding: 0px 20px 5px 20px; font-size: 13px; font-weight: bold;"><a href="../my_friends_invite.php">Invite&nbsp;Friends</a></td>
						<td><img src="../img/pixel.gif" width="5" height="1"></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	
</table>

<table align="center" width="800" bgcolor="#DDDDDD" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
		<td><img src="../img/box_login_tl.gif" width="5" height="5"></td>
		<td><img src="../img/pixel.gif" width="1" height="5"></td>
		<td><img src="../img/box_login_tr.gif" width="5" height="5"></td>
	</tr>
	<tr>
		<td><img src="../img/pixel.gif" width="5" height="1"></td>
		<td width="790" align="center" style="padding: 2px;">

		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="font-size: 10px;">&nbsp;</td>
								
								
								
				<td style="font-size: 10px;">&nbsp;</td>
			</tr>
		</table>
			
		</td>
		<td><img src="../img/pixel.gif" width="5" height="1"></td>
	</tr>
	<tr>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="../img/box_login_bl.gif" width="5" height="5"></td>
		<td style="border-bottom: 1px solid #BBBBBB"><img src="../img/pixel.gif" width="1" height="5"></td>
		<td style="border-bottom: 1px solid #FFFFFF"><img src="../img/box_login_br.gif" width="5" height="5"></td>
	</tr>
</table>

<div style="padding: 0px 5px 0px 5px;">

<table align="center" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
	<tr>
	</tr>
</table>


<table width="100%" align="center" cellpadding="0" cellspacing="0" border="1" class="BoxedBorderTable">
			<tbody>
			<tr>
				
                				<td style="padding: 5px 0px 5px 0px;">
				
								
				<table width="100%" vlink="#e52222" alink="#e52222" cellpadding="0" cellspacing="0" border="0">
					<tbody><tr valign="top">
					
					
					<td width="33%" style="padding: 0px 10px 10px 10px; text-align: center; ">
					
					<a href="/">Index</a>




                    <p>* - COMING SOON/UNDER CONSTRUCTION
					</td>
					</tr>
				</tbody></table>

									
				</td>

									
				
                
				
			</tr>
			
		</tbody></table>
        <p>