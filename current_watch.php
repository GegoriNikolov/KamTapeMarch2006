<?php 
require "needed/start.php";
echo '<script language="javascript" type="text/javascript" >
		function dropdown_jumpto(x)
		{
			if (document.share_dropdown.jumpmenu.value != "null")
			{
				document.location.href = x
			}
		}
		</script>';
if(isset($_SERVER['HTTP_REFERER'])) {
    $vidReferer = $_SERVER['HTTP_REFERER'];
} else {
     $vidReferer = "https://kamtape.com";
}
if(strpos($_SERVER['HTTP_REFERER'], "kamtape.com") !== false){
  $vidReferer = "https://kamtape.com";  
}
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$_GET['v']]);

if($video->rowCount() == 0) {
	header("Location: index.php");
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);

if ($uploader['uid'] == NULL) {
    redirect("/index.php");
}
if($video['converted'] == 0) {
	header("Location: index.php");
}
echo "<title>KamTape - ".htmlspecialchars($video['title'])."</title>";

$search = str_replace(" ", "|", $video['tags']);
								$results = $conn->prepare("SELECT tags FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE videos.tags REGEXP ? AND videos.converted = 1 ORDER BY videos.uploaded DESC"); // Regex!
								$results->execute([$search]);
                                $views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE vid = ?");
$views->execute([$video['vid']]);
$video['views'] = $views->fetchColumn();
$notOrganic = false;

// Check for organic views (better spam prevention)
$organ_views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE vid = ? AND viewed > DATE_SUB(NOW(), INTERVAL 1 DAY)");
$organ_views->execute([$video['vid']]);
$organc = $organ_views->fetchColumn();

if ($organc > 300) {
    $notOrganic = true;
}

// Check for organic views (better spam prevention)
$organ_views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE vid = ? AND viewed > DATE_SUB(NOW(), INTERVAL 1 MINUTE)");
$organ_views->execute([$video['vid']]);
$organc = $organ_views->fetchColumn();

if ($organc > 15) {
    $notOrganic = true;
}

if ($notOrganic = true) {
$already_viewed = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ? AND viewed > DATE_SUB(NOW(), INTERVAL 1 HOUR)");    
$already_viewed->execute([$video['vid']]);
} else {
$already_viewed = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ? AND sid = ? AND viewed > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
$already_viewed->execute([$video['vid'], session_id()]);
}

if($already_viewed->fetchColumn() == 0) {
    if($_SESSION['uid'] != NULL) { 
	$add_view = $conn->prepare("INSERT INTO views (view_id, referer, vid, sid, uid) VALUES (?, ?, ?, ?, ?)");
	$add_view->execute([generateId(34), $vidReferer, $video['vid'], session_id(), $session['uid']]);
    } else {
    $add_view = $conn->prepare("INSERT INTO views (view_id, referer, vid, sid, uid) VALUES (?, ?, ?, ?, NULL)");
	$add_view->execute([generateId(34), $vidReferer, $video['vid'], session_id()]);    
    }
}
$maker_videos = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND converted = 1");
$maker_videos->execute([$video["uid"]]);
					
$maker_favorites = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
$maker_favorites->execute([$video["uid"]]);

$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN users ON users.uid = comments.uid WHERE vidon = ? ORDER BY post_date DESC");
$comments->execute([$video['vid']]);
$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
				$video['comments']->execute([$video['vid']]);
				$video['comments'] = $video['comments']->fetchColumn();

if($_SESSION['uid'] != NULL) { 
    // Logged in stuff
            $favorites_of_you = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE favorites.uid = ? AND videos.converted = 1 AND videos.privacy = 1
	ORDER BY favorites.fid DESC"
);
$favorites_of_you->execute([$session['uid']]);

