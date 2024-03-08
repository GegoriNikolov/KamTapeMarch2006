<?php
require "needed/start_channel.php";
$profile = $conn->prepare("SELECT * FROM users WHERE users.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	redirect("index_down.php");
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
    $profile['videos'] = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND privacy = 1 AND converted = 1");
    $profile['videos']->execute([$profile["uid"]]);
    $profile['videos'] = $profile['videos']->rowCount();

    $profile['priv_videos'] = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND privacy = 2 AND converted = 1");
    $profile['priv_videos']->execute([$profile["uid"]]);
    $profile['priv_videos'] = $profile['priv_videos']->rowCount();

    $profile['favorites'] = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
    $profile['favorites']->execute([$profile["uid"]]);
    $profile['favorites'] = $profile['favorites']->rowCount();

    $profile['watched'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE uid = ?");
    $profile['watched']->execute([$profile['uid']]);
    $profile['watched'] = $profile['watched']->fetchColumn();

$profile_latest_video = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1
	GROUP BY videos.vid
	ORDER BY videos.uploaded DESC LIMIT 1"
);
$profile_latest_video->execute([$profile['uid']]);

if($profile_latest_video->rowCount() == 0) {
	$profile_latest_video = false;
} else {
	$profile_latest_video = $profile_latest_video->fetch(PDO::FETCH_ASSOC);
	
	$profile_latest_video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE vid = ?");
	$profile_latest_video['views']->execute([$profile_latest_video['vid']]);
	$profile_latest_video['views'] = $profile_latest_video['views']->fetchColumn();
	
	$profile_latest_video['comments'] = $conn->prepare("SELECT COUNT(cid) AS comments FROM comments WHERE vidon = ?");
	$profile_latest_video['comments']->execute([$profile_latest_video['vid']]);
	$profile_latest_video['comments'] = $profile_latest_video['comments']->fetchColumn();
    $videos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1
	ORDER BY videos.uploaded DESC"
);
if($profile_latest_video['privacy'] !== 1) {
    $profile_latest_video = false;
}
$videos->execute([$profile['uid']]);
}
$favorites = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE favorites.uid = ? AND videos.converted = 1
	ORDER BY favorites.fid DESC"
);
$favorites->execute([$profile['uid']]);

$friends = $conn->prepare(
	"SELECT * FROM relationships LEFT JOIN users ON users.uid = relationships.sender WHERE (respondent = ? OR sender = ?) AND accepted = 1 ORDER BY sent DESC"
);
$friends->execute([$profile['uid'], $profile['uid']]);

    $videolists = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1 AND videos.privacy = 1
	ORDER BY videos.uploaded DESC LIMIT 12"
);
$videolists->execute([$profile['uid']]);
}


