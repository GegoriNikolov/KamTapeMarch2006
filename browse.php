<?php
require "needed/start.php";
// It's just old code made compatible with kamtape because i was tired and lazy :P
// If it doesn't work too well development wise I'll rewrite it later
if(isset($_GET['s']) && in_array($_GET['s'], ["mr", "mp", "md", "mf", "r", "rf", "tr", "mrd"])) {
	$browse_sort = $_GET['s'];
} else {
	$browse_sort = "mr";
}

if(isset($_GET['t']) && in_array($_GET['t'], ["t", "w", "m", "a"])) {
	$time = $_GET['t'];
} else {
	$time = "t";
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ppv = 20;
$offs = ($page - 1) * $ppv;

if($browse_sort == "rf") {
	$videos = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) ORDER BY picks.featured DESC LIMIT $ppv OFFSET $offs");
} elseif($browse_sort == "mr") {
	$videos = $conn->query("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) ORDER BY uploaded DESC LIMIT $ppv OFFSET $offs");
} elseif($browse_sort == "mp") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY views.vid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY views.vid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY views.vid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0 GROUP BY views.vid
			ORDER BY COUNT(views.view_id) DESC LIMIT $ppv OFFSET $offs"
		);
	}
} elseif($browse_sort == "tr") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM ratings 
			LEFT JOIN videos ON videos.vid = ratings.video
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM ratings
			LEFT JOIN videos ON videos.vid = ratings.video
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) GROUP BY ratings.video
			ORDER BY AVG(ratings.rating) DESC LIMIT $ppv OFFSET $offs"
		);
	}    
} elseif($browse_sort == "md") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.vid = comments.vidon
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY comments.vidon
			ORDER BY COUNT(comments.cid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.vid = comments.vidon
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY comments.vidon
			ORDER BY COUNT(comments.cid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.vid = comments.vidon
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY comments.vidon
			ORDER BY COUNT(comments.cid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM comments
			LEFT JOIN videos ON videos.vid = comments.vidon
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) GROUP BY comments.vidon
			ORDER BY COUNT(comments.cid) DESC LIMIT $ppv OFFSET $offs"
		);
	}
} elseif($browse_sort == "mf") {
	if($time == "t") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.privacy = 1  AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
		$videos = $conn->query(
			"SELECT * FROM favorites
			LEFT JOIN videos ON videos.vid = favorites.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) GROUP BY favorites.vid
			ORDER BY COUNT(favorites.fid) DESC LIMIT $ppv OFFSET $offs"
		);
	}
} elseif($browse_sort == "r") {
	$videos = $conn->query("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) ORDER BY RAND() DESC LIMIT $ppv OFFSET $offs");
} elseif($browse_sort == "mrd") {
	if($time == "t") {
	    $videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE ((videos.converted = 1 AND videos.privacy = 1) AND (users.termination = 0 AND views.referer NOT LIKE '%kamtape.com%')) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 DAY) GROUP BY views.vid
			ORDER BY COUNT(views.referer) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "w") {
	    $videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE ((videos.converted = 1 AND videos.privacy = 1) AND (users.termination = 0 AND views.referer NOT LIKE '%kamtape.com%')) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 WEEK) GROUP BY views.vid
			ORDER BY COUNT(views.referer) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "m") {
	    $videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE ((videos.converted = 1 AND videos.privacy = 1) AND (users.termination = 0 AND views.referer NOT LIKE '%kamtape.com%')) AND videos.uploaded > DATE_SUB(NOW(), INTERVAL 1 MONTH) GROUP BY views.vid
			ORDER BY COUNT(views.referer) DESC LIMIT $ppv OFFSET $offs"
		);
	} elseif($time == "a") {
	    $videos = $conn->query(
			"SELECT * FROM views
			LEFT JOIN videos ON videos.vid = views.vid
			LEFT JOIN users ON users.uid = videos.uid
			WHERE ((videos.converted = 1 AND videos.privacy = 1) AND (users.termination = 0 AND views.referer NOT LIKE '%kamtape.com%')) GROUP BY views.vid
			ORDER BY COUNT(views.referer) DESC LIMIT $ppv OFFSET $offs"
		);
	}
}
?>
<script type="text/javascript" src="/js/browsePageTimeAgo_yts1157352107.js"></script>
<div id="mainContent">

<div id="sectionHeader" class="videosColor">
	<div class="my"><a href="/my_videos"><img src="/img/btn_myvideo_104x25.gif" width="104" height="25" border="0" alt="myvideos" /></a></div>
	<div class="name">Videos</div>
	<span class="title">
							<?php
				switch($browse_sort) {
					case 'mp':
						echo "Most Viewed";
						break;
					case 'md':
						echo "Most Discussed";
						break;
					case 'mf':
						echo "Top Favorites";
						break;
					case 'r':
						echo "Random";
						break;
				    case 'rf':
						echo "Recently Featured";
						break;
				    case 'tr':
						echo "Top Rated";
						break;
				    case 'mrd':
						echo "Most Linked";
						break;
					default:
						echo "Recently Added";
				}
				?> <?php
				if($browse_sort !== "mr" && $browse_sort !== "r" && $browse_sort !== "rf") {
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
						echo "(Today)";
				}
				}
				?> </span>
