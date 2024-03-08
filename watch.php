<?php 
require "needed/start.php";
if(isset($_SERVER['HTTP_REFERER'])) {
    $vidReferer = $_SERVER['HTTP_REFERER'];
} else {
     $vidReferer = "https://kamtape.com";
}
if(strpos($_SERVER['HTTP_REFERER'], "kamtape.com") !== false){
  $vidReferer = "https://kamtape.com";  
}

if(strpos($_SERVER['HTTP_REFERER'], "66.33.192.247") !== false){
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

if ($uploader['termination'] == 1) {
    redirect("/index.php");
}

if($video['converted'] == 0) {
	header("Location: index.php");
}
echo "<title>KamTape - ".htmlspecialchars($video['title'])."</title>";

$search = str_replace(" ", "|", $video['tags']);
								$results = $conn->prepare("SELECT tags FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE videos.tags REGEXP ? AND videos.converted = 1 AND videos.privacy = 1 ORDER BY videos.uploaded DESC"); // Regex!
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
$maker_videos = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND converted = 1 AND privacy = 1");
$maker_videos->execute([$video["uid"]]);
					
$maker_favorites = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
$maker_favorites->execute([$video["uid"]]);

$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN users ON users.uid = comments.uid WHERE vidon = ? AND users.termination = 0 AND is_reply = 0 ORDER BY post_date DESC LIMIT 10");
$comments->execute([$video['vid']]);
$comments = $comments->fetchAll(PDO::FETCH_ASSOC);
rsort($comments);
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
$ACCESSES = $conn->prepare("SELECT referer FROM views WHERE vid = ? AND referer NOT LIKE '%kamtape.com%' ORDER BY viewed DESC");
$ACCESSES->execute([$video['vid']]);
$SITE_LIST = [];

foreach ($ACCESSES as $WEBSITE) {
    $SITE_LIST[] = $WEBSITE['referer'];
}

function isUserOnline($lastLoginDate) {
    // Define an online threshold (e.g., 5 minutes) in seconds
    $onlineThreshold = 5 * 60;

    // Convert the last login date to a Unix timestamp
    $lastLoginTimestamp = strtotime($lastLoginDate);

    // Calculate the difference between current time and last login time
    $timeDifference = time() - $lastLoginTimestamp;

    // Check if the time difference is within the online threshold with a margin of error
    // You can adjust the margin based on how accurate you want the online status to be
    $marginOfError = 30; // 30 seconds
    return $timeDifference <= ($onlineThreshold + $marginOfError);
}

$relatedvideos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC LIMIT 20");
$relatedvideos->execute([$search, $search, $search, $search, $search]);
$relatedvideostotal = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC");
$relatedvideostotal->execute([$search, $search, $search, $search, $search]);
$relvidnum = -1;
$video_favorites = $conn->prepare("SELECT fid FROM favorites WHERE vid = ?");
$video_favorites->execute([$video["vid"]]);
$vidresponses = $conn->prepare("SELECT rid FROM vidresponses WHERE responseto = ? AND accepted = 1");
$vidresponses->execute([$video["vid"]]);
$vidresponses = $vidresponses->fetchColumn();
    $vresponses = $conn->prepare(
	"SELECT * FROM vidresponses
	LEFT JOIN videos ON vidresponses.responsevid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE vidresponses.responseto = ? AND (videos.converted = 1 AND videos.privacy = 1) AND vidresponses.accepted = 1
	ORDER BY vidresponses.rid DESC LIMIT 20" // i don't know the actual limit (the highest num of responses ive seen was 18 [web.archive.org/web/20061103100321/http://www.youtube.com/watch%3Fv%3Do8tvNj_1Fr0]) so im gonna guess 20
);
$vresponses->execute([$video['vid']]);
$vidresponseto = $conn->prepare("SELECT * FROM vidresponses LEFT JOIN videos ON vidresponses.responseto = videos.vid WHERE vidresponses.responsevid = ? AND (videos.converted = 1 AND videos.privacy = 1) AND vidresponses.accepted = 1");
$vidresponseto->execute([$_GET['v']]);
switch($video['category']) {
					case '1':
						$catname = "Arts &amp; Animation";
						break;
					case '2':
						$catname = "Autos &amp; Vehicles";
						break;
					case '23':
						$catname = "Comedy";
						break;
					case '24':
						$catname = "Entertainment";
						break;
				    case '10':
						$catname = "Music";
						break;
				    case '25':
						$catname = "News &amp; Blogs";
						break;
				    case '22':
						$catname = "People";
						break;
				    case '15':
						$catname = "Pets &amp; Animals";
						break;
				    case '26':
						$catname = "Science &amp; Technology";
						break;
				    case '17':
						$catname = "Sports";
						break;
				    case '19':
						$catname = "Travel &amp; Places";
						break;
				    case '20':
						$catname = "Video Games";
						break;
					default:
						$catname = "Entertainment";
				}


?>
<meta property="og:title" content="<?php echo htmlspecialchars($video['title']); ?> by <?php echo htmlspecialchars($uploader['username']); ?> on KamTape" />
<meta property="og:description" content="<?php echo mb_strimwidth(htmlspecialchars($video['description']) , 0, 500, "..."); ?>" />
<meta property="og:image" content="http://kamtape.com/get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" />
<meta property="og:video" content="http://kamtape.com/get_video.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>&format=webm" />
<meta property="og:type" content="website">

<link rel="stylesheet" href="/css/watch_yts1164775696.css" type="text/css">
<link rel="stylesheet" href="/viewfinder/player.css">
<iframe id="invisible" name="invisible" src="" scrolling="yes" width="0" height="0" frameborder="0" marginheight="0" marginwidth="0"></iframe>   

<script type="text/javascript" src="/swfobject.js"></script>
<script type="text/javascript" src="/js/components_yts1157352107.js"></script>
<script type="text/javascript" src="/js/AJAX_yts1161839869.js"></script>
<script type="text/javascript" src="/js/ui_yts1164777409.js"></script>
<script type="text/javascript" src="/js/comments_yts1163746047.js"></script>
<script language="javascript" type="text/javascript">
	function toggleFullStats() {
		if (document.getElementById('honorLinkDiv'))
		{
			if (document.getElementById('honorLinkDiv').style.display=='') document.getElementById('honorLinkDiv').style.display= 'block';
			toggleDisplay('honorLinkDiv');
		}
		var fsd = document.getElementById('fullStats').style.display;
		if (fsd == 'none' || fsd == '') {
			showAjaxDivNotLoggedIn('honorsDiv', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_honors=1&l=EN', false);
			document.getElementById('moreless').innerHTML = 'less';
		}
		else
		{
			document.getElementById('moreless').innerHTML = 'more';
		}
		toggleDisplay('fullStats');
	}
	function CheckLogin()
	{
	
			<?php if($_SESSION['uid'] == NULL) { ?>
			//alert("You must be logged in to to perform this action!");
			return false;
		<?php } else { ?>
		
		return true;<? } ?>
	}

	function showRelatedVideosContent() {
		getAndShowNavContent('exRelated', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_related_videos_component&search=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>');
	}
	
	function showRelatedPlaylistContent() {
		getAndShowNavContent('exPlaylist', '/watch_ajax?feature=PlayList&search=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>&video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_related_playlist_component&p=&index=0');
	}
	function showRelatedUserContent() {
		getAndShowNavContent('exUser', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_user_videos_component&user_id=<?php echo htmlspecialchars($video['uid']); ?>&video_count=<?php echo $maker_videos->rowCount(); ?>');
	}
	function showHonorsContent() {
		getAndShowNavContent('honors', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_honors=1&l=EN');
	}
	
	function showAllQueuedVideos() {
		setInnerHTML('show_all_queued_videos_div', 'Loading..');
		getAndShowNavContent('watchlist_container', '/watch_queue_ajax?action_get_all_queue_videos_component&v=<?php echo htmlspecialchars($video['vid']); ?>', postShowAllQueuedVideos);
	}
	function postShowAllQueuedVideos() {
		setInnerHTML('show_all_queued_videos_div', 'Showing All Videos');
		jumpToNowPlaying();
	}
		
	// This can be split out...
	var contentTab=new Array();	
	function getAndShowNavContent(nameprefix, url, postShowNavContent) {
		selectNavLink(nameprefix + "Link");
	
		if(contentTab[nameprefix]) {
			return;
		}
		
		self.nameprefix = nameprefix;
		self.showRelatedVideosResponse = showRelatedVideosResponse;
		self.postShowNavContent = postShowNavContent
		getUrlXMLResponse(url, showRelatedVideosResponse);
	}
	function showRelatedVideosResponse(req) {
		document.getElementById(self.nameprefix + "Div").innerHTML=getNodeValue(req.responseXML, "html_content");
		setContentLoaded(self.nameprefix);
		
		if(self.postShowNavContent) {
			self.postShowNavContent();
		}
	}
		
	function setContentLoaded(nameprefix) {
		selectNavLink(nameprefix + "Link");
		contentTab[nameprefix] = 1;
	}
	function showAjaxDivNotLoggedIn(divName, url) {
			self.divName = divName
			self.showAjaxDivResponse = showAjaxDivResponse
			getUrlXMLResponse(url, self.showAjaxDivResponse);
	}
	function showAjaxDiv(divName, url) {
		if(CheckLogin()) {
			showAjaxDivNotLoggedIn(divName,url)
		} else {
			alert("Please login to perform this operation.");
		}
	}
	function showAjaxDivResponse(req) {
		document.getElementById(self.divName).innerHTML=getNodeValue(req.responseXML, "html_content");
		openDiv(self.divName);
	}
	
	function postAjaxForm(divName, formName) {
		self.postAjaxFormCompleted = postAjaxFormCompleted;	
		self.divName = divName;
		
		postFormXMLResponse(formName, self.postAjaxFormCompleted);
	}
	function postAjaxFormCompleted(req) {
		if(self.divName != null)
			hideAjaxDiv(self.divName);
	}
	function hideAjaxDiv(divName) {
		closeDiv(divName);
	}
	
	function xmlrpccallback(req) {
		alert('xmlrpccallback: ' + req.responseText);
	}

	function debugCompleted(req) {
		url = getNodeValue(req.responseXML, 'url');
		xmldata = getNodeValue(req.responseXML, 'xmldata');
		proxy = getNodeValue(req.responseXML, 'proxy');
		//alert('url: '+ url + '\nxmldata: ' + xmldata);
		postUrlXMLResponse(url, xmldata, xmlrpccallback);
		//alert ("url: " + url + "\nxmldata: " + xmldata + "\nproxy: " + proxy);
	}



				<?php if ($vidresponses != NULL) { ?>onLoadFunctionList.push(function() { imagesInit_video_responses();} );

		function imagesInit_video_responses() {
			imageBrowsers['video_responses'] = new ImageBrowser(<?php if($vidresponses < 4) { ?><?php echo htmlspecialchars($vidresponses); ?><? } else { ?>4<? } ?>, 1, "video_responses");
				<?php foreach($vresponses as $vresponse) { ?>
				
				
				
				
				
				
				imageBrowsers['video_responses'].addImage(new customYtImage("/get_still.php?video_id=<?php echo htmlspecialchars($vresponse['responsevid']); ?>", 
													  "/watch?v=<?php echo htmlspecialchars($vresponse['responsevid']); ?>&watch_response",
													  "\n<a href=\'/user/<?php echo htmlspecialchars($vresponse['username']); ?>\'><?php echo shorten($vresponse['username'], 11, ''); ?></a>\n\n", 
													  "",
													  "",
													  false) );
				<? } ?>
			imageBrowsers['video_responses'].initDisplay();
			imageBrowsers['video_responses'].showImages();
			images_loaded = true;
		}<? } ?>

	
	function getXMLRPCData(divName, formName) {
		self.callback = debugCompleted;
		postFormXMLResponse(formName, self.callback);
	}
	
	
	// Player stuff
	
	function openFull()
	{
	  var fs = window.open( "/watch_fullscreen?video_id=<?php echo htmlspecialchars($video['vid']); ?>&l=<?php echo ceil($video['time']); ?>&t=OEgsToPDskJscPkaWm96els0PbwqM0I8&s=BEA68A66C63585B9:8A51374783A7BCE6&fs=1&title=" + "<?php echo htmlspecialchars($video['title']); ?>" ,
			   "FullScreenVideo", "toolbar=no,width=" + screen.availWidth  + ",height=" + screen.availHeight 
			 + ",status=no,resizable=yes,fullscreen=yes,scrollbars=no");
	  fs.focus();
	}
	
	function gotoNext()
	{
	}
	
	function hideDiv(d) 
	{                   
		d.style.display = "none";
	}               
	
	function showDiv(d)
	{
		d.style.display = "block";
	}
	
	function autoNext()
	{
		var p = document.getElementById("movie_player");
		p.SetVariable("playnext", "1");
	
		var pa = document.getElementById("playall");
		var pga = document.getElementById("playingall");
		hideDiv(pa);
		showDiv(pga);
	}

	function postToTrackerForWatch(time_played)
	{
	}


		function showCommentReplyForm(form_id, reply_parent_id, is_main_comment_form) {
		if(!CheckLogin()) {
			alert("Please login to post a comment.");
			return false;
		}
			
		printCommentReplyForm(form_id, reply_parent_id, is_main_comment_form);
	}
	function printCommentReplyForm(form_id, reply_parent_id, is_main_comment_form) {

		var div_id = "div_" + form_id;
		var reply_id = "reply_" + form_id;
		var reply_comment_form = "comment_form" + form_id;
		
		if (is_main_comment_form)
			discard_visible="style='display: none'";
		else
			discard_visible="";
		
		var innerHTMLContent = '\
		<form name="' + reply_comment_form + '" id="' + reply_comment_form + '" method="post" action="/comment_servlet" >\
			<input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video['vid']); ?>">\
			<input type="hidden" name="add_comment" value="">\
			<input type="hidden" name="form_id" value="' + reply_comment_form + '">\
			<input type="hidden" name="reply_parent_id" value="' + reply_parent_id + '">\
			<input type="hidden" name="comment_type" value="V">\
			<textarea tabindex="2" name="comment" cols="55" rows="3"></textarea>\
			<br>\
			<input align="right" type="button" name="add_comment_button" \
								value="Post Comment" \
								onclick="postThreadedComment(\'' + reply_comment_form + '\');">\
			<input align="right" type="button" name="discard_comment_button"\
								value="Discard" ' + discard_visible + '\
								onclick="hideCommentReplyForm(\'' + form_id + '\',false);">\
		</form><br><br>';
		if(!is_main_comment_form) {
			toggleVisibility(reply_id, false);
		}
		setInnerHTML(div_id, innerHTMLContent);
		toggleVisibility(div_id, true);
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




	
		<?php if(isset($session)) { ?>onLoadFunctionList.push(function() { printCommentReplyForm('main_comment', '', true) } );<? } ?>
	
		onLoadFunctionList.push(function() { setContentLoaded("exRelated"); } );
	
	
	function selectNavLink (linkName) {
		if (linkName == "exRelatedLink") {
			closeDiv("exUserDiv");
			closeDiv("exPlaylistDiv");
			openDiv("exRelatedDiv");
			unSelectLink("exPlaylistLink");
			unSelectLink("exUserLink");
			selectLink("exRelatedLink");
			blurElement("exRelatedLink");
			replaceExploreTab("exploreMoreTabs", "/img/btn_exploretab_related_300x34.gif");
		}
		if (linkName == "exPlaylistLink") {
			closeDiv("exRelatedDiv");
			closeDiv("exUserDiv");
			openDiv("exPlaylistDiv");
			unSelectLink("exUserLink");
			unSelectLink("exRelatedLink");
			selectLink("exPlaylistLink");
			blurElement("exPlaylistLink");
			replaceExploreTab("exploreMoreTabs", "/img/btn_exploretab_playlist_300x34.gif");
		}
		if (linkName == "exUserLink") {
			closeDiv("exRelatedDiv");
			closeDiv("exPlaylistDiv");
			openDiv("exUserDiv");
			unSelectLink("exPlaylistLink");
			unSelectLink("exRelatedLink");
			selectLink("exUserLink");
			blurElement("exUserLink");
			replaceExploreTab("exploreMoreTabs", "/img/btn_exploretab_morefromuser_300x34.gif");
		}
	}
	
	function replaceExploreTab(elid, imgSrc) {
		var theElement = document.getElementById(elid);
		if (theElement) {
			theElement.src = imgSrc;
		}
	}

</script>


<? if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') { ?>
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
<? } ?>
<!-- begin main presentation code -->

<h1 id="video_title"><?php echo htmlspecialchars($video['title']); ?></h1>

<table cellpadding="0" cellspacing="0" align="center"><tr valign="top">
<td>
<div id="interactDiv">

	<div id="playerDiv">
		<div style="padding: 20px; font-size:14px; font-weight: bold;">
			Hello, you either have JavaScript turned off or an old version of Macromedia's Flash Player. <a href="http://www.macromedia.com/go/getflashplayer/" onclick="_hbLink('Get+Flash','Watch');">Get the latest flash player</a>.
		</div>
	</div>
		<script type="text/javascript">
			if(swfobject.hasFlashPlayerVersion("6")) {	
				swfobject.embedSWF("/player2.swf?video_id=<?php echo $video['vid']; ?>&l=<?php echo ceil($video['time']); ?>", "playerDiv", "450", "370", "6");
			}  
            
            </script><script type="text/javascript">
            if(typeof(document.createElement('video').canPlayType) != 'undefined' && document.createElement('video').canPlayType('video/webm;codecs="vp8,opus"') == "probably") {
				document.getElementById('playerDiv').innerHTML = `<!-- player HTML begins here -->
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
		
		<?php if ($session['staff'] == 1 && $session['uid'] != $video['uid']) { ?>
		<div style="font-size: 12px; font-weight: bold; text-align: center; padding-bottom: 10px;">
		
        <p>ManagerTape Options: <a href="/admin/videos_feature.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>">Feature This Video</a>&nbsp;&nbsp;//&nbsp;&nbsp;<a style="color:#f22b33;" href="/admin/videos_reject.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>">Reject This Video</a><br><a style="color:#f22b33;" href="/admin/user_terminate.php?user_id=<?php echo htmlspecialchars($uploader['uid']); ?>">Terminate The Creator</a>&nbsp;&nbsp;//&nbsp;&nbsp;<a href="/admin/videos_restrict.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>">Mark 18+</a>&nbsp;&nbsp;//&nbsp;&nbsp;<a href="/admin/categorize.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>">Recategorize</a>
				</div>
				<? } ?>

	<?php if ($vidresponseto->rowCount() != 0) { $vidresponseto = $vidresponseto->fetch(PDO::FETCH_ASSOC); ?>
	<div id="vResponseParent">
		This is a video response to
		<a href="/watch?v=<?php echo htmlspecialchars($vidresponseto['responseto']); ?>"><?php echo htmlspecialchars($vidresponseto['title']); ?></a>
		
	</div>
	<? } ?>
	



	<div id="watchqueueStartNew" class="quicklist_container hid">
	
		<div><img src="/img/pic_curves_top_450x4.gif" border="1" /></div>
		<div id="nextvideoRow" class="nextVideoRowDiv">
			<table width="440" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="77" align="left" valign="top">
						<div class="headerTitleLite">&nbsp;QuickList</div>
					</td>
					<td width="30" valign="top" align="right">
							<a href="#" onclick="play_all_start_new();_hbLink('QuickList+PlayAll','Watch');return false;" rel="nofollow" ><img id="play_all_buttton" hspace="4" src="/img/pixel.gif" border="0" valign="top" height="25" width="33" alt="Play All Videos" /></a>
									</td>
					<td width="53" valign="top">
								<div id="playall" class="smallText">
								<b><a href="#" onclick="autoNext();gotoNext();return false;" rel="nofollow" >Play All</a></b></div>
								<div id="playingall" class="grayText smallText hid">Playing All</div>
						<span id="play_all_numb" class="smallText grayText">
						<script language="javascript" type="text/javascript">document.write(quicklist_count);</script>
						</span>
						<span class="smallText grayText">
						video<script language="javascript" type="text/javascript">if (quicklist_count>1) document.write('s');</script>
						</span>
					</td>
							<td width="30" valign="top" align="right">
							<a id="next_video_url_1" href="#"><img id="next_video_image_url" src="/img/pixel.gif" height="25" border="0" style="padding-right:2px;" align="middle"></a>
							</td>
							<td width="200" valign="top">
							<div class="nextVideoDisplay">
								<span class="grayText smallText"><b><a id="next_video_url_2" href="#">Play Next</a></b>
								<br /><span id="next_video_title">
								</span>
							</div>
							</td>	
					<td width="40" valign="top" align="right">
						<div id="show_button_container" class="hideShowButton">
						   <a href="#" onClick="clickedHideShowButton();_hbLink('QuickList+ShowHide','Watch');return false;" rel="nofollow" ><img id="watch_queue_show_hide" src="/img/btn_watchqueue_hide_33x25.gif" width="33" height="25" border="0" alt="Show or Hide QuickList Videos"></a>                
						</div>
					</td>
				</tr>
			</table>
		</div>
	
		<div id="watchlist_container" class="watchlist_videos">                
		<table id="watchlist_table" width="426" align="center" border="0" cellpadding="0" cellspacing="0">                        
			<tr><td width="8"></td><td width="55"></td><td width="313"></td><td width="50"></td></tr>                
		</table>
		</div>

		<div id="save_row" class="saveRowDiv" style="height:20px;display:block;">
			<div class="saveRowSetting">
				<span class="smallText">
				<form id="set_pop" name="set_pop">
				&nbsp;<input id="checkbox_pop_no_pop" type="checkbox"  onClick="if(document.set_pop.quicklist_pop_nopop.checked==true) {set_pop_status(1);} else{set_pop_status(0);};" name="quicklist_pop_nopop">Remove videos as I watch them   
				</form>
				</span>
			</div>
			<div class="quicklistActions">
			<span class="smallText"><b><a href="/watch_queue?all" title="Go to QuickList page" onclick="_hbLink('QuickList+ManageLink','Watch');" rel="nofollow" >Manage</a> | <a href="/edit_playlist_info?watch_queue=1" title="Save all videos into a permanent playlist" onclick="_hbLink('QuickList+SaveLink','Watch');" rel="nofollow">Save</a> | <a href="#" onClick="javascript:return clear_watch_queue_watch_page();_hbLink('QuickList+ClearLink','Watch');return false;" title="Remove all videos from QuickList" rel="nofollow">Clear</a></b></span>
			</div>
		</div> <!-- end save_row -->
		<div><img src="/img/pic_curves_bottom_450x4.gif" border="1"></div>
	</div> <!-- end QuickList -->

		
	<div id="actionsAndStatsDiv" class="contentBox">
		<div id="ratingDivWrapper">
			<div id="ratingDiv">

	

				<div id="ratingMessage"><?php if ($uploader['uid'] == $session['uid']) { ?>Video Rating<? } else { ?>Rate this video<? } ?>:</div>
				<?php if ($_SESSION['uid'] != NULL && $uploader['uid'] != $session['uid']) { ?>
					
<form style="display:none;" name="ratingForm" action="/rating" method="POST">
	<input type="hidden" name="action_add_rating" value="1" />
	<input type="hidden" name="rating_count" value="<? echo htmlspecialchars(getRatingCount($video['vid'])); ?>">
	<input type="hidden" name="video_id" value="<?php echo htmlspecialchars($video['vid']); ?>">
	<input type="hidden" name="user_id" value="<?php echo htmlspecialchars($session['uid']); ?>">
	<input type="hidden" name="rating" id="rating" value="">
	<input type="hidden" name="size" value="L">
</form>

<script language="javascript">
	ratingComponent = new UTRating('ratingDiv', 5, 'ratingComponent', 'ratingForm', 'ratingMessage', '', 'L');
	ratingComponent.starCount=<? echo htmlspecialchars(getRatingAverage($video['vid'])); ?>;
			onLoadFunctionList.push(function() { ratingComponent.drawStars(<? echo htmlspecialchars(getRatingAverage($video['vid'])); ?>, true); });
</script>

	
<div>
		<nobr>
			<a href="#" onclick="ratingComponent.setStars(1); return false;" onmouseover="ratingComponent.showStars(1);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__1" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(2); return false;" onmouseover="ratingComponent.showStars(2);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__2" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(3); return false;" onmouseover="ratingComponent.showStars(3);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__3" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(4); return false;" onmouseover="ratingComponent.showStars(4);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__4" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(5); return false;" onmouseover="ratingComponent.showStars(5);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__5" class="rating" style="border: 0px" ></a>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($video['vid'])); ?> rating<? if (htmlspecialchars(getRatingCount($video['vid'])) != 1) { ?>s<? } ?></div>
	


</div>
<? } else { ?>
							<nobr>
			<? grabRatings($video['vid'], "L", 'class="rating"'); ?>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($video['vid'])); ?> rating<? if (htmlspecialchars(getRatingCount($video['vid'])) != 1) { ?>s<? } ?></div>
		<?php if ($uploader['uid'] == $session['uid']) { ?>
		<div class="rating">You cannot rate your own video.</div>
		<? } ?>
		<? } ?>



			
		<div class="spacer"></div>
			</div> <!-- end ratingDiv -->
			
		</div> <!-- end ratingDivWrapper -->
			
		<div class="actionsDiv">
			<div class="actionRow">
					<a href="#" class="noul" onclick="showAjaxDiv('addFavsDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_playlists_component=1', true); _hbLink('Save+To+Favorites','Watch'); return false;" onmouseover="" rel="nofollow"><img src="/img/icn_fav_reg_19x17.gif" border="0" class="alignMid" alt="Save to Favorites"> <span class="eLink">Save to Favorites</span></a>
			</div>
			<div class="actionRow">
				<a href="#" class="noul" onclick="showAjaxDiv('addGroupsDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_groups_component=1', true); _hbLink('Add+To+Groups','Watch'); return false;" rel="nofollow"><img src="/img/icn_groups_reg_19x17.gif" border="0" class="alignMid" alt="Add to Groups"> <span class="eLink">Add to Groups</span></a>
			</div>
		</div>
		<div class="actionsDiv sm">
			<div class="actionRow">
				<a href="#" class="noul" onClick="window.open('/share?v=<?php echo htmlspecialchars($video['vid']); ?>','Share','width=580,height=480,resizable=yes,scrollbars=yes,status=0'); _hbLink('Share+Video','Watch'); return false;" rel="nofollow"><img src="/img/icn_email_reg_19x17.gif" border="0" class="alignMid" alt="Share This Video"> <span class="eLink">Share Video</span></a>
			</div>
			<div class="actionRow">
				<a href="#" class="noul" onclick="showAjaxDiv('blogVidDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_user_blogs_component=1', true); _hbLink('Blog+Video','Watch'); return false;" rel="nofollow"><img src="/img/icn_web_reg_19x17.gif" border="0" class="alignMid"> <span class="eLink">Post Video</span></a>
			</div>
		</div>
		<div class="actionsDiv sm">
			<div class="actionRow">
				<a href="#" class="noul" onclick="showAjaxDiv('inappropriateVidDiv', '/watch_ajax?video_id=<?php echo htmlspecialchars($video['vid']); ?>&action_get_flag_video_component=1', true); _hbLink('Flag+Inappropriate','Watch'); return false;" rel="nofollow"><img src="/img/icn_flag_reg_19x17.gif" border="0" class="alignMid" alt="Flag as Inappropriate"> <span class="eLink">Flag as Inappropriate</span></a>
			</div>
		</div>
		<div class="spacer"></div>
		<div id="statsDiv">
			Views: <span class="statVal"><?php echo number_format($video['views']); ?></span> <div class="statDivider">|</div>
			Comments: <span class="statVal"><?php echo number_format($video['comments']); ?></span> <div class="statDivider">|</div>
			Favorited: <span class="statVal"><?php echo $video_favorites->rowCount(); ?></span>
				times
			<div class="statDivider">|</div> <a href="#" onclick="toggleFullStats(); return false;" rel="nofollow"><span id="moreless">more</span>&nbsp;stats...</a>
		</div>
		<div id="fullStats">
			<div id="referDiv" class="moreStats">
			<? if ($SITE_LIST != NULL) { ?>
				<h4>Sites Linking to This Video:</h4>
					<?php foreach($SITE_LIST as $frequency => $referer	) {
                        if ($frequency < 1) {
                        $frequency = 1;
                        }
                        if(empty($referer)) {
                            echo '<div class="statItem"><span class="label">'.htmlspecialchars($frequency).' click from somewhere...</span></div>'."\r\n";
                            } else {
					echo '<div class="statItem"><span class="label">'.htmlspecialchars($frequency).' clicks from </span><a rel="nofollow" href="r.php?u='.htmlspecialchars($referer).'" target="_top">'.htmlspecialchars($referer).'</a></div>'."\r\n"; }
					} ?>
			<? } else { ?>
				<h4>(There are no sites linking to this video.)</h4><? } ?>
			</div>
			<div id="honorsDiv" class="moreStats"><h3>Loading Honors...</h3></div>
			<div id="fullStatsClose"><a href="#" onclick="toggleFullStats(); return false;" rel="nofollow">close</a></div>
			<div class="spacer"></div>
		</div>
		<!--<div id="honorLinkDiv">
			<span>
				<a href="#" onclick="toggleFullStats(); return false;">There are 6 honors for this video</a>
			</span>
		</div>-->


		

		<div id="addFavsDiv" class="popupDiv"></div>
		<div id="addGroupsDiv" class="popupDiv"></div>
		<div id="blogVidDiv" class="popupDiv"></div>
		<div id="inappropriateVidDiv" class="popupDiv"></div>

	</div> <!-- end actionsAndStatsDiv -->



			
	<div id="commentsDiv">
				<?php if ($vidresponses == NULL) { ?><table cellpadding="0" cellspacing="0" width="100%"><tr>
				<td><h2 style="margin: 0px;">Comments &amp; Responses</h2></td>
				<td align="right">
						<div style="padding-bottom: 2px;">
						<b><a href="/video_response_upload?v=<?php echo htmlspecialchars($video['vid']); ?>" onclick="_hbLink('Post+Video+Response','Watch');" rel="nofollow">Post a video response</a></b>
						</div><? } else { ?>
			<h2 style="margin: 0px;">Comments &amp; Responses</h2>
			
				<div id="vResponseDiv">
		<div id="vResponseHeading">
			<table cellpadding="0" cellspacing="0" width="100%"><tr>
			<td>
				<b>Video Responses</b>
					&nbsp;(<a href="/video_response_view_all?v=<?php echo htmlspecialchars($video['vid']); ?>"><?php echo htmlspecialchars($vidresponses); ?> responses</a>)
			</td>
			<td align="right">
					<b><a href="/video_response_upload?v=<?php echo htmlspecialchars($video['vid']); ?>" onclick="_hbLink('Post+Video+Response','Watch');">Post a Video Response</a></b>
			</td>
			</tr></table>
		</div>

		
		<div>
				<div style="padding-left: 1px;">					
	<table width="21" height="90" cellpadding="0" cellspacing="0">
		<tr>
			<td><img src="/img/LeftSingleArrowOff.gif" onclick="shiftLeft('video_responses')" border=0></td>
			<td>
				<table width="392" height="90" style="background-color: #FFFFFF; " cellpadding="0" cellspacing="0">
					<tr>
					<td style="border-bottom:none;">
						<div id="div_video_responses_0" style="float: left; width: 97px; padding: 1px;">
<center>
<a id="href_video_responses_0" href=".."><img class="videobarthumbnail_white" id="img_video_responses_0" src="/img/pixel.gif" width="80" height="60"></a>
<div id = "title1_video_responses_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
<div id = "title2_video_responses_0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;"></div>
</center>
</div>
<div id="div_video_responses_0_alternate" style="float: left; width: 97px; padding: 1px; display: none">
	<center>
		<div><img src="/img/pixel.gif" width="80" height="60"></div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
	</center>
</div>

						<?php if($videoresponses > 1) { ?><div id="div_video_responses_1" style="float: left; width: 97px; padding: 1px;">
<center>
<a id="href_video_responses_1" href=".."><img class="videobarthumbnail_white" id="img_video_responses_1" src="/img/pixel.gif" width="80" height="60"></a>
<div id = "title1_video_responses_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
<div id = "title2_video_responses_1" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;"></div>
</center>
</div>
<div id="div_video_responses_1_alternate" style="float: left; width: 97px; padding: 1px; display: none">
	<center>
		<div><img src="/img/pixel.gif" width="80" height="60"></div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
	</center>
</div><? } ?>

						<?php if($videoresponses > 2) { ?><div id="div_video_responses_2" style="float: left; width: 97px; padding: 1px;">
<center>
<a id="href_video_responses_2" href=".."><img class="videobarthumbnail_white" id="img_video_responses_2" src="/img/pixel.gif" width="80" height="60"></a>
<div id = "title1_video_responses_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
<div id = "title2_video_responses_2" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;"></div>
</center>
</div>
<div id="div_video_responses_2_alternate" style="float: left; width: 97px; padding: 1px; display: none">
	<center>
		<div><img src="/img/pixel.gif" width="80" height="60"></div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
		<div style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">&nbsp;</div>
	</center>
</div><? } ?>

						<?php if($videoresponses > 3) { ?><div id="div_video_responses_3" style="float: left; width: 97px; padding: 1px;">
<center>
<a id="href_video_responses_3" href=".."><img class="videobarthumbnail_white" id="img_video_responses_3" src="/img/pixel.gif" width="80" height="60"></a>
<div id = "title1_video_responses_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;">loading...</div>
<div id = "title2_video_responses_3" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #666666; padding-bottom: 3px;"></div>
</center>
</div>
<div id="div_video_responses_3_alternate" style="float: left; width: 97px; padding: 1px; display: none">
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
			<td><img src="/img/RightSingleArrowOff.gif" onclick="shiftRight('video_responses');" border=0></td>
		</tr>
	</table>
	</div>

		
				
		</div>
	</div> <!-- end vResponseDiv -->

			
				<table cellpadding="0" cellspacing="0" width="100%"><tr>
				<td><b class="largeText">Text Comments (<?php echo $video['comments']; ?>)</b></td>
				<td align="right"><? } ?>
							<div id="reply_main_comment2">
			<b><a href="#" class="eLink" onclick="showCommentReplyForm('main_comment2', '', false); return false;" onclick="_hbLink('Post+Text+Comment','Watch');" id="post_text_comment_link" rel="nofollow">Post a text comment</a></b>
		</div>

				</td>
				</tr></table>
	
	
	
			
			<div id="recent_comments">
				
				<div id="div_main_comment2"></div>


						<!--<div class="commentPagination marT5">
		Most Recent
	&nbsp;...&nbsp;
			<span class="commentPnum">1</span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=2', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">2</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=3', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">3</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=4', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">4</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=5', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">5</a></span>
	&nbsp;...&nbsp;
		<a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=5', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">Oldest</a></b>
	</div>-->

				
	<?php if($comments !== false) {
				foreach($comments as $comment) {
					$comment_videos = $conn->prepare("SELECT vid FROM videos WHERE uid = ? AND converted = 1");
					$comment_videos->execute([$comment["uid"]]);
					
					$comment_favorites = $conn->prepare("SELECT fid FROM favorites WHERE uid = ?");
					$comment_favorites->execute([$comment["uid"]]);
                    if ($comment['vid'] == NULL) { ?>
			<div id="div_<?php echo htmlspecialchars($comment['cid']); ?>"> <!-- comment_div_id -->
			<a name="<?php echo htmlspecialchars($comment['cid']); ?>"/></a>
			<div class="commentEntry" id="comment_<?php echo htmlspecialchars($comment['cid']); ?>">
                                <div class="commentHead">
				<b><a href="/user/<?php echo htmlspecialchars($comment['username']); ?>" rel="nofollow"><?php echo htmlspecialchars($comment['username']); ?></a></b>
				<span class="smallText"> (<?php echo timeAgo($comment['post_date']); ?>) </span>
			</div>
				<div class="commentBody">
					<?php echo htmlspecialchars($comment['body']); ?>
				</div>
				<div class="commentAction smallText">
					


	<div class="commentAction smallText" id="container_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>" style="display: none"> </div> <!-- container id -->
		<div class="commentAction smallText" id="reply_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>">
				(<a href="#" onclick="showCommentReplyForm('comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>', '<?php echo htmlspecialchars($comment['cid']); ?>', false); return false;" class="eLink" rel="nofollow">Reply</a>) &nbsp; 
						<?php if($_SESSION['uid'] != NULL) { ?>(<a href="#" onclick="postUrlXMLResponse('/comment_servlet',  '&mark_comment_as_spam=<?php echo htmlspecialchars($comment['cid']); ?>&entity_id=<?php echo htmlspecialchars($video['vid']); ?>', null); return false;" class="smallText" rel="nofollow">Mark as spam</a>)
<? } ?>

		</div>
		<div id="div_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>">
	</div>

				</div>
			</div>
	</div> <!-- comment_div_id -->
	<?php } else { ?>
			<div id="div_<?php echo htmlspecialchars($comment['cid']); ?>"> <!-- comment_div_id -->
			<a name="<?php echo htmlspecialchars($comment['cid']); ?>"/></a>
			<div class="commentEntry" id="comment_<?php echo htmlspecialchars($comment['cid']); ?>">
                                <div class="commentHead">
				<b><a href="/user/<?php echo htmlspecialchars($comment['username']); ?>" rel="nofollow"><?php echo htmlspecialchars($comment['username']); ?></a></b>
				<span class="smallText"> (<?php echo timeAgo($comment['post_date']); ?>) </span>
			</div>
			    <div style="float: left;"><a href="watch.php?v=<?php echo htmlspecialchars($comment['vid']); ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($comment['vid']); ?>" class="commentsThumb" width="60" height="45"></a></div>
				<div style="font-size: 10px; text-align: center;"><a href="watch.php?v=<?php echo htmlspecialchars($comment['vid']); ?>">Related Video</a></div>
				<div class="commentBody">
					<?php echo htmlspecialchars($comment['body']); ?>
				</div>
				<div class="commentAction smallText">
					


	<div class="commentAction smallText" id="container_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>" style="display: none"> </div> <!-- container id -->
		<div class="commentAction smallText" id="reply_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>">
				(<a href="#" onclick="showCommentReplyForm('comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>', '<?php echo htmlspecialchars($comment['cid']); ?>', false); return false;" class="eLink" rel="nofollow">Reply</a>) &nbsp; 
						<?php if($_SESSION['uid'] != NULL) { ?>(<a href="#" onclick="postUrlXMLResponse('/comment_servlet',  '&mark_comment_as_spam=<?php echo htmlspecialchars($comment['cid']); ?>&entity_id=<?php echo htmlspecialchars($video['vid']); ?>', null); return false;" class="smallText" rel="nofollow">Mark as spam</a>)
<? } ?>

		</div>
		<div id="div_comment_form_id_<?php echo htmlspecialchars($comment['cid']); ?>">
	</div>

				</div>
			</div>
	</div> <!-- comment_div_id --><? } ?>
	<?php $replies = $conn->prepare("SELECT * FROM comments LEFT JOIN users ON users.uid = comments.uid WHERE is_reply = ? AND master_comment = ? AND users.termination = 0 ORDER BY post_date ASC");
	$replies->execute([1, $comment['cid']]);
	foreach($replies as $reply) { ?>
			<div id="div_<?php echo htmlspecialchars($reply['cid']); ?>"> <!-- comment_div_id -->
			<a name="<?php echo htmlspecialchars($reply['cid']); ?>"/></a>
			<div class="commentEntryReply" id="comment_<?php echo htmlspecialchars($reply['cid']); ?>">
				<div class="commentHead">
				<b><a href="/user/<?php echo htmlspecialchars($reply['username']); ?>"><?php echo htmlspecialchars($reply['username']); ?></a></b>
				<span class="smallText"> (<?php echo timeAgo($reply['post_date']); ?>) </span>
			</div>
				<div class="commentBody">
					<?php echo htmlspecialchars($reply['body']); ?>
				</div>
				<div class="commentAction smallText">
					


	<div class="commentAction smallText" id="container_comment_form_id_<?php echo htmlspecialchars($reply['cid']); ?>" style="display: none"> </div> <!-- container id -->
		<div class="commentAction smallText" id="reply_comment_form_id_<?php echo htmlspecialchars($reply['cid']); ?>">
				(<a href="#" onclick="showCommentReplyForm('comment_form_id_<?php echo htmlspecialchars($reply['cid']); ?>', '<?php echo htmlspecialchars($reply['cid']); ?>', false); return false;" class="eLink">Reply</a>) &nbsp; 
						<?php if($_SESSION['uid'] != NULL) { ?>(<a href="#" onclick="postUrlXMLResponse('/comment_servlet',  '&mark_comment_as_spam=<?php echo htmlspecialchars($reply['cid']); ?>&entity_id=<?php echo htmlspecialchars($video['vid']); ?>', null); return false;" class="smallText" rel="nofollow">Mark as spam</a>)
<? } ?>

		</div>
		<div id="div_comment_form_id_<?php echo htmlspecialchars($reply['cid']); ?>">
	</div>

				</div>
			</div>
	</div> <!-- comment_div_id -->
	<? } } } ?>



						<!--<div class="commentPagination">
		Most Recent
	&nbsp;...&nbsp;
			<span class="commentPnum">1</span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=2', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">2</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=3', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">3</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=4', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">4</a></span>
			<span class="commentPnum"><a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=5', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">5</a></span>
	&nbsp;...&nbsp;
		<a href="#" onclick="showAjaxDivNotLoggedIn('recent_comments', '/watch_ajax?v=<?php echo htmlspecialchars($video['vid']); ?>&action_get_comments=1&p=5', true);document.getElementById('post_text_comment_link').focus(); return false;" rel="nofollow">Oldest</a></b>
	</div>-->

				<b><a href="/comment_servlet?all_comments&v=<?php echo htmlspecialchars($video['vid']); ?>&fromurl=/watch%3Fv%3D<?php echo htmlspecialchars($video['vid']); ?>" onclick="_hbLink('View+All+Comments','Watch');" rel="nofollow">View all <?php echo $video['comments']; ?> comments</a></b>
				
			</div> <!-- end recent_comments -->
	
	
		<div id="all_comments" style="display: none;">
				
				<div id="div_main_comment2"></div>
				
				<div id="all_comments_content">
				</div>
		</div> <!-- end all_comments -->

	
			<div id="commentPostDiv"><?php if(isset($session)) { ?>
				<table cellpadding="0" cellspacing="0" width="100%"><tr>
				<td><h2 style="margin: 0px;">Comment on this video</h2></td>
				<td align="right">
					<div style="padding-bottom: 2px;">
					<b><a href="/video_response_upload?v=<?php echo htmlspecialchars($video['vid']); ?>" onclick="_hbLink('Post+Video+Response','Watch');" rel="nofollow">Post a video response</a></b>
					</div>
				</td>
				</tr></table><?php } else if(!isset($session)){ ?>
				<h2 style="margin: 0px;">Would you like to comment?</h2>
				<div style="margin-top: 8px;">
					<a href="/signup">Join YouTube</a> for a free account, or
					<a href="/signup_login">Login</a> if you are already a member.
				</div>
			</div> <!-- end post a comment section --><? } ?>
			
			<div id="div_main_comment"></div>
	</div> <!-- end commentsDiv -->
	
</div> <!-- end interactDiv -->
</td>



<td>

<div id="aboutExploreDiv">
	<div id="aboutVidDiv" class="contentBox">
		<div id="uploaderInfo">
		
		<div id="subscribeDiv" class="smallText">
			<div><a href="#" onclick="showAjaxDiv('emptydiv','/ajax_subscriptions?subscribe_to_user=<?php echo htmlspecialchars($uploader['username']); ?>',false); return false;" title="subscribe to <?php echo htmlspecialchars($uploader['username']); ?>'s videos"><img src="/img/btn_subscribe_sm_yellow_99x16.gif" width="99" height="16" class="alignMid" alt="subscribe" border="0" title="subscribe to <?php echo htmlspecialchars($uploader['username']); ?>'s videos"></a></div>
			<div id="emptydiv">							<div id="subscribeCount" class="smallText">to <?php echo htmlspecialchars($uploader['username']); ?></div>
			</div>
		</div> <!-- end subscribeDiv -->
		
		<div id="userInfoDiv">
			<span class="smallLabel">Added</span>&nbsp;
			<b class="smallText"><?php echo retroDate($video['uploaded'], "F j, Y"); ?></b><br>
			<span class="smallLabel">From</span>&nbsp;
			<b><a href="/user/<?php echo htmlspecialchars($uploader['username']); ?>" onclick="_hbLink('ChannelLink','Watch');"><?php echo htmlspecialchars($uploader['username']); ?></a></b>
		</div> <!-- end userInfoDiv -->

	
			
			

		</div> <!-- end uploaderInfo -->
		
		
		<?php if ($uploader['uid'] == $session['uid']) { ?>
		<div style="margin: 8px 0px;" class="smallText">
            <span class="smallLabel">Video Owner Options:</span>
            <a href="http://www.youtube.com/my_videos_edit?video_id=hohu8SSpduM">Edit Your Video</a>
        </div>	
		<? } ?>
		
		
        <div id="vidDescDiv">
        	
			<span id="vidDescBegin">
			<?php echo shorten($video['description'], 40); ?>
			</span>
			<?php if (shorten($video['description'], 40) != htmlspecialchars($video['description'])) {?>
				<span id="vidDescRemain"><?php echo htmlspecialchars($video['description']); ?></span>
				<span id="vidDescMore" class="smallText">... (<a href="#" class="eLink" onclick="showInline('vidDescRemain'); hideInline('vidDescMore'); hideInline('vidDescBegin'); showInline('vidDescLess'); return false;"  rel="nofollow">more</a>)</span>
			<span id="vidDescLess" class="smallText">(<a href="#" class="eLink" onclick="hideInline('vidDescRemain'); hideInline('vidDescLess'); showInline('vidDescBegin'); showInline('vidDescMore'); return false;" rel="nofollow">less</a>)</span><? } ?>
		</div>
		

			<div class="smallText">
			<span class="smallLabel">Category&nbsp;</span>
			<a href="/browse?s=mp&t=t&c=<?php echo htmlspecialchars($video['category']); ?>" class="dg" onclick="_hbLink('Video+Category+Link','Watch');"><?php echo $catname; ?></a>
			</div>


		<div id="vidFacetsDiv">
            <form name="urlForm" id="urlForm">
            <table cellpadding="0" cellspacing="0" id="vidFacetsTable">
            <tr><td class="label">Tags</td>
            <td class="tags">		
				<span id="vidTagsBegin">
<?php $tags = explode(" ", $video['tags']); $faketagCount = 4; $tagCount = count($tags); foreach ($tags as $index => $tag) { echo '<a href="results.php?search=' . htmlspecialchars($tag) . '" class="dg">' . htmlspecialchars($tag) . '</a>';
    if ($index < $faketagCount - 1) {
        echo ' &nbsp; ';
    }
	if ($index == $faketagCount && $index != $tagCount) {
		echo '				</span>
					<span id="vidTagsRemain">';
		if ($index < $tagCount - 1) {
			echo ' &nbsp;';
		}
		if ($index == $tagCount) {
			echo '					</span>
					&nbsp;
					<span id="vidTagsMore" class="smallText">(<a href="#" class="eLink" onclick="showInline(\'vidTagsRemain\'); hideInline(\'vidTagsMore\'); showInline(\'vidTagsLess\'); return false;" rel="nofollow">more</a>)</span>
					<span id="vidTagsLess" class="smallText">(<a href="#" class="eLink" onclick="hideInline(\'vidTagsRemain\'); hideInline(\'vidTagsLess\'); showInline(\'vidTagsMore\'); return false;" rel="nofollow">less</a>)</span>';
		}
	}
	if ($index == $faketagCount && $index == $tagCount) {
		echo '				</span>';
	}
	if ($index == $tagCount && $index != $faketagCount) {
		echo '				</span>';
	}
}
?>
			</td>
			</tr>
            <tr><td class="label">URL</td>
            <td>
            <input name="video_link" type="text" value="http://www.youtube.com/watch?v=<?php echo htmlspecialchars($video['vid']); ?>" class="vidURLField" onClick="javascript:document.urlForm.video_link.focus();document.urlForm.video_link.select();" readonly="true">
            </td>
            </tr>
            <tr><td class="smallLabel">Embed</td>
            <td>
            <input name="embed_code" type="text" value='<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/<?php echo htmlspecialchars($video['vid']); ?>"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/<?php echo htmlspecialchars($video['vid']); ?>" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>' class="vidURLField" onClick="javascript:document.urlForm.embed_code.focus();document.urlForm.embed_code.select();" readonly="true">
            </td></tr>
            </table>
            </form>
        </div>	<!-- end vidFacetsDiv -->
        
		
        
	</div> <!-- end aboutVidDiv -->
	



	<div id="exploreDiv">
	
	<map name="relatedvideosmap">
	<area shape="rect" coords="10,0,64,24" href="javascript:showRelatedVideosContent();" name="&lid=RelatedVideosTab&lpos=Watch">
	<area shape="rect" coords="76,0,222,24" href="javascript:showRelatedUserContent();" name="&lid=UserVideosTab&lpos=Watch">
	<area shape="rect" coords="234,0,290,24" href="javascript:showRelatedPlaylistContent();" name="&lid=PlaylistsTab&lpos=Watch">
	</map>
	<div><img id="exploreMoreTabs" src="/img/btn_exploretab_related_300x34.gif" width="300" height="34" usemap="#relatedvideosmap" alt="ExploreMoreTabs"></div>
	<div id="exploreBody" class="contentBox" style="border-top: 0px;">


		
		<div id="exRelatedDiv" style="display: block;">
			<table class="showingTable"><tr>
	<td class="smallText">Showing 1-<?php echo $relatedvideos->rowCount(); ?> of <?php echo $relatedvideostotal->rowCount(); ?></td>
	<td align="right" class="smallText"><a href="/results?search_type=related&search_query=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>">See All Videos</a></td>
	</tr></table>

				<script language="javascript">	
		var side_imgs_loaded=false;
		function render_full_side()
		{
			if (!side_imgs_loaded)
			{
				
				<?php $imgrelatedvideos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC LIMIT 15 OFFSET 5");
$imgrelatedvideos->execute([$search, $search, $search, $search, $search]); 
$imgrelnum = 4;
foreach($imgrelatedvideos as $imgrelatedvideo) { ?>
					img = document.getElementById("side_img_<?php $imgrelnum = $imgrelnum + 1; echo $imgrelnum; ?>");
					img.src = "/get_still.php?video_id=<?php echo htmlspecialchars($imgrelatedvideo['vid']); ?>";<? } ?>
				
				side_imgs_loaded = true;
			}
		}
		</script>

		<div id="side_results" class="exploreContent" name="side_results" onscroll="render_full_side()" >
		<?php foreach($relatedvideos as $relatedvideo) { $relatedvideo['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?"); $relatedvideo['views']->execute([$relatedvideo['vid']]); $relatedvideo['views'] = $relatedvideo['views']->fetchColumn(); $relvidnum = $relvidnum + 1; ?>
		<div class="vWatchEntry <? if ($video['vid'] == $relatedvideo['vid']) { ?>vNowPlaying<? } ?> ">
                <table><tr>
                <td>
                        <div class="img" style="margin-top:0px;">
                                <a href="/watch?v=<?php echo htmlspecialchars($relatedvideo['vid']); ?>&mode=related&search=" onclick="_hbLink('RelatedVideo','ExploreMore');" rel="nofollow"><img class="vimgSm" <?php if ($relvidnum < 5) { ?>src="/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?>"<? } else { ?>id="side_img_<? echo $relvidnum; ?>" src="/img/pixel.gif"<? } ?> /></a>
                        </div>
			<div id="add_img_<?php echo htmlspecialchars($relatedvideo['vid']); ?>" class="addtoQLRelatedIE">
				<a href="#" onClick="clicked_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>', 1);print_quicklist_video('/get_still.php?video_id=<?php echo htmlspecialchars($relatedvideo['vid']); ?>',document.getElementById('video_title_text_<? echo $relvidnum; ?>_<?php echo $relatedvideo['views']; ?>_<?php echo $relatedvideo['time']; ?>').innerHTML,'<?php echo htmlspecialchars($relatedvideo['username']); ?>','<?php echo htmlspecialchars($relatedvideo['vid']); ?>','<?php echo gmdate("i:s", $relatedvideo['time']); ?>');_hbLink('QuickList+AddTo','Watch');return false;" title="Add Video to QuickList" rel="nofollow"><img id="add_button_<?php echo htmlspecialchars($relatedvideo['vid']); ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo htmlspecialchars($relatedvideo['vid']); ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
                        </div>
               </td>
		<td><div class="title" onclick="_hbLink('RelatedVideo','ExploreMore');"><a href="/watch?v=<?php echo htmlspecialchars($relatedvideo['vid']); ?>&mode=related&search=" id="video_title_text_<? echo $relvidnum; ?>_<?php echo $relatedvideo['views']; ?>_<?php echo $relatedvideo['time']; ?>" rel="nofollow"><?php echo htmlspecialchars($relatedvideo['title']); ?></a><br/>
			<span class="runtime"><?php echo gmdate("i:s", $relatedvideo['time']); ?></span>
			</div>
			<div class="facets">
				<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($relatedvideo['username']); ?>" class="dg" rel="nofollow"><?php echo htmlspecialchars($relatedvideo['username']); ?></a><br/>
				<span class="grayText">Views:</span> <?php echo $relatedvideo['views']; ?>
			</div><? if ($video['vid'] == $relatedvideo['vid']) { ?>
				<div class="smallText">
				<b>&lt;&lt; Now Playing</b>
				</div><? } ?>
			</div></td>
		</tr></table>
		</div> <!-- end vWatchEntry --><? } ?>
	
	</div>

			<table class="showingTable"><tr>
	<td class="smallText">Showing 1-<?php echo $relatedvideos->rowCount(); ?> of <?php echo $relatedvideostotal->rowCount(); ?></td>
	<td align="right" class="smallText"><a href="/results?search_type=related&search_query=<?php echo urlencode(htmlspecialchars($video['tags'])); ?>">See All Videos</a></td>
	</tr></table>
		</div> <!-- end exRelatedDiv -->
		
		
		<div id="exPlaylistDiv"  style="display: none;">
			Loading...
		</div> <!-- end exPlaylistDiv -->

		<div id="exUserDiv"  style="display: none;">
			Loading...
		</div> <!-- end exPlaylistDiv -->
			
	</div> <!-- end exploreBody -->
	
	
	
	</div> <!-- end exploreDiv -->

	
</div> <!-- end aboutExploreDiv -->

<div class="spOffersDiv">
	<h4 class="label">New on YouTube</h4>

        <div class="spOffersEntry">
        Do you know how not to?
        <a href="/contest/hownotto">Enter for a chance to win</a>!
        </div>
        
        <div class="spOffersEntry">
        There&#146;s a new way to play.
        <a href="/profile?user=wii" rel="nofollow">Wii from Nintendo</a>.
        </div>    
        
        <div class="spOffersEntry">
        Real Drama all the time. 
        <a href="/profile?user=TheBadGirlsClub" rel="nofollow">Check out the Bad Girls Club</a>.
        </div>  
 
         <div class="spOffersEntry">
        The Dark Side of Fame. 
        <a href="/profile?user=FXDirt" rel="nofollow">Dirt on FX</a>.
        </div>         
                    
         <div class="spOffersEntry">
        Show Us Your Undeniable Power.
        <a href="/undeniabletv" rel="nofollow">Enter for a chance to win</a> a Panasonic Plasma TV.
        </div>  

</div>

</td>



<td>

<div id="sideAdDiv">
	<div id="dVidsDiv">
	<div class="heading">Director Videos</div>
		<div class="dvidEntry">
			<div><a href="/cthru?2AEN368OoJjJB6qOuxYxqXg3IC1tjnuc7jzd8mGCeGQndd1WrCirx1eEEjoV8Xl4QfEAizzYkdaxVNAgaz4Ez5YIWqfAGWDkhR2aPo4AC6H5rqR2inweS5EYok4ONCnZ9PXQGgWNET1KhSJ0iZn1RIS1Q8vWiMcrcqtVBB_j5SvqpX0_wYt75mdGRtuPtJe1WxBj-JoTpw0=" target="_parent" name="&lid=DV+-+StarsDucks+-+NHLVideo&lpos=Watch-s0" rel="nofollow"><img src="http://sjl-static6.sjl.youtube.com/vi/xi-5Ni8KE1w/2.jpg" class="vimgMd"></a></div>
			<div class="title">
			<b><a href="/cthru?2AEN368OoJjJB6qOuxYxqXg3IC1tjnuc7jzd8mGCeGQndd1WrCirx1eEEjoV8Xl4QfEAizzYkdaxVNAgaz4Ez5YIWqfAGWDkhR2aPo4AC6H5rqR2inweS5EYok4ONCnZ9PXQGgWNET1KhSJ0iZn1RIS1Q8vWiMcrcqtVBB_j5SvqpX0_wYt75mdGRtuPtJe1WxBj-JoTpw0=" target="_parent" name="&lid=DV+-+StarsDucks+-+NHLVideo&lpos=Watch-s0" rel="nofollow">Stars@Ducks</a></b><br>
			<span class="runtime">02:46</span>
			</div>
			<div class="facets">
			<span class="grayText">From:</span> <a href="/user/NHLVideo" class="dg" name="&lid=DirectorChannelLink&lpos=Watch-s0" rel="nofollow">NHLVideo</a>
			</div>
		</div>
		<div class="dvidEntry">
			<div><a href="/cthru?nhpEm1WiGQOMOXSo_CdzgtoFGnWedJ8d29oNJjdf4yiRqJutyA21eAnl5yjGSLHj-In8kcMIayItmuMyw2vTympi1l26bc4HriRJPSGU5qNYTPIFHl1Pit9tyVcalCq97wo96PIc000kaFUl6P_SISQh5gfqdQ7mj229SyNkNsTvsMkfgKwiMZuwy1cYdfj3WQ79A5fuLBY=" target="_parent" name="&lid=DV+-+AvalancheOilers+-+NHLVideo&lpos=Watch-s1" rel="nofollow"><img src="http://sjl-static6.sjl.youtube.com/vi/5JqyyQn4TGs/2.jpg" class="vimgMd"></a></div>
			<div class="title">
			<b><a href="/cthru?nhpEm1WiGQOMOXSo_CdzgtoFGnWedJ8d29oNJjdf4yiRqJutyA21eAnl5yjGSLHj-In8kcMIayItmuMyw2vTympi1l26bc4HriRJPSGU5qNYTPIFHl1Pit9tyVcalCq97wo96PIc000kaFUl6P_SISQh5gfqdQ7mj229SyNkNsTvsMkfgKwiMZuwy1cYdfj3WQ79A5fuLBY=" target="_parent" name="&lid=DV+-+AvalancheOilers+-+NHLVideo&lpos=Watch-s1" rel="nofollow">Avalanche@Oilers</a></b><br>
			<span class="runtime">04:52</span>
			</div>
			<div class="facets">
			<span class="grayText">From:</span> <a href="/user/NHLVideo" class="dg" name="&lid=DirectorChannelLink&lpos=Watch-s1" rel="nofollow">NHLVideo</a>
			</div>
		</div>
		<div class="dvidEntry">
			<div><a href="/cthru?o3cS-tglMojTk20rBhVSKWg3FEyubbp_sk_tGhkeaMRG2TWNB3yypRV73q6-TnkLhqcKoI__qZiWmxdZosxiCuopjATHDqW9dQdlPewgfvfMONv_d790hAiI8oUhVw3hFzQSoXM9ua7BZwFe8redIgfay5b9ltJgqTcAY6TvnuIp4wad_AJ_HudWBR0spEKemM3a7hZlPi4=" target="_parent" name="&lid=DV+-+RiverboatGamblersTrueCrime+-+WMGILG&lpos=Watch-s2" rel="nofollow"><img src="http://sjc-static11.sjc.youtube.com/vi/sr_qS4SDGOQ/2.jpg" class="vimgMd"></a></div>
			<div class="title">
			<b><a href="/cthru?o3cS-tglMojTk20rBhVSKWg3FEyubbp_sk_tGhkeaMRG2TWNB3yypRV73q6-TnkLhqcKoI__qZiWmxdZosxiCuopjATHDqW9dQdlPewgfvfMONv_d790hAiI8oUhVw3hFzQSoXM9ua7BZwFe8redIgfay5b9ltJgqTcAY6TvnuIp4wad_AJ_HudWBR0spEKemM3a7hZlPi4=" target="_parent" name="&lid=DV+-+RiverboatGamblersTrueCrime+-+WMGILG&lpos=Watch-s2" rel="nofollow">Riverboat Gamblers- True Crime</a></b><br>
			<span class="runtime">03:01</span>
			</div>
			<div class="facets">
			<span class="grayText">From:</span> <a href="/user/WMGILG" class="dg" name="&lid=DirectorChannelLink&lpos=Watch-s2" rel="nofollow">WMGILG</a>
			</div>
		</div>
		<div class="dvidEntry">
			<div><a href="/cthru?a5iWiLBzLavn0v6EjMCNXxgOR5xKP5zgs-hcp6BeyWqCtz8eCzBJdx769yd8x1wIznV9-8ltjtcyG_ERePVmJm1Gss1eVZGjh-JzoAjNDSOXPsWZgLU_nNqXCFY7QOEx-nwEenxd98rhv_SfzII6FjcbQPTjMXStCmKxmopVdS8B3ENZrpGh2p8AiBJkf-Oeo-SNodLItjU=" target="_parent" name="&lid=DV+-+RedVsBlueSeesGreen+-+forbes&lpos=Watch-s3" rel="nofollow"><img src="http://sjl-static10.sjl.youtube.com/vi/ZpdlZtVMIuQ/2.jpg" class="vimgMd"></a></div>
			<div class="title">
			<b><a href="/cthru?a5iWiLBzLavn0v6EjMCNXxgOR5xKP5zgs-hcp6BeyWqCtz8eCzBJdx769yd8x1wIznV9-8ltjtcyG_ERePVmJm1Gss1eVZGjh-JzoAjNDSOXPsWZgLU_nNqXCFY7QOEx-nwEenxd98rhv_SfzII6FjcbQPTjMXStCmKxmopVdS8B3ENZrpGh2p8AiBJkf-Oeo-SNodLItjU=" target="_parent" name="&lid=DV+-+RedVsBlueSeesGreen+-+forbes&lpos=Watch-s3" rel="nofollow">Red Vs Blue Sees Green</a></b><br>
			<span class="runtime">02:29</span>
			</div>
			<div class="facets">
			<span class="grayText">From:</span> <a href="/user/forbes" class="dg" name="&lid=DirectorChannelLink&lpos=Watch-s3" rel="nofollow">forbes</a>
			</div>
		</div>
	</div> <!-- end dVidsDiv -->
</div> <!-- end sideAdDiv -->

</td>


</tr></table>

<!-- Quicklist hide if cookie is no -->
<script language="javascript" type="text/javascript">
showQuickList();
</script>

<?php 
require "needed/end.php";
?>