?>
<? if($profile['termination'] == 1) { alert("This user has been terminated due to multiple violations of the Terms Of Service.", "error"); } else { ?>
<!--Begin Page Container Table-->
<div class="profileTitleLinks">
	<div>
	<table align="center" cellpadding="0" cellspacing="0" border="0" class="profileSubLinks">
		<tr>
			
			<td>
					<span class="profileSubLinks">		<strong>Channel</strong>
</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
					<span class="profileSubLinks">		<a href="/profile_videos?user=<?php echo htmlspecialchars($profile['username']) ?>">Videos</a>
 (<?php echo $profile['videos']; ?>)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
					<span class="profileSubLinks">		<a href="/profile_favorites?user=<?php echo htmlspecialchars($profile['username']) ?>">Favorites</a>
 (<?php echo $profile['favorites']; ?>)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
						<span class="profileSubLinks">		<a href="/profile_play_list?user=<?php echo htmlspecialchars($profile['username']) ?>">Playlists</a>
 (1)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
						<span class="profileSubLinks">		<a href="/profile_groups?user=<?php echo htmlspecialchars($profile['username']) ?>">Groups</a>
 (7)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
					<span class="profileSubLinks">		<a href="/profile_subscribers?user=<?php echo htmlspecialchars($profile['username']) ?>">Subscribers</a>
 (134)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
						<span class="profileSubLinks">		<a href="/profile_subscriptions?user=<?php echo htmlspecialchars($profile['username']) ?>">Subscriptions</a>
 (13)</span>
			</td>
	
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
						<span class="profileSubLinks">		<a href="/profile_comment_all?user=<?php echo htmlspecialchars($profile['username']) ?>">Comments</a>
 (42)</span>
			</td>
			<td style="padding: 0px 5px 0px 5px;"><span class="profileSubLinks">|</span></td>
			<td>
						<span class="profileSubLinks">		<a href="/bulletin_all?user=<?php echo htmlspecialchars($profile['username']) ?>">Bulletins</a>
 (190)</span>
			</td>
		</tr>
	</table>
</div>

</div>



<script type="text/javascript" src="/js/video_bar_yts1157352107.js"></script>
<link rel="stylesheet" href="/css/styles_yts1164775696.css" type="text/css">


 







<script language="javascript">
	
	function getFormVars(form) 
	{	var formVars = new Array();
		for (var i = 0; i < form.elements.length; i++)
		{
			var formElement = form.elements[i];
			formVars[formElement.name] = formElement.value;
		}
		return urlEncodeDict(formVars);
	}


	
	
	function blockUser(form) 
	{
        if (!confirm("Are you sure you want to block this user?"))
            return false;

		postFormByForm(form, true, execOnSuccess(function (xmlHttpRequest) { 
			response_str = xmlHttpRequest.responseText;
			if(response_str == "SUCCESS") {
				form.block_button.value = "User blocked"
			} else {
				alert ("An error occured while blocking the user.");
				form.block_button.value = "Block this user"
				form.block_button.disabled = false;
			}
		}));
		form.block_button.disabled = true
		form.block_button.value = "Please wait.."
		return true;
	}
	function unblockUser(form) 
	{
        if (!confirm("Are you sure you want to unblock this user?"))
            return false;

		postFormByForm(form, true, execOnSuccess(function (xmlHttpRequest) { 
			response_str = xmlHttpRequest.responseText;
			if(response_str == "SUCCESS") {
				form.unblock_button.value = "User unblocked"
			} else {
				alert ("An error occured while unblocking the user.");
				form.unblock_button.value = "Unblock this user"
				form.unblock_button.disabled = false;
			}
		}));

		form.unblock_button.disabled = true
		form.unblock_button.value = "Please wait.."
		return true;
	}
	
	function unblockUserLink(friend_id, url)
	{
        if (!confirm("Are you sure you want to unblock this user?"))
            return true;
		getUrlSync("/link_servlet?unblock_user=1&friend_id=" + friend_id);
	    window.location.href = url;
		return true;
	}
	function blockUserLink(friend_id, url)
	{
        if (!confirm("Are you sure you want to block this user?"))
            return true;
		getUrlSync("/link_servlet?block_user=1&friend_id=" + friend_id);
	    window.location.href = url;
		return true;
	}



				<?php if($profile['videos'] != 0) { ?>onLoadFunctionList.push(function() { imagesInit_profile_videos();} );
	
		function imagesInit_profile_videos() {
			imageBrowsers['profile_videos'] = new ImageBrowser(<?php if($profile['videos'] < 4) { ?><?php echo htmlspecialchars($profile['videos']); ?><? } else { ?>4<? } ?>, 1, "profile_videos");
				
				<?php foreach($videolists as $videolist) { ?>
				imageBrowsers['profile_videos'].addImage(new ytImage("/get_still.php?video_id=<?php echo $videolist['vid']; ?>", 
													  "/watch?v=<?php echo $videolist['vid']; ?>",
													  "<?php echo htmlspecialchars($videolist['title']); ?>", 
													  "/watch?v=<?php echo $videolist['vid']; ?>",
													  "<? echo timeAgo($videolist['uploaded']) ?>",
													  "",
													  "",
													  false) );
				<? } ?>
			imageBrowsers['profile_videos'].initDisplay();
			imageBrowsers['profile_videos'].showImages();
			images_loaded = true;
		}<? } ?>

				<?php if($profile['favorites'] != 0) { ?>onLoadFunctionList.push(function() { imagesInit_favorite_videos();} );
	
		function imagesInit_favorite_videos() {
			imageBrowsers['favorite_videos'] = new ImageBrowser(4, 1, "favorite_videos");
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjc-static6.sjc.youtube.com/vi/T1LpI6yN6Os/2.jpg", 
													  "/watch?v=T1LpI6yN6Os",
													  "BOOM HEADSHOT!!!", 
													  "/watch?v=T1LpI6yN6Os",
													  "5 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjl-static15.sjl.youtube.com/vi/Ifs1V5M2fPg/2.jpg", 
													  "/watch?v=Ifs1V5M2fPg",
													  "Command &amp; Conquer 3 Tiberium Wars Trailer", 
													  "/watch?v=Ifs1V5M2fPg",
													  "7 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjl-static3.sjl.youtube.com/vi/5dhTpX7SyI0/2.jpg", 
													  "/watch?v=5dhTpX7SyI0",
													  "Re: Moon video: plane crossing in field of view", 
													  "/watch?v=5dhTpX7SyI0",
													  "3 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjc-static7.sjc.youtube.com/vi/ztYRno3x-8I/2.jpg", 
													  "/watch?v=ztYRno3x-8I",
													  "Crazy Frog", 
													  "/watch?v=ztYRno3x-8I",
													  "1 year ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjc-static9.sjc.youtube.com/vi/UbRjcXsejzQ/2.jpg", 
													  "/watch?v=UbRjcXsejzQ",
													  "Command &amp; Conquer 3: Tiberium Wars", 
													  "/watch?v=UbRjcXsejzQ",
													  "4 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjl-static5.sjl.youtube.com/vi/FVsijmCFs50/2.jpg", 
													  "/watch?v=FVsijmCFs50",
													  "AYBABTU", 
													  "/watch?v=FVsijmCFs50",
													  "10 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjl-static13.sjl.youtube.com/vi/GXeceK-HdB0/2.jpg", 
													  "/watch?v=GXeceK-HdB0",
													  "Spam at Karkand - The Movie", 
													  "/watch?v=GXeceK-HdB0",
													  "4 months ago",
													  "",
													  "",
													  false) );
				
				imageBrowsers['favorite_videos'].addImage(new ytImage("http://sjl-static10.sjl.youtube.com/vi/EKy4BdOVsZI/2.jpg", 
													  "/watch?v=EKy4BdOVsZI",
													  "Tiberian sun Kane returns", 
													  "/watch?v=EKy4BdOVsZI",
													  "7 months ago",
													  "",
													  "",
													  false) );
			imageBrowsers['favorite_videos'].initDisplay();
			imageBrowsers['favorite_videos'].showImages();
			images_loaded = true;
		}<? } ?>

				onLoadFunctionList.push(function() { imagesInit_subscribers();} );
	
		function imagesInit_subscribers() {
			imageBrowsers['subscribers'] = new ImageBrowser(4, 1, "subscribers");
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg", 
													  "/profile?user=mydownyvalentines",
													  "mydownyvalentines", 
													  "/profile?user=mydownyvalentines",
													  "",
													  "",
													  "",
													  false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg", 
													  "/profile?user=chappie8",
													  "chappie8", 
													  "/profile?user=chappie8",
													  "",
													  "",
													  "",
													  false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg", 
													  "/profile?user=kuttukidoriburu",
													  "kuttukidoriburu", 
													  "/profile?user=kuttukidoriburu",
													  "",
													  "",
													  "",
													  false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg", 
													  "/profile?user=j4m3sb0ndfx",
													  "j4m3sb0ndfx", 
													  "/profile?user=j4m3sb0ndfx",
													  "",
													  "",
													  "",
													  false) );
				imageBrowsers['subscribers'].addImage(new ytImage("/img/no_videos_140.jpg", 
													  "/profile?user=carlgoodman25",
													  "carlgoodman25", 
													  "/profile?user=carlgoodman25",
													  "",
													  "",
													  "",
													  false) );
				imageBrowsers['subscribers'].addImage(new ytImage("http://sjc-static15.sjc.youtube.com/vi/Tda3TLGTEOI/2.jpg", 
													  "/profile?user=Sehnsucht234",
													  "Sehnsucht234", 
													  "/profile?user=Sehnsucht234",
													  "",
													  "",
													  "",
													  false) );
			imageBrowsers['subscribers'].initDisplay();
			imageBrowsers['subscribers'].showImages();
			images_loaded = true;
		}

	
	function share_profile()
	{
	  var fs = window.open( "/share?u=<?php echo htmlspecialchars($profile['username']) ?>",
			   "Share", "toolbar=no,width=546,height=485,status=no,resizable=yes,fullscreen=no,scrollbars=no");
	  fs.focus();
	}