</div>



<div id="sideNav">
		<div class="navHead videosColor">Browse</div>
		<div class="navBody12">
			
				<?php echo ($browse_sort == "mr") ? '<span class="label">&raquo; Most Recent</span>' : '<a href="?s=mr&t='.$time.'&c=0&l=">Most Recent</a>'; ?><br/>
			
				<?php echo ($browse_sort == "mp") ? '<span class="label">&raquo; Most Viewed</span>' : '<a href="?s=mp&t='.$time.'&c=0&l=">Most Viewed</a>'; ?><br/>
			
				<?php echo ($browse_sort == "tr") ? '<span class="label">&raquo; Top Rated</span>' : '<a href="?s=tr&t='.$time.'&c=0&l=">Top Rated</a>'; ?><br/>
			
				<?php echo ($browse_sort == "md") ? '<span class="label">&raquo; Most Discussed</span>' : '<a href="?s=md&t='.$time.'&c=0&l=">Most Discussed</a>'; ?><br/>
			
				<?php echo ($browse_sort == "mf") ? '<span class="label">&raquo; Top Favorites</span>' : '<a href="?s=mf&t='.$time.'&c=0&l=">Top Favorites</a>'; ?><br/>
			
				<?php echo ($browse_sort == "mrd") ? '<span class="label">&raquo; Most Linked</span>' : '<a href="?s=mrd&t='.$time.'&c=0&l=">Most Linked</a>'; ?><br/>
			
				<?php echo ($browse_sort == "rf") ? '<span class="label">&raquo; Recently Featured</span>' : '<a href="?s=rf&t='.$time.'&c=0&l=">Recently Featured</a>'; ?><br/>
		</div>
		
                        <?php // if($browse_sort !== "mr" && $browse_sort !== "r" && $browse_sort !== "rf") {
                if($browse_sort !== "mr" && $browse_sort !== "r" && $browse_sort !== "rf") { ?>
				<div class="navHead videosColor">Time</div>
				<div class="navBody11">
						<?php echo ($time == "t") ? '<span class="label">&raquo; Today</span>' : '<a href="?s='.$browse_sort.'&t=t&c=0&l=">Today</a>'; ?><br/>
						<?php echo ($time == "w") ? '<span class="label">&raquo; This Week</span>' : '<a href="?s='.$browse_sort.'&t=w&c=0&l=">This Week</a>'; ?><br/>
						<?php echo ($time == "m") ? '<span class="label">&raquo; This Month</span>' : '<a href="?s='.$browse_sort.'&t=m&c=0&l=">This Month</a>'; ?><br/>
						<?php echo ($time == "a") ? '<span class="label">&raquo; All Time</span>' : '<a href="?s='.$browse_sort.'&t=a&c=0&l=">All Time</a>'; ?><br/>
				</div>
				<?php } ?>
			
				<div class="navHead videosColor">Category</div>
				<div class="navBody11">
					<div class="label">&raquo; All</a></div>
					<a href="?s=mp&t=t&c=1&l=">Arts &amp; Animation</a><br/>
					<a href="?s=mp&t=t&c=2&l=">Autos &amp; Vehicles</a><br/>
					<a href="?s=mp&t=t&c=23&l=">Comedy</a><br/>
					<a href="?s=mp&t=t&c=24&l=">Entertainment</a><br/>
					<a href="?s=mp&t=t&c=10&l=">Music</a><br/>
					<a href="?s=mp&t=t&c=25&l=">News &amp; Blogs</a><br/>
					<a href="?s=mp&t=t&c=22&l=">People</a><br/>
					<a href="?s=mp&t=t&c=15&l=">Pets &amp; Animals</a><br/>
					<a href="?s=mp&t=t&c=26&l=">Science &amp; Technology</a><br/>
					<a href="?s=mp&t=t&c=17&l=">Sports</a><br/>
					<a href="?s=mp&t=t&c=19&l=">Travel &amp; Places</a><br/>
					<a href="?s=mp&t=t&c=20&l=">Video Games</a><br/>
				</div>
			
				<?php if($browse_sort !== "mrd" && $browse_sort !== "r" && $browse_sort !== "rf") { ?>
				<div class="navHead videosColor">Language</div>
				<div class="navBody11">
					<div class="label">&raquo; All</a></div>
					<a href="?s=mp&t=t&c=0&l=EN">English</a><br/>
					<a href="?s=mp&t=t&c=0&l=ES">Spanish</a><br/>
					<a href="?s=mp&t=t&c=0&l=JP">Japanese</a><br/>
					<a href="?s=mp&t=t&c=0&l=DE">German</a><br/>
					<a href="?s=mp&t=t&c=0&l=CN">Chinese</a><br/>
					<a href="?s=mp&t=t&c=0&l=FR">French</a><br/>
				</div>
				<?php } ?>
</div> <!-- end sideNav -->



