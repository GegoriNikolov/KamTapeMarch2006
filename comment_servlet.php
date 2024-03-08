<?php
require "needed/start.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["form_id"] == "comment_formmain_comment" || $_POST["form_id"] == "comment_formmain_comment2") {
ob_get_clean();
// Make sure the user is logged in.
if($_SESSION['uid'] == NULL) {
	echo "LOGIN " . htmlspecialchars($_POST["form_id"]) . "";
	die();
}
// Make sure variables are set
if(!isset($_POST['video_id']) || !isset($_POST['comment'])) {
	die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT vid FROM videos WHERE vid = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_POST['video_id']
]);

if($video_exists->rowCount() == 0) {
	die();
}

// Check if the user has already commented on this video within the past 5 minutes.
$comment_exists = $conn->prepare("SELECT cid FROM comments WHERE uid = :uid AND vidon = :video_id AND post_date > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
$comment_exists->execute([
	":uid" => $session['uid'],
	":video_id" => $_POST['video_id']
]);

if($comment_exists->rowCount() > 4) {
	die();
}


// Post that comment!
$post_comment = $conn->prepare("INSERT INTO comments (cid, vidon, uid, body) VALUES (:comment_id, :video_id, :uid, :body)");
$post_comment->execute([
	":comment_id" => generateId(),
	":video_id" => $_POST['video_id'],
	":uid" => $session['uid'],
	":body" => trim($_POST['comment'])
]);
echo "OK " . htmlspecialchars($_POST["form_id"]) . "";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['reply_parent_id'])) {
ob_get_clean();
// Make sure the user is logged in.
if($_SESSION['uid'] == NULL) {
	echo "LOGIN " . htmlspecialchars($_POST["form_id"]) . "";
	die();
}
// Make sure variables are set
if(!isset($_POST['video_id']) || !isset($_POST['comment'])) {
	die();
}

// Check if the video in question exists.
$video_exists = $conn->prepare("SELECT vid FROM videos WHERE vid = :video_id AND converted = 1");
$video_exists->execute([
	":video_id" => $_POST['video_id']
]);

if($video_exists->rowCount() == 0) {
	die();
}

// Check if the comment in question exists.
$parentcomment_exists = $conn->prepare("SELECT cid FROM comments WHERE cid = :comment_id");
$parentcomment_exists->execute([
	":comment_id" => $_POST['reply_parent_id']
]);

if($parentcomment_exists->rowCount() == 0) {
	die();
}

// Check if the comment in question is a master comment.
$master_comment = $conn->prepare("SELECT master_comment FROM comments WHERE cid = :comment_id");
$master_comment->execute([
	":comment_id" => $_POST['reply_parent_id']
]);
$master_comment = $master_comment->fetchColumn();

if(empty($master_comment)) {
	$master_comment = $_POST['reply_parent_id'];
}

// Check if the user has already commented on this video within the past 5 minutes.
$comment_exists = $conn->prepare("SELECT cid FROM comments WHERE uid = :uid AND vidon = :video_id AND post_date > DATE_SUB(NOW(), INTERVAL 10 MINUTE)");
$comment_exists->execute([
	":uid" => $session['uid'],
	":video_id" => $_POST['video_id']
]);

if($comment_exists->rowCount() > 4) {
	die();
}


// Post that comment!
$post_comment = $conn->prepare("INSERT INTO comments (cid, vidon, uid, body, is_reply, reply_to, master_comment) VALUES (:comment_id, :video_id, :uid, :body, :is_reply, :reply_to, :master_comment)");
$post_comment->execute([
	":comment_id" => generateId(),
	":video_id" => $_POST['video_id'],
	":uid" => $session['uid'],
	":body" => trim($_POST['comment']),
	":is_reply" => 1,
	":reply_to" => $_POST['reply_parent_id'],
	":master_comment" => $master_comment
]);
echo "OK " . htmlspecialchars($_POST["form_id"]) . "";
}

if(isset($_GET["all_comments"])) {
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$_GET['v']]);