</script>

			


<table border="0" cellpadding="0" cellspacing="0" width="875"><tr valign="top">
	

	<!--Begin Left Column-->		
	<td width="300">
		<!--Begin Profile Box-->
		<div class="headerBox">		<div class="headerTitleEdit">
			<div class="headerTitleRight">
			</div>
			<span>
					<?php echo htmlspecialchars($profile['username']) ?> Channel
			</span>
		</div>
</div>
		<div id="pBox" class="highlightBoxes">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr valign="top">
					<td>
					<? if($profile_latest_video) { ?>
						<img src="/get_still.php?video_id=<?php echo htmlspecialchars($profile_latest_video['vid']) ?>" id="profileImg" class="imageProperties"/>
					<? } else { ?>
						<img src="/img/no_videos_140.jpg" id="profileImg" class="imageProperties"/><? } ?>
					</td>
					<td align="left" style="padding-left: 5px;">
						<div>
							<a href="/subscription_center?add_user=<?php echo htmlspecialchars($profile['username']) ?>">
								<img src="/img/btn_subscribe_md_yellow_99x21.gif" border="0" align="absmiddle" alt="Subscribe to Channel">
							</a>
						</div>
						<div class="extraVertSpaceMini">
							<strong><?php echo htmlspecialchars($profile['username']) ?></strong>
						</div>											
						<!-- Begin If Comedian Account -->
						<!--End If Comedian Account -->
							<? if ($profile['birthday'] != '0000-00-00' && $profile['birthday'] != NULL) { ?><strong>Age: </strong> 
		<?php echo str_replace(' years ago', '', timeAgo($profile['birthday'])); ?>
	<br/><? } ?>

				
							<? if (!empty($profile['city'])) { ?><strong>City: </strong> 
		<?php echo htmlspecialchars($profile['city']) ?>
	<br/><? } ?>
						 
							<? if (!empty($profile['country'])) { ?><strong>Country: </strong> 
		<? echo htmlspecialchars(getCountryName($profile['country'])) ?>
	<br/><? } ?>

						
					</td>
				</tr>
			</table>	
	
			<div id="statsBox">
				<b>Channel Views:</b> 269
				<br />
				<b>Subscribers:</b>
				<a href="profile_subscribers?user=<?php echo htmlspecialchars($profile['username']) ?>" rel="nofollow">6</a>
			</div>
	
			<div class="sepBox">
						
			<span id="BeginvidDescchannel_desc">
	
	</span>
	



			</div>
			
			
			<!--Begin Honors Section-->
				<!--<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="20" valign="top"><img src="/img/icn_award_17x24.gif" border="0"></td>
						<td valign="top">
							<span class="smallText">
									<span id="BeginvidDeschonors">
				<a href="/members?t=a&p=5&s=mv&g=3">#86 - Most Viewed (All Time) - Directors</a><br/>

	</span>
	


							</span>
						</td>
					</tr>
				</table>-->
			<!--End Honors Section-->
	
			
				<div class="sepBoxReg">
					<div class="largeTitles">About Me</div>		
							<? if (!empty($profile['name'])) { ?><strong>Name: </strong> 
		<?php echo htmlspecialchars($profile['name']) ?> 
	<br/><? } ?> 
	
						<strong>Member Since: </strong> 
		<? echo timeAgo($profile['joined']) ?> 
	<br/> 

						<strong>Videos Watched: </strong> 
		<?php echo htmlspecialchars($profile['watched']) ?> 
	<br/> 

						<strong>Last Login: </strong> 
		<? echo timeAgo($profile['lastlogin']) ?> 
	<br/> 

							<? if (!empty($profile['website'])) { ?><strong>Website: </strong> 
		<a href='<?php echo htmlspecialchars($profile['website']) ?>' rel=nofollow><?php echo htmlspecialchars($profile['website']) ?></a>
	<br/><? } ?>
	

				</div>
				
				
				
					<span id="BeginvidDescaboutme">
	
	</span>
	
			<? if (!empty($profile['about']) || !empty($profile['hometown']) || !empty($profile['schools']) || !empty($profile['occupations']) || !empty($profile['companies']) || !empty($profile['hobbies']) || !empty($profile['fav_media']) || !empty($profile['music']) || !empty($profile['books'])) { ?><span id="RemainvidDescaboutme" style="display: none">					<? if (!empty($profile['about'])) { ?><?php echo nl2br(htmlspecialchars($profile['about'])); ?><? } ?>
						<? if (!empty($profile['hometown']) || !empty($profile['schools']) || !empty($profile['occupations']) || !empty($profile['companies'])) { ?><div class="sepBox">
							<span class="largeTitles">Where I've Been</span>
							<br />
							<div><? if (!empty($profile['hometown'])) { ?>	<strong>Hometown: </strong> 
		<?php echo htmlspecialchars($profile['hometown']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['schools'])) { ?>	<strong>Schools: </strong> 
		<?php echo htmlspecialchars($profile['schools']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['occupations'])) { ?>	<strong>Occupation: </strong> 
		<?php echo htmlspecialchars($profile['occupations']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['companies'])) { ?>	<strong>Companies: </strong> 
		<?php echo htmlspecialchars($profile['companies']) ?> 
	<br/> 
<? } ?></div>
						</div><? } ?>
						<div class="sepBox">
							<? if (!empty($profile['hobbies']) || !empty($profile['fav_media']) || !empty($profile['music']) || !empty($profile['books'])) { ?><span class="largeTitles">What I Like To Do</span>
							<br />
							<div><? if (!empty($profile['hobbies'])) { ?>	<strong>Interests & Hobbies: </strong> 
		<?php echo htmlspecialchars($profile['hobbies']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['fav_media'])) { ?>	<strong>Favorite Movies & Shows: </strong> 
		<?php echo htmlspecialchars($profile['fav_media']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['music'])) { ?>	<strong>Favorite Music: </strong> 
		<?php echo htmlspecialchars($profile['music']) ?> 
	<br/> 
<? } ?></div>
							<div><? if (!empty($profile['books'])) { ?>	<strong>Favorite Books: </strong> 
		<?php echo htmlspecialchars($profile['books']) ?> 
	<br/> 
<? } ?></div>
						</div><? } ?>
</span>
			<span id="MorevidDescaboutme" class="smallText">(<a href="#" class="eLink" onclick="showInline('RemainvidDescaboutme'); hideInline('MorevidDescaboutme'); hideInline('BeginvidDescaboutme'); showInline('LessvidDescaboutme'); return false;">See full profile</a>)</span>
			<span id="LessvidDescaboutme" style="display: none" class="smallText">(<a href="#" class="eLink" onclick="hideInline('RemainvidDescaboutme'); hideInline('LessvidDescaboutme'); showInline('BeginvidDescaboutme'); showInline('MorevidDescaboutme'); return false;">Hide full profile</a>)</span><? } ?>
	


				<!--Begin About Me Description-->
			
			
			<!--Begin Album Section-->
		</div> <!-- end pBox-->
	
	
			
		<!--Begin Connect With Box If Has Image-->
			<div class="headerBox">			<div class="headerTitle">Connect with <?php echo htmlspecialchars($profile['username']) ?></div>			
