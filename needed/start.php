<?php 
require "needed/scripts.php";
$inbox = $conn->prepare("SELECT * FROM messages WHERE receiver = ? AND isRead = 0 ORDER BY created DESC");
$inbox->execute([$session['uid']]);
$inbox = $inbox->rowCount();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?php if ($current_page !== 'watch')  { ?>
<title>KamTape - Televise Yourself.</title>
<? } ?>
<link rel="stylesheet" href="/css/styles_yts1164775696.css" type="text/css">
<link rel="stylesheet" href="/css/base_yts1164787633.css" type="text/css">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">	
<meta name="keywords" content="video,sharing,camera phone,video phone">
<link rel="alternate" title="YouTube - [RSS]" href="/rssls">
<script type="text/javascript" src="/js/ui_yts1164777409.js"></script>
<script type="text/javascript" src="/js/AJAX_yts1161839869.js"></script>
<script type="text/javascript" src="/js/watch_queue_yts1161839869.js"></script>
<script language="javascript" type="text/javascript">
	onLoadFunctionList = new Array();
	function performOnLoadFunctions()
	{
		for (var i in onLoadFunctionList)
		{
			onLoadFunctionList[i]();
		}
	}
</script>
<script language="javascript" type="text/javascript"> function _hbLink (a,b) { return false; } </script>
<? if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') { ?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2537513323123758"
     crossorigin="anonymous"></script>
     <? } ?>
</head>


<body onLoad="performOnLoadFunctions();">