$videos_of_you = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1 AND videos.privacy = 1
	ORDER BY videos.uploaded DESC"
);
$videos_of_you->execute([$session['uid']]);
// End logged in stuff
}
if (isset($_POST['c'])) {
    if ($uploader['uid'] == $session['uid'] || $comment['uid'] == $session['uid'] || $session['staff'] == 1 && $comment['uid'] != NULL) {
    // fuck you
    $remove_video = $conn->prepare("DELETE FROM comments WHERE cid = ?");
    $remove_video->execute([
        $_POST['c']
    ]);
    alert("You have successfully removed this comment!");
    }
}
?>
<meta property="og:title" content="<?php echo htmlspecialchars($video['title']); ?> by <?php echo htmlspecialchars($uploader['username']); ?> on KamTape" />
<meta property="og:description" content="<?php echo mb_strimwidth(htmlspecialchars($video['description']) , 0, 500, "..."); ?>" />
<meta property="og:image" content="http://kamtape.com/get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" />
<meta property="og:video" content="http://kamtape.com/get_video.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>&format=webm" />
<meta property="og:type" content="website">

<link rel="stylesheet" href="/viewfinder/player.css">
<iframe id="invisible" name="invisible" src="" scrolling="yes" width="0" height="0" frameborder="0" marginheight="0" marginwidth="0"></iframe>   

<script>

function CheckLogin()
{
	
		<?php if($_SESSION['uid'] == NULL) { ?>
		alert("You must be logged in to to perform this action!");
		return false;
	<?php } ?>
		
	return true;
}

function FavoritesHandler()
{
	if (CheckLogin() == false)
		return false;

	alert("Video has been added to Favorites!");
	return true;
}

function CommentHandler()
{
	if (CheckLogin() == false)
		return false;

	var comment = document.comment_form.comment;
	var comment_button = document.comment_form.comment_button;

	if (comment.value.length == 0 || comment.value == null)
	{
		alert("You must enter a comment!");
		comment.focus();
		return false;
	}

	if (comment.value.length > 500)
	{
		alert("Your comment must be shorter than 500 characters!");
		comment.focus();
		return false;
	}

	comment_button.disabled='true';
	comment_button.value='Thanks for your comment!';

	return true;
}
</script>
<script src="/swfobject.js"></script>
<div align="center" style="padding-bottom: 10px;">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2537513323123758"
     crossorigin="anonymous"></script>
<!-- Watch Page -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2537513323123758"
     data-ad-slot="3705019363"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