</div>
			<table id="connectTable" class="basicBoxes" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">
						<div class="emptyConnectImg">&nbsp;</div>
					</td>
					<td colspan="2" align="left" width="245" valign="top">
						<table class="actionsTable">
							<tr class="actionsTable">
								<td class="actionsTable" width="110">
									<div class="smallText">
										<a href="/outbox?to_user=<?php echo htmlspecialchars($profile['username']) ?>">
											<img src="/img/blue_send_message.gif" class="iconProperties" align="absmiddle" border="0" alt="Send Message">
										</a>
										<a href="/outbox?to_user=<?php echo htmlspecialchars($profile['username']) ?>"">Send Message</a>
									</div>
								</td>
								<td class="actionsTable">
									<div class="smallText">
										<a href="/profile_comment_post?user=<?php echo htmlspecialchars($profile['username']) ?>"">
											<img src="/img/blue_add_comment.gif" class="iconProperties" align="absmiddle" border="0" alt="Add Comment">
										</a>
										<a href="/profile_comment_post?user=<?php echo htmlspecialchars($profile['username']) ?>">Add&nbsp;Comment</a>
									</div>
								</td>
							</tr>
							<tr class="actionsTable">
								<td class="actionsTable" width="110">
									<div class="smallText">
										<a href="javascript:share_profile()">
											<img src="/img/blue_fwd_member.gif" class="iconProperties" align="absmiddle" border="0" alt="Share Channel">
										</a>
										<a href="javascript:share_profile()">Share Channel</a>
									</div>
								</td>
								<td class="actionsTable">
									<div class="smallText">
										
										
									</div>
								</td>
							</tr>
							<tr class="actionsTable">
								<td class="actionsTable" width="110">
									<div class="smallText">
										<a href="/my_friends_invite_user?friend_id=NaWcvI2Agwk">
											<img src="/img/blue_add_friends.gif" class="iconProperties" align="absmiddle" border="0" alt="Add as Friend">
										</a>
										<a href="/my_friends_invite_user?friend_id=NaWcvI2Agwk">Add as Friend</a>
									</div>
								</td>
								<td class="actionsTable">
									<div class="smallText">
										&nbsp;
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>												
			</table>
			<div class="linkSection">
					<div class="extraVertSpaceSm"><a href="/user/<?php echo htmlspecialchars($profile['username']) ?>">http://www.youtube.com/user/<?php echo htmlspecialchars($profile['username']) ?></a></div>
			</div>	
		<!--End Connect With Box If Has No Image-->


	

	
	
		
	
	
		
	
	
		<!--Begin Bulletin Board Box-->
		
		<!--End Bulletin board Box-->
	


		<!--Begin Comments Box-->
		
			<div class="headerBox">			<div class="headerTitle">
				<div class="headerTitleRight">
					(<a href="/profile_comment_all?user=<?php echo htmlspecialchars($profile['username']) ?>" class="headers">See All Comments</a>)
				</div>
				<span>Comments (1)</span>
			</div>		
