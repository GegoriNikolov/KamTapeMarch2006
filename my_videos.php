<?php 
require "needed/start.php";
if($_SESSION['uid'] == NULL) {
	header("Location: login.php");
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ppv = 10;
$offs = ($page - 1) * $ppv;

$videos = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ?
	ORDER BY videos.uploaded DESC LIMIT $ppv OFFSET $offs"
);
$videos->execute([$session['uid']]);
$related_tags = [];

$rlcnt = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ?
	ORDER BY videos.uploaded DESC"
);
$rlcnt->execute([$session['uid']]);
$vidocount = $rlcnt->rowCount();
unset($rlcnt);

if(isset($_GET['whoops'])) {
alert("You've uploaded too many videos today -- take a break and come back tomorrow.", "error");
}
?>
<table align="center" cellpadding="0" cellspacing="0" border="0">
	<tbody><tr>
		<td><strong>Overview</strong></td>
		<td style="padding: 0px 5px 0px 5px;">|</td>
		<td><a href="sharing.php">Share</a></td>
        <td style="padding: 0px 5px 0px 5px;">|</td>
        <td><a href="my_videos_upload.php">Upload</a></td>
	</tr>
</tbody></table><p>
		

<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td width="100%">
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#CCCCCC">
			<tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td width="100%"><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td>
				<div class="moduleTitleBar">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
				<div class="moduleTitle"><? if($vidocount > 0) { ?><div style="float: right; padding: 1px 5px 0px 0px; font-size: 12px;">Videos <?php if ($offs > 0) { echo htmlspecialchars(trim($offs)); } else { echo "1"; } ?>-<? if($vidocount > $ppv) { $nextynexty = $offs + $ppv; } else {$nextynexty = $vidocount; } echo htmlspecialchars($nextynexty); ?> of <?php echo $vidocount; ?></div><? } ?>
                My Videos</div>
				</div>
		
					</tr>
				</table>
				</div>
				
				<?php if($videos !== false) { ?>
			<?php foreach($videos as $video) { 
        $related_tags = array_merge($related_tags, explode(" ", $video['tags']));
        $video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
	    $video['views']->execute([$video['vid']]);
		$video['views'] = $video['views']->fetchColumn();

        $video['fans'] = $conn->prepare("SELECT COUNT(fid) FROM favorites WHERE vid = ?");
	    $video['fans']->execute([$video['vid']]);
		$video['fans'] = $video['fans']->fetchColumn();
						
		$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
		$video['comments']->execute([$video['vid']]);
		$video['comments'] = $video['comments']->fetchColumn(); 
        ?>
					<div class="moduleEntry"> 
				<table cellpadding="0" cellspacing="0" border="0">
					<tr valign="top">
						<td>
							<a href="watch.php?v=<?php echo htmlspecialchars($video['vid']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" class="moduleEntryThumb" width="120" height="90"></a>
							<table width="130" cellpadding="0" cellspacing="0" border="0">
								<tr align="center">
									<td width="100%">
										<br><form method="get" action="my_videos_edit.php">
											<input type="hidden" value="<?php echo $video['vid']; ?>" name="video_id">
											<input type="submit" value="Edit Video">
										</form>
										<form method="get" action="remove_video.php" onsubmit="return confirm('Are you sure about permamently removing this video from our servers?');">
											<input type="hidden" value="<?php echo $video['vid']; ?>" name="video_id">
											<input type="submit" value="Remove Video">
										</form>
									</td>
								</tr>
							</table>
						</td>
						<td width="100%"><div class="moduleEntryTitle"><a href="index.php?v=<?php echo htmlspecialchars($video['vid']); ?>"><?php echo htmlspecialchars($video['title']); ?></a></div>
							<div class="moduleEntryDescription"><?php echo htmlspecialchars($video['description']); ?></div>
							<div class="moduleEntryTags">
							Tags // <?php
						foreach(explode(" ", $video['tags']) as $tag) {
							echo ' <a href="results.php?search='.htmlspecialchars($tag).'">'.htmlspecialchars($tag).'</a> :';
						}
						?> 							</div>
                            <div class="moduleEntryDetails">Recorded: <?php if ($video['recorddate'] != NULL) { echo date("F j, Y", strtotime($video['recorddate'])); } ?> | Location: <?php echo htmlspecialchars($video['address']); ?> <?php if ($video['country'] != "AG") { echo htmlspecialchars($video['addrcountry']); }?></div>
							<div class="moduleEntryDetails">Added: <?php echo retroDate($video['uploaded']); ?></div>
							<div class="moduleEntryDetails">Runtime: <?php echo gmdate("i:s", $video['time']); ?> | Views: <?php echo $video['views']; ?> | Comments: <?php echo $video['comments']; ?> | Fans: <?php echo $video['fans']; ?></div>
                            <hr style="border: 0; border-bottom: 1px dashed #999999; margin: 1em 0;">
							<div class="moduleEntryDetails">File: <?php echo (!empty($video['file'])) ? htmlspecialchars($video['file']) : "undefined"; ?></div>
                            <div class="moduleEntryDetails">Broadcast: <?php echo ($video['privacy'] == 1) ? '<span style="color: #3e7335; font-weight: bold;">Public Video</span>' : '<span style="color: #d72d11; font-weight: bold;">Private Video</span>'; ?></div>
							<div class="moduleEntryDetails">Status: <?php echo ($video['converted'] == 1) ? "Live!" : "Processing..."; ?></div>
							<div class="moduleEntryDetails">
								<form name="linkForm_<?php echo htmlspecialchars($video['vid']); ?>" id="linkForm_<?php echo htmlspecialchars($video['vid']); ?>">
									<input name="video_link" type="text" onClick="document.linkForm_<?php echo htmlspecialchars($video['vid']); ?>.video_link.focus();document.linkForm_<?php echo htmlspecialchars($video['vid']); ?>.video_link.select();" value="http://www.kamtape.com/?v=<?php echo htmlspecialchars($video['vid']); ?>" size="50" readonly="true" style="font-size: 10px; text-align: center;">
									<br>Share this video with friends! Copy and paste this link above to an email or website.
								</form>
							</td>
						</tr>
					</tbody></table>
					</div><? } } ?>

                    <!-- begin paging -->
				<?php if($vidocount > $ppv) { ?><div style="font-size: 13px; font-weight: bold; color: #444; text-align: right; padding: 5px 0px 5px 0px;">Browse Pages:
				
					<?php
    $totalPages = ceil($vidocount / $ppv);
    if (empty($_GET['page'])) { $_GET['page'] = 1; }
    $pagesPerSet = 10; // Set the number of pages per group
    $startPage = floor(($page - 1) / $pagesPerSet) * $pagesPerSet + 1;
    $endPage = min($startPage + $pagesPerSet - 1, $totalPages); ?>
    <?php if ($startPage < $totalPages && $page !== 1) { ?>
    <a href="my_videos.php?page=<?php echo $_GET['page'] - 1; ?>"> < Previous</a>
    <?php } ?>

    <?php 
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo '<span style="color: #444; background-color: #FFFFFF; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;">' . $i . '</span>';
        } else {
            echo '<span style="background-color: #CCC; padding: 1px 4px 1px 4px; border: 1px solid #999; margin-right: 5px;"><a href="my_videos.php?page=' . $i . '">' . $i . '</a></span>';
        }
    }
    ?>
    <!-- Add "Next" link if there are more pages -->
    <?php if ($endPage < $totalPages) { ?>
            <a href="my_videos.php?page=<?php echo $_GET['page'] + 1; ?>">Next ></a>
    <?php } ?>