if($video->rowCount() == 0) {
	header("Location: index.php");
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$comments = $conn->prepare("SELECT * FROM comments LEFT JOIN users ON users.uid = comments.uid WHERE vidon = ? AND users.termination = 0 AND is_reply = 0 ORDER BY post_date ASC");
$comments->execute([$video['vid']]);
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);
$views = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE vid = ?");
$views->execute([$video['vid']]);
$video['views'] = $views->fetchColumn();
$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
				$video['comments']->execute([$video['vid']]);
				$video['comments'] = $video['comments']->fetchColumn();
?>
	<script type="text/javascript" src="/js/components_yts1157352107.js"></script>
	<script type="text/javascript" src="/js/AJAX_yts1161839869.js"></script>
	<script type="text/javascript" src="/js/ui_yts1164777409.js"></script>
	<script type="text/javascript" src="/js/comments_yts1163746047.js"></script>
	
	<script language="javascript" type="text/javascript">
		function CheckLogin() {
			<?php if($_SESSION['uid'] == NULL) { ?>
				return false;
				<?php } else { ?>
				return true;<? } ?>
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



	
	</script>	

	<link href="/css/watch.css" type="text/css" rel="stylesheet">


	
	
	
	
	<div> 
		<div style="float: left; padding-right: 10px;">
			<a href="<?php if (isset($_GET["fromurl"])) { echo htmlspecialchars($_GET["fromurl"]); } else { ?>/watch?v=<?php echo htmlspecialchars($_GET['v']); ?><? } ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($_GET['v']); ?>&still_id=1" class="vimg90" /></a>&nbsp;
			<a href="<?php if (isset($_GET["fromurl"])) { echo htmlspecialchars($_GET["fromurl"]); } else { ?>/watch?v=<?php echo htmlspecialchars($_GET['v']); ?><? } ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($_GET['v']); ?>&still_id=2" class="vimg90" /></a>&nbsp;
			<a href="<?php if (isset($_GET["fromurl"])) { echo htmlspecialchars($_GET["fromurl"]); } else { ?>/watch?v=<?php echo htmlspecialchars($_GET['v']); ?><? } ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($_GET['v']); ?>&still_id=3" class="vimg90" /></a>
		</div>
		<div class="vtitle">
			<span class="xlargeText"><a href="<?php if (isset($_GET["fromurl"])) { echo htmlspecialchars($_GET["fromurl"]); } else { ?>/watch?v=<?php echo htmlspecialchars($_GET['v']); ?><? } ?>"><?php echo htmlspecialchars($video['title']); ?></a></span>
			<div class="runtime"><?php echo gmdate("i:s", $video['time']); ?></div>
		</div>
		<div class="vfacets">
			<span class="grayText">Added:</span>
			<?php echo timeAgo($video['uploaded']); ?><br/>
			<span class="grayText">From:</span>
			<a href="/profile?user=<?php echo htmlspecialchars($uploader['username']); ?>"><?php echo htmlspecialchars($uploader['username']); ?></a><br/>
			<span class="grayText">Views:</span>
			<?php echo number_format($video['views']); ?><br/>
		</div>
	</div>
	<div style="clear: both">
		<div class="standaloneComments">
			<div id="recent_comments">
				<table>
					<tr>
						<td width="100%"><h2 class="commentHeading">All Comments (<?php echo $video['comments']; ?> total)</h2></td>
						<td align="right" nowrap>		<div id="reply_main_comment2" class="commentHeading">
			<a href="#" class="eLink" onclick="showCommentReplyForm('main_comment2', '', false); return false;">Post a new comment</a>
		</div>
</td>
					</tr>
					<tr>
						<td></td>
					</tr>
				</table>
			</div>
			<br/>
		</div>
		<div style="clear: both" class="standaloneComments">
			<div id="div_main_comment2"></div>
	
	
	
		
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

		

		</div><?php if(!isset($session)){ ?>
				<h2 class="commentHeading">Would you like to Comment?</h2>
				<div style="margin-top: 8px;">
				<a href="/signup">Join YouTube</a> for a free account, or
				<a href="/signup_login">Login</a> if you are already a member.
				</div>
				<!--
				Join YouTube for free to comment on this video.
				Already a YouTube member? Login to comment.
				--><? } ?>
	</div>
<?php
require "needed/end.php";
}
?>