</div>
			<div id="commentsBox" class="basicBoxes">
				<table border="0" cellpadding="0" cellspacing="0" width="100%"><?php if ($usercomments != NULL) { ?>
								
		<tr class="commentsTableFull">
		<td valign="top" width="90" align="left">
			<a href="/user/TX200000000"><img src="http://sjl-static12.sjl.youtube.com/vi/R8Z7cYKWVQ4/2.jpg" width="90" class="imgBrdr" /></a>
		</td>
		<td valign="top" align="left">
			<div class="smallText" style="font-weight: bold; padding-bottom: 12px;"><a href="/user/TX200000000">TX200000000</a> <span class="labels">| November 08, 2006</span></div>
                LoL<br/><br/>Test O_o<br/><br/>Hi Mr. Calhoun
		</td>
		</tr>
		
<? } else { ?>

						<div align="center" class="extraVertSpaceMini">There are no comments for this user.</div><? } ?>
						
					<tr>
						<td colspan="2">
							<div style="padding: 10px 0px; text-align: center;">
								<span class="smallText"><a href="/profile_comment_all?user=<?php echo htmlspecialchars($profile['username']) ?>">See All Comments</a> | 
								<a href="/profile_comment_post?user=<?php echo htmlspecialchars($profile['username']) ?>" rel="nofollow">Add Comment</a></span>
							</div>
						</td>
					</tr>
				</table>
			</div>
		<!--End Comments Box-->
	
	</td>
	<!--End Left Column-->

		
		
		
	<!--Begin Right Column-->
	<td style="padding-left: 15px;">	
	
								
											
						
		<!--Begin If No Videos, Favorites, Subscribers Box For External View-->
		<!--End If No Videos, Favorites, Subscribers Box For External View-->


	
		<!--Begin If No Videos Empty Set Box-->
		<!--End If No Videos Empty Set Box-->

													
						
		<!--Begin Videos Scroller Box--><?php if($profile['videos'] != 0) { ?>
				<div class="headerBox">				<div class="headerTitle">
					<div class="headerTitleRight">
					<!-- <img src="/img/arrow_sq_sm.gif" align="absmiddle" /><a href="playall" class="headers">Play All Videos</a> | -->
					(<a href="/profile_videos?user=<?php echo htmlspecialchars($profile['username']) ?>" class="headers" />See All Videos</a> |
					<a href="/subscription_center?add_user=<?php echo htmlspecialchars($profile['username']) ?>" class="headers" />Subscribe To Videos</a>)
					</div>
					<span>Videos (<?php echo $profile['videos']; ?>)</span>
				</div>	
</div>
				<div class="basicBoxes scrollersBox">
					 <center>
					 		<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="/img/l_slider_arrow.gif" onclick="fadeOldImage('profile_videos','4');shiftLeft('profile_videos')" border=0></td>
				<td>
					<table width="500" height="121" style="background-color: #ffffff; " cellpadding="0" cellspacing="0">
						<tr>
							<td   style="border-bottom:none;">
							<div class="videobarthumbnail_block" id="div_profile_videos_0">
								<center>
									<div><a id="href_profile_videos_0" href=".."><img class="videobarthumbnail_white" id="img_profile_videos_0" src="/img/pixel.gif" onload="opacity('img_profile_videos_0', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_profile_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_profile_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_profile_videos_0_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<?php if($profile['videos'] > 1) { ?><div class="videobarthumbnail_block" id="div_profile_videos_1">
								<center>
									<div><a id="href_profile_videos_1" href=".."><img class="videobarthumbnail_white" id="img_profile_videos_1" src="/img/pixel.gif" onload="opacity('img_profile_videos_1', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_profile_videos_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_profile_videos_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_profile_videos_1_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div><? } ?>
							<?php if($profile['videos'] > 2) { ?><div class="videobarthumbnail_block" id="div_profile_videos_2">
								<center>
									<div><a id="href_profile_videos_2" href=".."><img class="videobarthumbnail_white" id="img_profile_videos_2" src="/img/pixel.gif" onload="opacity('img_profile_videos_2', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_profile_videos_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_profile_videos_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_profile_videos_2_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div><? } ?>
							<?php if($profile['videos'] > 3) { ?><div class="videobarthumbnail_block" id="div_profile_videos_3">
								<center>
									<div><a id="href_profile_videos_3" href=".."><img class="videobarthumbnail_white" id="img_profile_videos_3" src="/img/pixel.gif" onload="opacity('img_profile_videos_3', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_profile_videos_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_profile_videos_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_profile_videos_3_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div><? } ?>
							</td>
						</tr>
					</table>
				</td>
				</td>
				<td><img src="/img/r_slider_arrow.gif" onclick="fadeOldImage('profile_videos','<?php if($profile['videos'] < 4) { ?><?php echo htmlspecialchars($profile['videos']) ?><? } else { ?>4<? } ?>');shiftRight('profile_videos');" border=0></td>
			</tr>
		</table>
		</div>


					</center>
				</div><? } ?>
		<!--End Videos Scroller Box-->
						
						
						
		<!--Begin If No Video Log Empty Set Box-->
		<!--End If No Video Log Empty Set Box-->
						
						
		<!-- Begin Video Log Box-->
		<!--End Video Log Box-->
						
						
		<!--Begin If No Favorites Empty Set Box-->
		<!--End If No Favorites Empty Set Box-->
	
	
								
		<!--Begin Favorites Box--><?php if($profile['favorites'] != 0) { ?>
				<div class="headerBox">				<div class="headerTitle">
					<div class="headerTitleRight">
					(<a href="/profile_favorites?user=<?php echo htmlspecialchars($profile['username']) ?>" class="headers" />See All Favorites</a> |
							 <a href="/subscription_center?add_user_favorites=<?php echo htmlspecialchars($profile['username']) ?>" class="headers" />Subscribe To Favorites</a>)
					</div>
					<span>Favorites (<?php echo $profile['favorites']; ?>)</span>
				</div>	
</div>
				<div class="basicBoxes scrollersBox">
						<center>
									<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="/img/l_slider_arrow.gif" onclick="fadeOldImage('favorite_videos','4');shiftLeft('favorite_videos')" border=0></td>
				<td>
					<table width="500" height="121" style="background-color: #ffffff; " cellpadding="0" cellspacing="0">
						<tr>
							<td   style="border-bottom:none;">
							<div class="videobarthumbnail_block" id="div_favorite_videos_0">
								<center>
									<div><a id="href_favorite_videos_0" href=".."><img class="videobarthumbnail_white" id="img_favorite_videos_0" src="/img/pixel.gif" onload="opacity('img_favorite_videos_0', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_favorite_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_favorite_videos_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_0_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_1">
								<center>
									<div><a id="href_favorite_videos_1" href=".."><img class="videobarthumbnail_white" id="img_favorite_videos_1" src="/img/pixel.gif" onload="opacity('img_favorite_videos_1', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_favorite_videos_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_favorite_videos_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_1_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_2">
								<center>
									<div><a id="href_favorite_videos_2" href=".."><img class="videobarthumbnail_white" id="img_favorite_videos_2" src="/img/pixel.gif" onload="opacity('img_favorite_videos_2', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_favorite_videos_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_favorite_videos_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_2_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_3">
								<center>
									<div><a id="href_favorite_videos_3" href=".."><img class="videobarthumbnail_white" id="img_favorite_videos_3" src="/img/pixel.gif" onload="opacity('img_favorite_videos_3', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_favorite_videos_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_favorite_videos_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_favorite_videos_3_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							</td>
						</tr>
					</table>
				</td>
				</td>
				<td><img src="/img/r_slider_arrow.gif" onclick="fadeOldImage('favorite_videos','4');shiftRight('favorite_videos');" border=0></td>
			</tr>
		</table>
		</div>

                  
						</center>
				</div><? } ?>
		<!--End Favorites Box-->
						
						
		<!--Begin If No Subscribers Empty Set Box-->
		<!--End If No Subscribers Empty Set Box-->
	
			
		<!--Begin Subscribers Box-->
				<div class="headerBox">				<div class="headerTitle">
					<div class="headerTitleRight">
					(<a href="/profile_subscribers?user=<?php echo htmlspecialchars($profile['username']) ?>" class="headers" />See All Subscribers</a>)
					</div>
					<span>Subscribers (6)</span>
				</div>	
</div>
				<div class="basicBoxes scrollersBox">
						<center>
								<div style="padding-left: 1px;">					
		<table width="21" height="121" cellpadding="0" cellspacing="0">
			<tr>
				<td><img src="/img/l_slider_arrow.gif" onclick="fadeOldImage('subscribers','4');shiftLeft('subscribers')" border=0></td>
				<td>
					<table width="500" height="121" style="background-color: #ffffff; " cellpadding="0" cellspacing="0">
						<tr>
							<td   style="border-bottom:none;">
							<div class="videobarthumbnail_block" id="div_subscribers_0">
								<center>
									<div><a id="href_subscribers_0" href=".."><img class="videobarthumbnail_white" id="img_subscribers_0" src="/img/pixel.gif" onload="opacity('img_subscribers_0', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_subscribers_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_subscribers_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_0_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_1">
								<center>
									<div><a id="href_subscribers_1" href=".."><img class="videobarthumbnail_white" id="img_subscribers_1" src="/img/pixel.gif" onload="opacity('img_subscribers_1', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_subscribers_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_subscribers_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_1_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_2">
								<center>
									<div><a id="href_subscribers_2" href=".."><img class="videobarthumbnail_white" id="img_subscribers_2" src="/img/pixel.gif" onload="opacity('img_subscribers_2', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_subscribers_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_subscribers_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_2_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_3">
								<center>
									<div><a id="href_subscribers_3" href=".."><img class="videobarthumbnail_white" id="img_subscribers_3" src="/img/pixel.gif" onload="opacity('img_subscribers_3', 80, 100, 800);" width="80" height="60"></a></div>
									<div id = "title1_subscribers_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
									<div id = "title2_subscribers_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							<div class="videobarthumbnail_block" id="div_subscribers_3_alternate" style="display: none">
								<center>
									<div><img src="/img/pixel.gif" width="80" height="60"></div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
									<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
								</center>
							</div>
							</td>
						</tr>
					</table>
				</td>
				</td>
				<td><img src="/img/r_slider_arrow.gif" onclick="fadeOldImage('subscribers','4');shiftRight('subscribers');" border=0></td>
			</tr>
		</table>
		</div>


						</center>
				</div>
		<!--End Subscribers Box-->
	</td>
	<!--End Right Column-->
</tr></table>
<!--End Page Container Table-->
<? } ?>
<?php
require "needed/end_channel.php";
?>