<div id="mainContentWithNav">
		
	
	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<?php $i = 0;
        foreach($videos as $video) { ?>
				<?php			
                $i = $i + 1;
						if($i == 1) {
							echo '<tr valign="top">';
						}
				$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
				$video['views']->execute([$video['vid']]);
				$video['views'] = $video['views']->fetchColumn();
						
				$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
				$video['comments']->execute([$video['vid']]);
				$video['comments'] = $video['comments']->fetchColumn();
				?><td width="25%">
				<div id="v120vEntry_<?php echo $video['vid']; ?>" class="v120vEntry">
			<script type="text/javascript">
                if (navigator.appName.indexOf('Microsoft') != -1) {
                	document.write('<div id="add_img_<?php echo $video['vid']; ?>" class="addtoQLIE">');
                }
                else {
                	document.write('<div id="add_img_<?php echo $video['vid']; ?>" class="addtoQL">');
                }
			</script>
			<a href="#" onClick="clicked_add_icon('<?php echo $video['vid']; ?>', 0);_hbLink('QuickList+AddTo','VidVert');return false;" title="Add Video to QuickList"><img id="add_button_<?php echo $video['vid']; ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo $video['vid']; ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo $video['vid']; ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
			<script type="text/javascript">document.write('</div>');</script>
			<div style="margin-top:-20px;">
		<div class="vstill"><a href="watch.php?v=<?php echo $video['vid']; ?>" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $video['title']); ?>','VidVert');"><img src="get_still.php?video_id=<?php echo $video['vid']; ?>" class=" vimg " alt="<?php echo $video['vid']; ?>" /></a></div>
		<div class="vtitle">
			<a href="watch.php?v=<?php echo $video['vid']; ?>" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $video['title']); ?>','VidVert');"><?php echo htmlspecialchars($video['title']); ?></a><br/>
			<span class="runtime"><?php echo gmdate("i:s", $video['time']); ?></span>
		</div>
		
		<div class="vfacets">
			<span class="grayText">Added:</span> <script type="text/javascript">document.write(format_time_ago(<?php echo strtotime($video['uploaded']); ?>000));</script><br/>
			<span class="grayText">From:</span> <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo shorten($video['username'], 13); ?></a><br/>
			<span class="grayText">Views:</span> <?php echo number_format($video['views']); ?><br/>
			<? if ($browse_sort == "md") { ?>
			<span class="grayText">Comments:</span> <?php echo number_format($video['comments']); ?><br/>
			<? } elseif ($browse_sort == "mrd") { 
			$video['links'] = $conn->prepare("SELECT COUNT(referer) FROM views WHERE vid = ? AND referer NOT LIKE '%kamtape.com%' GROUP BY referer");
			$video['links']->execute([$video['vid']]);
			$video['links'] = $video['links']->fetchColumn();
			?>
			<span class="grayText">Links:</span> <?php echo number_format($video['links']); ?><br/>
			<? } ?>
		</div>
		<?php if (getRatingCount($video['vid']) != 0) { ?>
						<nobr>
			<? grabRatings($video['vid'], "SM", 'class="rating"'); ?>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($video['vid'])); ?> ratings</div>
		<? } ?>
	



			</div>
	</div> <!-- end vEntry -->
	
		</td>
		
	<? if($i == 4) { echo '</tr>'; $i = 0; } } ?>
	
	</table>
		<div class="footerBox">
			
		<div class="pagingDiv">
				Pages:
				
<?php if ($page != 1) {?>
						<span class="pagerNotCurrent" onClick="location.href='?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&c=0&l=&page=<?php echo $page - 1; ?>'" accesskey="p" >Previous</span><? } ?>
				
				
				<?php
	if ($browse_sort == "mr") {
	if ($page >= 11) {
    $totalPages = ceil(300 / $ppv);
	} elseif ($page > 5) {
    $totalPages = ceil($page + 4);
	} else {
    $totalPages = ceil(180 / $ppv);
	}
	if ($page >= 11) {
		$pagething = 7;
	} elseif ($page > 5) {
		$pagething = $page - 4;
	} else {
		$pagething = 1;
	}
    for ($i = $pagething; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<span class="pagerCurrent">' . $i . '</span>';
        } else {
            echo '<span class="pagerNotCurrent" onClick="location.href=\'?s=' . $browse_sort . '&t=' . $time . '&c=0&l=&page=' . $i . '\'" >' . $i . '</span>';
        }
    }
	} else {
    $totalPages = ceil(100 / $ppv);
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<span class="pagerCurrent">' . $i . '</span>';
        } else {
            echo '<span class="pagerNotCurrent" onClick="location.href=\'?s=' . $browse_sort . '&t=' . $time . '&c=0&l=&page=' . $i . '\'" >' . $i . '</span>';
        }
    }
	}
    ?>
<?php if ($browse_sort == "mr") { ?>


		
			...<? } ?><? if ($page != $totalPages) { ?>
                			<span class="pagerNotCurrent" onClick="location.href='?s=<?php echo $browse_sort; ?>&t=<?php echo $time; ?>&c=0&l=&page=<?php echo $page + 1; ?>'" >Next</span><? } ?>
							
			</div> <!-- end pagingDiv -->




		</div>
	


</div>


</div> <!-- end mainContent -->
<?php
require "needed/end.php";
?>