</div>
<table width="790" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="510" style="padding-right: 15px;">

		<div class="tableSubTitle"><?php echo htmlspecialchars($video['title']); ?></div>
		
		<div style="padding: 0px 0px 10px 0px;">
		<table width="495" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="485"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<form name="share_dropdown">
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td align="center">
				<table align="center" cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding-right: 5px; font-weight: bold;">Share Video With:</td>
						<td>
				
				<select name="jumpmenu" onChange="dropdown_jumpto(document.share_dropdown.jumpmenu.options[document.share_dropdown.jumpmenu.options.selectedIndex].value)">
					<option value="null">--- select ---</option>
		
					<option value="my_friends.php">Add Friends...</option>		
					<option value="mailto:?subject=<?php echo htmlspecialchars($video['title']); ?>&body=http://www.kamtape.com/?v=<?php echo htmlspecialchars($video['vid']); ?>">E-mail...</option>
				</select>
						</td>
					</tr>
				</table>
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				</form>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</div>
		
		<div style="font-size: 12px; font-weight: bold; text-align:center;">
		<a href="#comment">Comment</a>
		&nbsp;&nbsp;//&nbsp;&nbsp; <a href="add_favorites.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" target="invisible" onClick="return FavoritesHandler();">Add to Favorites</a>
		&nbsp;&nbsp;//&nbsp;&nbsp; <a href="outbox.php?user=<?php echo htmlspecialchars($uploader['username']); ?>&subject=Re: <?php echo htmlspecialchars(urlencode($video['title'])); ?>">Contact Me</a>
        <?php if ($uploader['uid'] == $session['uid']) { ?><p><strong>Video Owner Options</strong>: <a href="/my_videos_edit.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>">Edit Your Video Here</a><? } ?>
		</div>
		
		<div style="text-align: center; padding-bottom: 10px;">
		<div id="flashcontent">
		<div style="padding: 20px; font-size:14px; font-weight: bold;">
			Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player, <a href="http://archive.org/download/fp8_archive/fp8_archive.zip">click here</a> to get the latest flash player.
		</div>
        <script type="text/javascript">
			if(swfobject.hasFlashPlayerVersion("6")) {	
				swfobject.embedSWF("/player.swf?video_id=<?php echo $video['vid']; ?>&l=<?php echo ceil($video['time']); ?>", "flashcontent", "450", "370", "6");
			}  
            
            </script><script type="text/javascript">
            if(typeof(document.createElement('video').canPlayType) != 'undefined' && document.createElement('video').canPlayType('video/webm;codecs="vp8,opus"') == "probably") {
				document.getElementById('flashcontent').innerHTML = `<!-- player HTML begins here -->
        <div class="player" id="playerBox">
            <div class="mainContainer">
                <div class="playerScreen">
                    <div class="playbackArea">
                        <div class="videoContainer">
                            <video class="videoObject" id="video">
                                <source src="/get_video.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>&format=webm"> 
                             </video>
                        </div>
                    </div>
                  <div class="watermark">
                        <img src="/viewfinder/resource/watermark.png" height="35px">
                    </div>
                </div>
                <div class="controlBackground">
                    <div class="controlContainer">
                        <div class="lBtnContainer">
                            <div class="button" id="playButton">
                                <img src="/viewfinder/resource/play.png" id="playIcon">
                                <img src="/viewfinder/resource/pause.png" class="hidden" id="pauseIcon">
                            </div>
                        </div>
                        <div class="centerContainer">
                            <div class="seekbarElementContainer">
                                <progress class="seekProgress" id="seekProgress" value="0" min="0"></progress>
                            </div>
                            <div class="seekbarElementContainer">
                                <input class="seekHandle" id="seekHandle" value="0" min="0" step="1" type="range">
                            </div>
                        </div>
                        <div class="rBtnContainer">
                            <div class="button" id="muteButton">
                                <img src="/viewfinder/resource/mute.png" id="muteIcon">
                                <img src="/viewfinder/resource/unmute.png" class="hidden" id="unmuteIcon">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="aboutBox hidden" id="aboutBox">
                <div class="aboutBoxContent">
                <div class="aboutHeader">Viewfinder</div>
                <div class="aboutBody">
                    <div>Version 1.0<br>
                    <br>
                    2005-Style HTML5 player<br>
                    <br>
                    by purpleblaze<br>
                    for KamTape.com
                </div>
                </div>
                <button id="aboutCloseBtn">Close</button>
                </div>
            </div>
            <div class="contextMenu hidden" id="playerContextMenu">
                <div class="contextItem" id="contextMute">
                    <span>Mute</span>
                    <div id="muteTick" class="tick hidden">    
                    </div>
                </div>
                <div class="contextItem" id="contextLoop">
                    <span>Loop</span>
                    <div id="loopTick" class="tick hidden">
                    </div>
                </div>
                <div class="contextSeparator"></div>
                <div class="contextItem" id="contextAbout">About</div>
            </div>
        </div>
        
        <!-- here lies purple -->
				`;
			}
			</script>
         <script src="/viewfinder/player.js"></script>
		</div>
		</div>
        <!-- i do actually have the code for yg_ratings but couldn't figure out how to m8k it work- once again, i'm a JS novice -->
		<script src="/js/components.js"></script>
		<table width="425" cellpadding="0" cellspacing="0" border="0" align="center">
			<tr>
				<td>
				<table width="400" cellpadding="0" cellspacing="0" border="0" align="center">
			<tbody><tr>
				<!-- <td style="padding-bottom: 15px;">
						<div style="float:left; margin-left:5em; padding-right: 18px;">
							<span>Average (255 votes)</span><br>
										
	<nobr>
			<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
			<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
			<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
			<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star.gif">
			<img style="border:0px; padding:0px; margin:0px; vertical-align:middle;" src="/img/star_bg.gif">
	</nobr>


						</div>
						<div id="ratingDiv" style="float:right; margin-right:5em;">
							<span id="ratingMessage">Rate this video!</span><br>

					
	<nobr>
			<form style="display:none;" name="ratingForm" action="/rating" method="POST">
	<input type="hidden" name="action_add_rating" value="1"/>
	<input type="hidden" name="rating_count" value="122">
	<input type="hidden" name="video_id" value="iMv3tQU5xvY">
	<input type="hidden" name="user_id" value="8DQDFNtuSOs">
	<input type="hidden" name="rating" id="rating" value="">
	<input type="hidden" name="size" value="L">