<div id="baseDiv">	
		<div id="logoTagDiv">
		<a href="/" name="&lid=Logo&lpos=GlobalNav"><img src="/img/logo_tagline_sm.gif" alt="YouTube" width="254" height="48" border="0"></a>
	</div>

	<div id="utilDiv" style="height:13px;">
	
		<?php if(isset($session)) { ?>
		<div style="float:right; margin-top:5px;">                        
			<span class="utilDelim">|</span>                        
			<a href="/recently_watched" onclick="_hbLink('ViewingHistory','UtilityLinks');">History</a>                        
			<span class="utilDelim">|</span>                        
			<a href="/watch_queue?all" onclick="_hbLink('QuickList','UtilityLinks');">QuickList</a>
		(<span id="quicklist_numb"><a href="/watch_queue?all"><script type="text/javascript">var quicklist_count=0;document.write(quicklist_count);</script></a></span>)                        
			<span class="utilDelim">|</span>                        
			<a href="/t/help_center">Help</a>                        
			<span class="utilDelim">|</span>                        

			<a href="#" onClick="document.logoutForm.submit()">Log Out</a>                        
		</div>                
						  
		<div class="myAccountContainer" style="margin: 5px 5px 0px 3px;">
			<a href="/my_account" onclick="_hbLink('MyAccount','UtilityLinks');" onmouseover="showDropdown();">My Account</a> <a href="#" onclick="showDropdown();return false;" onmouseover="document.arrowImg.src='/img/icon_menarrwdrpdwn_mouseover_14x14.gif'" onmouseout="document.arrowImg.src='/img/icon_menarrwdrpdwn_regular_14x14.gif'"><img name="arrowImg" src="/img/icon_menarrwdrpdwn_regular_14x14.gif" align="texttop" border="0"></a>
		<!--Start of My Account Hidden Menu -->			

					<div id="myAccountDropdown" class="myAccountMenu" onmouseover="showDropdown();" onmouseout="hideDropwdown();" style="display: none;">
						<div id="menuContainer" class="menuBox">
<!--
							<div class="menuBoxItem" id="MyAccountMyAccount" onMouseover="showDropdown();changeBGcolor(this,1);" onMouseout="changeBGcolor(this,0);" onClick="window.location='/my_account">
                                                                <span class="smallText" onMouseover="showDropdown();"><a href="/my_account" class="dropdownLinks">My Account</a></span>
                                                        </div>
-->
							<div class="menuBoxItem" id="MyAccountMyVideo" onmouseover="showDropdown();changeBGcolor(this,1);" onmouseout="changeBGcolor(this,0);" onclick="window.location='/my_videos'">
								<span class="smallText" onmouseover="showDropdown();"><a href="/my_videos" class="dropdownLinks">My Videos</a></span>
							</div>
							<div class="menuBoxItem" id="MyAccountMyFavorites" onmouseover="showDropdown();changeBGcolor(this,1);" onmouseout="changeBGcolor(this,0);" onclick="window.location='/my_favorites'">
								<span class="smallText" onmouseover="showDropdown();"><a href="/my_favorites" class="dropdownLinks">My Favorites</a></span>
							</div>
							<div class="menuBoxItem" id="MyAccountMyPlaylists" onmouseover="showDropdown();changeBGcolor(this,1);" onmouseout="changeBGcolor(this,0);" onclick="window.location='/my_playlists'">
								<span class="smallText" onmouseover="showDropdown();"><a href="/my_playlists" class="dropdownLinks">My Playlists</a></span>
							</div>
							<div class="menuBoxItem" id="MyAccountSubscription" onmouseover="showDropdown();changeBGcolor(this,1);" onmouseout="changeBGcolor(this,0);" onclick="window.location='/subscription_center'">
								<span class="smallText" onmouseover="showDropdown();"><a href="/subscription_center" class="dropdownLinks">My Subscriptions</a></span>
							</div>
						</div>
					</div>
<script>
toggleVisibility('myAccountDropdown',0);
</script>
		<!--End of My Account Hidden Menu -->				

		</div>

		<div id="utilNavLeftContainer">
			<b>Hello, <a href="profile?user=<?php echo htmlspecialchars($session['username']) ?>" onclick="_hbLink('ChannelProfile','UtilityLinks');"><?php echo htmlspecialchars($session['username']) ?></a></b> &nbsp;
				<a href="/my_messages"><img src="/img/icn_<? if($inbox > 0) { echo 'new'; } else { echo 'no'; } ?>mail_21x17.gif" valign="bottom" border="0" id="iconMail"></a> (<a class="headerLink" href="/my_messages"><?php echo htmlspecialchars($inbox) ?></a>)
			<span class="utilDelim">|</span>
		    <? if ($session['staff'] == 1) {?>
			<a href="/admin/" class="bold" style="color: #006f09;">ManagerTape</a>
			<span class="utilDelim">|</span><? } ?>
		</div>	        

	                    <?php } else if(!isset($session)){ ?>
						<b><a href="/signup" onclick="_hbLink('SignUp','UtilityLinks');">Sign Up</a></b>
                        <span class="utilDelim">|</span>
                        <a href="/my_account" >My Account</a>
                        <span class="utilDelim">|</span>
                        <a href="/recently_watched" onclick="_hbLink('ViewingHistory','UtilityLinks');">History</a>
                        <span class="utilDelim">|</span>
                        <a href="/watch_queue?all" onclick="_hbLink('QuickList','UtilityLinks');">QuickList</a>
			(<span id="quicklist_numb"><a href="/watch_queue?all"><script type="text/javascript">var quicklist_count=0;document.write(quicklist_count);</script></a></span>)                        
                        <span class="utilDelim">|</span>
                        <a href="/t/help_center">Help</a>
                        <span class="utilDelim">|</span>

                        <a href="/login?next=<?php echo $_SERVER['REQUEST_URI'] ?>" onclick="_hbLink('LogIn','UtilityLinks');">Log In</a>
						<?php } ?>
        </div>
        <form name="logoutForm" method="post" action="/index">
                <input type="hidden" name="action_logout" value="1">
        </form>

	
	<div id="searchDiv">
		<form name="searchForm" id="searchForm" method="get" action="/results">
		<span class="smallLabel">Search for&nbsp;</span>
		<input tabindex="10000" type="text" name="search_query" maxlength="128" class="searchField" value="">
		&nbsp;
		<input type="submit" name="search" value="Search">
	</form>

	</div>
		
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr><td>
		<div id="gNavDiv">
				
				<div class="ltab">
				<div class="tabContent<?php if ($current_page == 'index' 
				|| $current_page == 'login' 
				|| $current_page == 'signup' 
				|| $current_page == 'video_response_view_all' 
				|| $current_page == 'recently_watched' 
				|| $current_page == 'email_confirm' 
				|| $current_page == 'watch_queue' 
				|| $current_page == 'signup_invite' 
				|| $current_page == 'forgot' 
				|| $current_page == 'results' 
				|| $current_page == 'contact' 
				|| $current_page == 'view_play_list' 
				|| $current_page == 'signup_login' 
				|| $current_page == 'dev' 
				|| $current_page == 'dev_intro' 
				|| $current_page == 'dev_rest' 
				|| $current_page == 'dev_xmlrpc' 
				|| $current_page == 'dev_error_codes'
				|| $current_page == 'dev_api_ref'
				|| $current_page == 'dev_docs') { echo ' selected'; }?>">
				<a href="/index" name="&lid=Home&lpos=GlobalNav">Home</a>
				</div>
				</div>
				
				<div class="tab">
				<div class="tabContent<?php if ($current_page == 'browse' || $current_page == 'watch' || $current_page == 'comment_servlet') { echo ' selected'; }?>">
				<a href="/browse?s=mp" name="&lid=Videos&lpos=GlobalNav">Videos</a>
				</div>
				</div>
				
				<div class="tab">
				<div class="tabContent<?php if ($current_page == 'members') { echo ' selected'; }?>">
				<a href="/members" name="&lid=Channels&lpos=GlobalNav">Channels</a>
				</div>
				</div>
				
				<div class="tab">
				<div class="tabContent<?php if ($current_page == 'groups_main') { echo ' selected'; }?>">
				<a href="/groups_main" name="&lid=Groups&lpos=GlobalNav">Groups</a>
				</div>
				</div>
				
				<div class="tab">
				<div class="tabContent<?php if ($current_page == 'channels' || $current_page == 'categories' || $current_page == 'categories_portal') { echo ' selected'; }?>">
				<a href="/categories" name="&lid=Categories&lpos=GlobalNav">Categories</a>
				</div>
				</div>
				
				<div class="rtab">
				<div class="tabContent<?php if ($current_page == 'my_videos_upload' || $current_page == 'my_videos_upload_2' || $current_page == 'my_videos_upload_complete') { echo ' selected'; }?>">
				<a href="/my_videos_upload" name="&lid=Upload&lpos=GlobalNav">Upload</a>
				</div>
				</div>
		</div>
	</td></tr>
	<tr><td>
	<?php if ($current_page == 'index' 
	|| $current_page == 'my_videos' 
	|| $current_page == 'my_favorites' 
	|| $current_page == 'my_messages' 
	|| $current_page == 'outbox' 
	|| $current_page == 'my_profile'  
	|| $current_page == 'my_friends' 
	|| $current_page == 'login' 
	|| $current_page == 'signup' 
	|| $current_page == 'video_response_view_all' 
	|| $current_page == 'recently_watched' 
	|| $current_page == 'email_confirm' 
	|| $current_page == 'watch_queue' 
	|| $current_page == 'signup_invite' 
	|| $current_page == 'forgot' 
	|| $current_page == 'results' 
	|| $current_page == 'contact' 
	|| $current_page == 'view_play_list' 
	|| $current_page == 'signup_login' 
	|| $current_page == 'dev'
	|| $current_page == 'dev_intro'
	|| $current_page == 'dev_rest'
	|| $current_page == 'dev_xmlrpc'
	|| $current_page == 'dev_error_codes'
	|| $current_page == 'dev_api_ref'
	|| $current_page == 'dev_docs') { ?>
			<div id="gSubNavDiv">
				&nbsp;
						<a href="/my_account" onclick="_hbLink('MyAccount','SubNav');">My Account</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/my_videos" onclick="_hbLink('MyVideos','SubNav');">My Videos</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/my_favorites" onclick="_hbLink('MyFavorites','SubNav');">My Favorites</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/my_friends?sort=n" onclick="_hbLink('MyFriends','SubNav');">My Friends</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/my_messages" onclick="_hbLink('MyInbox','SubNav');">My Inbox</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/subscription_center" onclick="_hbLink('MySubscriptions','SubNav');">My Subscriptions</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/groups_my" onclick="_hbLink('MyGroups','SubNav');">My Groups</a>
							<span style="padding: 0px 8px;">|</span>
						<a href="/my_profile" onclick="_hbLink('MyChannel','SubNav');">My Channel</a>
				&nbsp;
			</div> <!-- end gSubNavDiv -->
			<? } else { ?>
			<div id="gNavBottom">
				&nbsp;
			</div><? } ?>
	</td></tr>
	</table>
<? if(!empty(invokethConfig("notice"))) { alert(invokethConfig("notice")); } 
if (isset($_COOKIE['hates__dwntime']) && invokethConfig("maintenance") == 1){
    alert("The website is currently in maintenance. Be cool -- some things might break.");
    }
    if (isset($_GET['session'])) {
	require_once "needed/phpickle/phpickle.php";
	$base64urltobase64 = strtr($_GET['session'], '-_', '+/');
	$string = phpickle::loads(base64_decode($base64urltobase64));
    if (implode(" ",$string['messages']) != NULL) {
	$type = "success";
	$message = $string['messages']['0'];
    } elseif (implode(" ",$string['errors']) != NULL) {
	$type = "error";
	$message = $string['errors']['0'];
    }
    alert(htmlspecialchars($message), htmlspecialchars($type));
    }

?>

