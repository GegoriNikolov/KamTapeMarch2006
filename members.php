<?php
require "needed/start.php";
if(isset($_GET['s']) && in_array($_GET['s'], ["ms", "ra", "mv", "pa", "po"])) {
	$browse_sort = $_GET['s'];
} else {
	$browse_sort = "ms";
}

if(isset($_GET['t']) && in_array($_GET['t'], ["t", "w", "m", "a"])) {
	$time = $_GET['t'];
} else {
	$time = "w";
}

if($time == "t" && $browse_sort !== "mv") {
	$time = "w";
}

if(isset($_GET['g']) && in_array($_GET['g'], ["0", "2", "3", "1", "-1"])) {
	$group = $_GET['g'];
} else {
	$group = "0";
}
$page = isset($_GET['p']) ? intval($_GET['p']) : 1;
$ppv = 20;
$offs = ($page - 1) * $ppv;

if($browse_sort == "ms") {
	if($time == "w") {
		$members = $conn->query(
			"SELECT * FROM subscriptions
			LEFT JOIN users ON users.uid = subscriptions.subto
			WHERE (subscriptions.subtype = 1 AND users.termination = 0) AND subscriptions.added > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY subscriptions.subto
			ORDER BY COUNT(subscriptions.subid) DESC LIMIT $ppv OFFSET $offs"
		);
		/*$members = $conn->query(
			"SELECT * FROM subscriptions
			LEFT JOIN videos ON videos.uid = subscriptions.subto
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (subscriptions.subtype = 1 AND users.termination = 0 AND videos.privacy = 1) AND subscriptions.added > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY users.uid
			ORDER BY COUNT(subscriptions.subid) DESC LIMIT $ppv OFFSET $offs"
		);*/
	} elseif($time == "m") {
		$members = $conn->query(
			"SELECT * FROM subscriptions
			LEFT JOIN users ON users.uid = subscriptions.subto
			WHERE (subscriptions.subtype = 1 AND users.termination = 0) AND subscriptions.added > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY subscriptions.subto
			ORDER BY COUNT(subscriptions.subid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$members = $conn->query(
			"SELECT * FROM subscriptions
			LEFT JOIN users ON users.uid = subscriptions.subto
			WHERE (subscriptions.subtype = 1 AND users.termination = 0) GROUP BY subscriptions.subto
			ORDER BY COUNT(subscriptions.subid) DESC LIMIT $ppv OFFSET $offs"
		);
	} else {
		$members = $conn->query(
			"SELECT * FROM subscriptions
			LEFT JOIN users ON users.uid = subscriptions.subto
			WHERE (subscriptions.subtype = 1 AND users.termination = 0) AND subscriptions.added > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY subscriptions.subto
			ORDER BY COUNT(subscriptions.subid) DESC LIMIT $ppv OFFSET $offs"
		);
	}
} elseif($browse_sort == "ra") {
	//$members = $conn->query("SELECT * FROM users WHERE termination = 0 ORDER BY joined DESC LIMIT $ppv OFFSET $offs
	$members = $conn->query("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE users.termination = 0 AND videos.privacy = 1 GROUP BY users.uid ORDER BY users.joined DESC LIMIT $ppv OFFSET $offs");
} elseif($browse_sort == "mv") {
	if($time == "t") {
		$members = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND views.viewed > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY users.uid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
		$members = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND views.viewed > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY users.uid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
		$members = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND views.viewed > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY users.uid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$members = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) GROUP BY users.uid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	}
}
?>
	<script type="text/javascript" src="/js/browsePageTimeAgo_yts1157352107.js"></script>
	








<div id="sideAd">
	
			
		
			

			
					<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 2;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.members/_default;sz=160x600;kch=6835951170;kbg=FFFFFF;;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.members/_default;sz=160x600;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.members/_default;sz=160x600;ord=123456789?" width="160" height="600" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		

	<br/>
</div> <!-- end sideAd -->



<div id="mainContent">


<div id="sectionHeader" class="channelsColor">
	<div class="my"><a href="/my_profile"><img src="/img/btn_mychannel_104x25.gif" width="104" height="25" border="0" alt="mychannel" /></a></div>
	<div class="name">Channels</div>
	<span class="title"><?php
				switch($browse_sort) {
					case 'ms':
						echo "Most Subscribed";
						break;
					case 'ra':
						echo "Recent";
						break;
					case 'mv':
						echo "Most Viewed";
						break;
					case 'pa':
						echo "Partners";
						break;
				    case 'po':
						echo "Politicians";
						break;
					default:
						echo "Most Subscribed";
				}
				?> <?php
				if($browse_sort !== "pa" && $browse_sort !== "po") {
				switch($group) {
					case '2':
						echo "Comedians";
						break;
					case '3':
						echo "Directors";
						break;
					case '1':
						echo "Musicians";
						break;
					default:
						echo "";
				}
				}
				?> <?php
				if($browse_sort !== "ra" && $browse_sort !== "pa" && $browse_sort !== "po") {
				switch($time) {
					case 't':
						echo "(Today)";
						break;
					case 'w':
						echo "(This Week)";
						break;
					case 'm':
						echo "(This Month)";
						break;
					case 'a':
						echo "(All Time)";
						break;
					default:
						echo "(This Week)";
				}
				}
				?></span>
</div>


<div id="sideNav">
	<div class="navHead channelsColor">Type</div>
	<div class="navBody" style="padding-top: 4px;">
				<div class="navButton" style="font-size: 14px;"><?php if ($group == "0") { ?><span class="label">&raquo; All Channels</span><? } else { ?><b><a href="?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=0" onclick="_hbLink('All Channels','LocalNav');">All Channels</a></b><? } ?></div>
				<div class="navButton" style="font-size: 14px;"><?php if ($group == "2") { ?><span class="label">&raquo; Comedians</span><? } else { ?><b><a href="?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=2" onclick="_hbLink('Comedians','LocalNav');">Comedians</a></b><? } ?></div>
				<div class="navButton" style="font-size: 14px;"><?php if ($group == "3") { ?><span class="label">&raquo; Directors</span><? } else { ?><b><a href="?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=3" onclick="_hbLink('Directors','LocalNav');">Directors</a></b><? } ?></div>
				<div class="navButton" style="font-size: 14px;"><?php if ($group == "1") { ?><span class="label">&raquo; Musicians</span><? } else { ?><b><a href="?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=1" onclick="_hbLink('Musicians','LocalNav');">Musicians</a></b><? } ?></div>
				<div class="navButton" style="font-size: 14px;"><?php if ($browse_sort == "pa") { ?><span class="label">&raquo; Partners</span><? } else { ?><b><a href="?s=pa&t=<?php echo $time; ?>&g=-1" onclick="_hbLink('Partners','LocalNav');">Partners</a></b><? } ?></div>
				<div class="navButton" style="font-size: 14px;"><?php if ($browse_sort == "po") { ?><span class="label">&raquo; Politicains    </span><? } else { ?><b><a href="?s=po&t=<?php echo $time; ?>&g=-1" onclick="_hbLink('Politicains    ','LocalNav');">Politicains    </a></b><? } ?></div>
	</div> <!-- end navBody -->


<?php if ($browse_sort !== "pa" && $browse_sort !== "po") { ?>
	<div class="navHead channelsColor">Browse</div>
	<div class="navBody12">
				<?php echo ($browse_sort == "ms") ? '<span class="label">&raquo; Most Subscribed</span>' : '<a href="?s=ms&t='.$time.'&g='.$group.'">Most Subscribed</a>'; ?><br/>
				<?php echo ($browse_sort == "ra") ? '<span class="label">&raquo; Most Recent</span>' : '<a href="?s=ra&t='.$time.'&g='.$group.'">Most Recent</a>'; ?><br/>
				<?php echo ($browse_sort == "mv") ? '<span class="label">&raquo; Most Viewed</span>' : '<a href="?s=mv&t='.$time.'&g='.$group.'">Most Viewed</a>'; ?><br/>
	</div>
	
	
<?php if ($browse_sort !== "ra") { ?>
		<div class="navHead channelsColor">Time</div>
		<div class="navBody11">
<?php if ($browse_sort == "mv") { ?>
					<?php echo ($time == "t") ? '<span class="label">&raquo; Today</span>' : '<a href="?s='.$browse_sort.'&t=t&g='.$group.'">Today</a>'; ?><br/>
<? } ?>
					<?php echo ($time == "w") ? '<span class="label">&raquo; This Week</span>' : '<a href="?s='.$browse_sort.'&t=w&g='.$group.'">This Week</a>'; ?><br/>
					<?php echo ($time == "m") ? '<span class="label">&raquo; This Month</span>' : '<a href="?s='.$browse_sort.'&t=m&g='.$group.'">This Month</a>'; ?><br/>
					<?php echo ($time == "a") ? '<span class="label">&raquo; All Time</span>' : '<a href="?s='.$browse_sort.'&t=a&g='.$group.'">All Time</a>'; ?><br/>
		</div> <!-- end navBody -->
<? } } ?>
	

</div> <!-- end sideNav -->



<div id="mainContentWithNav" style="padding-top: 5px;">
		


<table cellpadding="0" cellspacing="0" border="0" width="100%">
<?php $i = 0;
foreach($members as $member) {
$i = $i + 1;
if($i == 1) {
echo '		<tr valign="top">';
}
$member['views'] = $conn->prepare(
	"SELECT COUNT(view_id) FROM views
	LEFT JOIN videos ON views.vid = videos.vid
	WHERE videos.uid = ? AND videos.converted = 1 AND videos.privacy = 1"
);
$member['views']->execute([$member['uid']]);
$member['views'] = $member['views']->fetchColumn();
						
$member['subs'] = $conn->prepare("SELECT COUNT(subid) FROM subscriptions WHERE subto = ? AND subtype = 1");
$member['subs']->execute([$member['uid']]);
$member['subs'] = $member['subs']->fetchColumn();

$member['videos'] = $conn->prepare("SELECT COUNT(vid) FROM videos WHERE uid = ? AND privacy = 1");
$member['videos']->execute([$member['uid']]);
$member['videos'] = $member['videos']->fetchColumn();
				
$profile_latest_video = $conn->prepare(
	"SELECT * FROM videos
	WHERE uid = ? AND converted = 1 AND privacy = 1
	GROUP BY vid
	ORDER BY uploaded DESC LIMIT 1"
);
$profile_latest_video->execute([$member['uid']]);
if($profile_latest_video->rowCount() == 0) {
	$profile_latest_video = false;
} else {
	$profile_latest_video = $profile_latest_video->fetch(PDO::FETCH_ASSOC);
}
?>
		<td width="25%">
		<div class="v120vEntry">
			<div class="vstill"><a href="/profile?user=<?php echo htmlspecialchars($member['username']); ?>"><img src="<? if($profile_latest_video) { ?>/get_still.php?video_id=<?php echo htmlspecialchars($profile_latest_video['vid']) ?><? } else { ?>/img/no_videos_140.jpg<? } ?>" class="vimg" alt="<?php echo htmlspecialchars($member['username']); ?>" /></a></div>
			<div class="vtitle">
				<a href="/profile?user=<?php echo htmlspecialchars($member['username']); ?>"><?php echo htmlspecialchars($member['username']); ?></a>
			</div>
			<div class="vfacets">
				<?php if ($browse_sort == "ms") { ?>
				<span class="grayText">Viewed:</span> <?php echo $member['views']; ?><br/>
				<span class="grayText">Subscribers:</span> <?php echo $member['subs']; ?><br/>
				<?php } elseif ($browse_sort == "ra" || $browse_sort == "mv") { ?>
				<span class="grayText">Joined:</span> <SCRIPT Language=JavaScript>document.write(format_time_ago(<?php echo strtotime($member['joined']); ?>000));</SCRIPT><br/>
				<?php if ($browse_sort == "mv") { ?>
				<span class="grayText">Videos:</span> <?php echo number_format($member['videos']); ?><br/>
				<span class="grayText">Video Views:</span> <?php echo number_format($member['views']); ?><br/>
				<? } } ?>
			</div>
		</div>
		</td>
		
	<? if($i == 4) { echo '</tr>'; $i = 0; } } ?>

</table>
	<div class="footerBox">
		
		<div class="pagingDiv">
				Pages:

<?php if ($page != 1) {?>
						<span class="pagerNotCurrent" onClick="location.href='?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=<?php echo $group; ?>&p=<?php echo $page - 1; ?>'" accesskey="p" >Previous</span><? } ?>
				
				
				<?php
	$totalPages = ceil(100 / $ppv);
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<span class="pagerCurrent">' . $i . '</span>';
        } else {
            echo '<span class="pagerNotCurrent" onClick="location.href=\'?s=' . $browse_sort . '&t=' . $time . '&g=' . $group . '&p=' . $i . '\'" >' . $i . '</span>';
        }
    }
    ?>


		
						<? if ($page != $totalPages) { ?><span class="pagerNotCurrent" onClick="location.href='?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&g=<?php echo $group; ?>&p=<?php echo $page + 1; ?>'" >Next</span><? } ?>

		
			</div> <!-- end pagingDiv -->




	</div>

</div> <!-- end mainContentWithNav -->


</div> <!-- end mainContent -->



<?php
require "needed/end.php";
?>