</form>

<script language="javascript">
	ratingComponent = new UTRating('ratingDiv', 5, 'ratingComponent', 'ratingForm', 'ratingMessage', '', 'L');
	ratingComponent.starCount=4;
			onLoadFunctionList.push(function() { ratingComponent.drawStars(4, true); });
</script>

	
<div>
		<nobr>
			<a href="#" onclick="ratingComponent.setStars(1); return false;" onmouseover="ratingComponent.showStars(1);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__1" class="rating" style="border: 0px"></a>
			<a href="#" onclick="ratingComponent.setStars(2); return false;" onmouseover="ratingComponent.showStars(2);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__2" class="rating" style="border: 0px"></a>
			<a href="#" onclick="ratingComponent.setStars(3); return false;" onmouseover="ratingComponent.showStars(3);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__3" class="rating" style="border: 0px"></a>
			<a href="#" onclick="ratingComponent.setStars(4); return false;" onmouseover="ratingComponent.showStars(4);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__4" class="rating" style="border: 0px"></a>
			<a href="#" onclick="ratingComponent.setStars(5); return false;" onmouseover="ratingComponent.showStars(5);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__5" class="rating" style="border: 0px"></a>
	</nobr>

</div>
</form> -->
	</nobr>

</a>
					</div>
					<!-- <br clear="all" />
				</div> -->
			</td>
		</tr>
	</tbody></table>
					<div class="watchDescription"><?php echo nl2br(htmlspecialchars($video['description'])); ?>					<div class="watchAdded" style="margin-top: 5px;">
										</div>
                                        
					</div>
					<div class="watchTags">Tags // <?php
						foreach(explode(" ", $video['tags']) as $tag) {
							echo ' <a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :';
						}
						?> 					</div>
			
								
					<div class="watchAdded">
					Added: <?php echo retroDate($video['uploaded'], "F j, Y, h:i a"); ?> by <a href="profile.php?user=<?php echo htmlspecialchars($uploader['username']); ?>"><?php echo htmlspecialchars($uploader['username']); ?></a> //
					<a href="profile_videos.php?user=<?php echo htmlspecialchars($uploader['username']); ?>">Videos</a> (<?php echo $maker_videos->rowCount(); ?>) | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($uploader['username']); ?>">Favorites</a> (<?php echo $maker_favorites->rowCount(); ?>) | Friends (0)
					</div>
			
					<div class="watchDetails">
					Runtime:  <?php echo gmdate("i:s", $video['time']); ?> | Views: <?php echo htmlspecialchars($video['views']); ?> | <a href="#comment">Comments</a>: <?php echo htmlspecialchars($video['comments']); ?>					</div>

				</td>
			</tr>
		</table>
		
		<!-- watchTable -->
		
		<div style="padding: 15px 0px 10px 0px;">
		<table width="471" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#E5ECF9">
			<tbody><tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="485"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<form name="linkForm" id="linkForm"></form>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td align="center">
		
				<div style="font-size: 14px;font-weight: bold;color: #CC6600;padding: 5px 0px 12px 0px;">Share this video! <a href="/sharing.php">Need help?</a></div>
				<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Video URL: <span style="color: #000;">E-mail or link it</span></div><div style="font-size: 11px; padding-bottom: 15px;">
				<input name="video_link" type="text" onclick="javascript:document.linkForm.video_link.focus();document.linkForm.video_link.select();" value="http://www.kamtape.com/?v=<?php echo htmlspecialchars($video['vid']); ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
				</div>
				<div style="font-size: 11px; font-weight: bold; color: #CC6600; padding: 5px 0px 5px 0px;">Video Thumbnail: <span style="color: #000;">Put a Thumbnail on your website</span></div>
				<div style="font-size: 11px; padding-bottom: 15px;">
				<input name="video_thumb" type="text" onclick="javascript:document.linkForm.video_thumb.focus();document.linkForm.video_thumb.select();" value="<iframe src=&quot;http://www.kamtape.com/videos_list.php?id=<?php echo htmlspecialchars($video['vid']); ?>&quot; width=&quot;265&quot; height=&quot;175&quot; frameborder=&quot;0&quot; marginheight=&quot;0&quot; marginwidth=&quot;0&quot;></iframe>" size="65" readonly="true" style="font-size: 10px; text-align: center;">
				<div style="font-size: 11px;font-weight: bold;color: #CC6600;padding: 5px 0px 9px 0px;">Video Player: <span style="padding: 1px 0px -1px 0px;color: #000;">Put a Video Player on your website<br>Works on eBay, Blogger, FriendProject!<p></p></span><input name="video_embed" type="text" onclick="javascript:document.linkForm.video_embed.focus();document.linkForm.video_embed.select();" value="<iframe id=&quot;embedplayer&quot; src=&quot;http://www.kamtape.com/v/<?php echo htmlspecialchars($video['vid']); ?>&quot; width=&quot;448&quot; height=&quot;382&quot; allowfullscreen scrolling=&quot;off&quot; frameborder=&quot;0&quot;></iframe>" size="65" readonly="true" style="font-size: 10px;text-align: center;"></div></div>
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table>
		</div>
		
		<a name="comment"></a>
		<div style="padding-bottom: 5px; font-weight: bold; color: #444;">Comment on this video:</div>

		<form name="comment_form" id="comment_form" method="post" action="add_comment.php" target="invisible" onsubmit="return CommentHandler();">
		<input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video['vid']); ?>">

		<textarea name="comment" cols="55" rows="3"></textarea>
		<br>
		<?php // attach video on utube has existed since july but only became visible in december
        //how? this is the only possible explanation
        if($_SESSION['uid'] != NULL) { ?>Attach a video: <select name="field_reference_video">				<option value="">- Your Videos -</option>				<?php if($videos_of_you !== false) { ?>
			<?php foreach($videos_of_you as $myvideo) { ?><option value="<?php echo htmlspecialchars($myvideo['vid']);?>"><?php echo htmlspecialchars($myvideo['title']);?></option> 				<?php } } ?><option value="">- Your Favorite Videos -</option>			<?php if($favorites_of_you !== false) { ?>
			<?php foreach($favorites_of_you as $myfavorites) { ?><option value="<?php echo htmlspecialchars($myfavorites['vid']);?>"><?php echo htmlspecialchars($myfavorites['title']);?></option> 				<?php } } ?></select><?php } ?> <input type="submit" name="comment_button" value="Add Comment">

		
		</form>
		<br>

		<div class="commentsTitle">Comments (<?php echo $comments->rowCount(); ?>):</div>
		<?php if($comments !== false) {
				foreach($comments as $comment) {
					$comment_videos = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND converted = 1");
					$comment_videos->execute([$comment["uid"]]);
					
					$comment_favorites = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
					$comment_favorites->execute([$comment["uid"]]);
                    if ($comment['vid'] == NULL) { ?>
		<div class="commentsEntry" width="60%">"<?php echo htmlspecialchars($comment['body']); ?>"<br>
 - <a href="profile.php?user=<?php echo htmlspecialchars($comment['username']); ?>"><?php echo htmlspecialchars($comment['username']); ?></a> // <a href="profile_videos.php?user=<?php echo htmlspecialchars($comment['username']); ?>">Videos</a> (<?php echo $comment_videos->rowCount(); ?>) | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($comment['username']); ?>">Favorites</a> (<?php echo $comment_favorites->rowCount(); ?>) - (<?php echo commentTimeAgo(strtotime($comment['post_date'])); ?>) <?php if ($uploader['uid'] == $session['uid'] || $comment['uid'] == $session['uid'] || $session['staff'] == 1 && $comment['uid'] != NULL) { ?><form style="display: inline;" method="POST" onsubmit="return confirm('Do you really want to delete this comment?!?!');"><input type="hidden" value="<?php echo htmlspecialchars($comment['cid']); ?>" name="c">(<a href="watch.php?v=<?php echo htmlspecialchars($video['vid']); ?>" onclick="this.closest('form').submit();return false;">Delete This Comment</a>)</form><?php } ?>
</div>	
<?php } else { ?>
<div class="commentsEntry">
					<table cellpadding="0" cellspacing="0" border="0">
					<tbody><tr valign="top">
					<td width="80">
					<div style="float: left;"><a href="watch.php?v=<?php echo htmlspecialchars($comment['vid']); ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($comment['vid']); ?>" class="commentsThumb" width="60" height="45"></a></div>
					<div style="font-size: 10px; text-align: center;"><a href="watch.php?v=<?php echo htmlspecialchars($comment['vid']); ?>">Related Video</a></div>
					</td><td style="font-size: 11px;">
					"<?php echo htmlspecialchars($comment['body']); ?>"<br>
 - <a href="profile.php?user=<?php echo htmlspecialchars($comment['username']); ?>"><?php echo htmlspecialchars($comment['username']); ?></a> // <a href="profile_videos.php?user=<?php echo htmlspecialchars($comment['username']); ?>">Videos</a> (<?php echo $comment_videos->rowCount(); ?>) | <a href="profile_favorites.php?user=<?php echo htmlspecialchars($comment['username']); ?>">Favorites</a> (<?php echo $comment_favorites->rowCount(); ?>) - (<?php echo commentTimeAgo(strtotime($comment['post_date'])); ?>) <?php if ($uploader['uid'] == $session['uid'] || $comment['uid'] == $session['uid'] || $session['staff'] == 1 && $comment['uid'] != NULL) { ?><form style="display: inline;" method="POST" onsubmit="return confirm('Do you really want to delete this comment?!?!');"><input type="hidden" value="<?php echo htmlspecialchars($comment['cid']); ?>" name="c">(<a href="watch.php?v=<?php echo htmlspecialchars($video['vid']); ?>" onclick="this.closest('form').submit();return false;">Delete This Comment</a>)</form><?php } ?>					</td></tr>
					</tbody></table>

					</div>
	
<? } } } ?>	
		</td>
		<td width="280">
    <?php $really_featured = $conn->prepare("SELECT * FROM picks WHERE video = :video_id");
    $really_featured->execute([
	":video_id" => $_GET['v']
    ]);

    if($really_featured->rowCount() == 1) {
	?>
               <table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#EEEEDD">
			<tbody><tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td width="270" style="padding: 5px; text-align: center;">
				
				
				<div style="font-size: 14px; font-weight: bold; color: #666633;">Previously Featured Video</div>
				<br>

				<div style="font-size: 12px; font-weight: normal; margin-bottom: 10px;">
				<a href="/browse.php">See More Featured</a>
				</div>
				
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table><br>
        <? } ?>
		<?php
    $search_resultz = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC");
    $search = str_replace(" ", "|", $video["tags"]);
    $search_resultz->execute([$search, $search, $search, $search, $search]); ?>		
		<table width="280" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td width="270">
				<div class="moduleTitleBar">
				<table width="270" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td><div class="moduleFrameBarTitle"><!-- Tag // <?php echo htmlspecialchars($video['tags']); ?> -->Related Videos (<?php echo $search_resultz->rowCount(); ?>)</div></td>
						<td align="right"><div style="font-size: 11px; margin-right: 5px;"><a href="results.php?&search=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>" target="_parent">Results Page</a></div></td>
					</tr>
				</table>
				</div>

				<iframe id="side_results" name="side_results" src="include_results.php?v=<?php echo htmlspecialchars($video['vid']); ?>&search=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>#selected" scrolling="auto" 
				 width="270" height="400" frameborder="0" marginheight="0" marginwidth="0">
				 [Content for browsers that don't support iframes goes here]
				</iframe>
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table><br>

		<? whos_online(4, 280, "Whos On Now"); ?>
		
		<div style="font-weight: bold; color: #333; margin: 10px 0px 5px 0px;">Related Tags:</div>
		<?php
			$related_tags = [];
			foreach($results as $result) $related_tags = array_merge($related_tags, explode(" ", $result['tags']));
			$related_tags = array_unique($related_tags);
			?>
			<?php foreach($related_tags as $tag) { ?>
			<div style="padding: 0px 0px 5px 0px; color: #999;">&#187; <a href="results.php?search=<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></a></div>
			<?php } ?>
		</td>
	</tr>
</table>

<iframe id="invisible" name="invisible" src="" scrolling="yes" width="0" height="0" frameborder="0" marginheight="0" marginwidth="0"></iframe>   

		</div>
		</td>
	</tr>
</table>
<?php 
require "needed/end.php";
?>