</div>
<?php } ?>
				<!-- end paging -->
				
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</table>
		</td>
		
		<td width="15"><img src="img/pixel.gif" width="15" height="1"></td>
		<td width="160">
        <table width="180" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#FFEEBB">
			<tbody><tr>
				<td><img src="img/box_login_tl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_tr.gif" width="5" height="5"></td>
			</tr>
			<tr>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
				<td width="170">
		
				                <div style="font-size: 16px; font-weight: bold; text-align: center; padding: 5px 5px 10px 5px;"><a href="my_friends.php">Share your videos with friends!</a></div>
                				
								
				</td>
				<td><img src="img/pixel.gif" width="5" height="1"></td>
			</tr>
			<tr>
				<td><img src="img/box_login_bl.gif" width="5" height="5"></td>
				<td><img src="img/pixel.gif" width="1" height="5"></td>
				<td><img src="img/box_login_br.gif" width="5" height="5"></td>
			</tr>
		</tbody></table><br>
		<div style="font-weight: bold; margin-bottom: 3px; width: 160px;">My Tags:</div>
			<?php $related_tags = array_unique($related_tags); ?>
			<?php foreach($related_tags as $tag) { ?>
			<div style="padding: 0px 0px 5px 0px; color: #999;">&#187; <a href="results.php?search=<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars($tag); ?></a></div>
			<?php } ?>
		</td>
		
	</tr>
</table>

		</div> 
<?php 
require "needed/end.php";
?>