<?php 
require "needed/start.php";
// todo: implement a way to retrieve watched videos from logged out users
if ($_SESSION['uid'] != NULL) {
$p_views = $conn->prepare(
	"SELECT * FROM views
	LEFT JOIN videos ON views.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE (views.uid = ? AND videos.converted = 1) AND EXISTS (SELECT 1 FROM videos WHERE videos.vid = views.vid)
	GROUP BY views.vid, views.uid
	ORDER BY views.viewed DESC LIMIT 20" // i don't really know if there was a limit or if this limit even applied to registered users but
);
$p_views->execute([$session['uid']]);
}
$recwatchnum = 0;
?>
<div id="sideContent">
	<!-- google_ad_section_start -->
	
			
		
			

			
					<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 2;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.videos/recentlywatched;sz=160x600;kch=6465244539;kbg=FFFFFF;;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.videos/recentlywatched;sz=160x600;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.videos/recentlywatched;sz=160x600;ord=123456789?" width="160" height="600" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		
		
</div>


<div id="mainContent">
	<h1>Viewing History</h1>
	<p class="largeText">
	These are your recently watched videos. We respect your privacy, and do not share this information with anyone. You can clear your history by clicking the 'Clear Viewing History' link at bottom.
	</p>
		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content">	<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
		<td width="30%">
		</td>
		<td width="35%" align="center" class="smallText">
		</td>
		<td width="35%" align="right"><a href="/watch_queue?all"><span class="normalText"><b>See QuickList</b></span></a> &nbsp;|&nbsp; <span class="label"> <?php if ($p_views == NULL) { ?>0<?php } else { ?><?php echo $p_views->rowCount(); ?><? } ?> Videos</span></td>
	</tr>	
	</table>
</div>
	</div>
	
	<div class="contentBox eeeBG"> 
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<?php if ($p_views == NULL) { ?>
		<tr><td>
			<div style="margin: 8px 0px; text-align: center; font-weight: bold;">
			There are no videos in your viewing history.
			</div>
		</td></tr>
		<?php } else { ?>
				<tr valign="top">
		<?php foreach($p_views as $p_view) { $p_view['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?"); $p_view['views']->execute([$p_view['vid']]); $p_view['views'] = $p_view['views']->fetchColumn(); ?>
		<?php if ($recwatchnum == 5) { $recwatchnum = 0; ?>
				<tr valign="top">
				<? } ?><?php $recwatchnum = $recwatchnum + 1; ?>
		<td width="20%">
				<div id="v120vEntry_<?php echo htmlspecialchars($p_view['vid']); ?>" class="v120vEntry">
			<script type="text/javascript">
                if (navigator.appName.indexOf('Microsoft') != -1) {
                	document.write('<div id="add_img_<?php echo htmlspecialchars($p_view['vid']); ?>" class="addtoQLIE">');
                }
                else {
                	document.write('<div id="add_img_<?php echo htmlspecialchars($p_view['vid']); ?>" class="addtoQL">');
                }
			</script>
			<a href="#" onClick="clicked_add_icon('<?php echo htmlspecialchars($p_view['vid']); ?>', 0);_hbLink('QuickList+AddTo','VidVert');return false;" title="Add Video to QuickList"><img id="add_button_<?php echo htmlspecialchars($p_view['vid']); ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo htmlspecialchars($p_view['vid']); ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo htmlspecialchars($p_view['vid']); ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
			<script type="text/javascript">document.write('</div>');</script>
			<div style="margin-top:-20px;">
		<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($p_view['vid']); ?>&feature=RecentlyWatched&page=1&t=t&f=b" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $p_view['title']); ?>','VidVert');"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($p_view['vid']); ?>" class=" vimg " alt="video" /></a></div>
		<div class="vtitle">
			<a href="/watch?v=<?php echo htmlspecialchars($p_view['vid']); ?>&feature=RecentlyWatched&page=1&t=t&f=b" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $p_view['title']); ?>','VidVert');"><?php echo htmlspecialchars($p_view['title']); ?></a><br/>
			<span class="runtime"><?php echo gmdate("i:s", $p_view['time']); ?></span>
		</div>

		<div class="vfacets">
			<span class="grayText">Added:</span> <?php echo timeAgo($p_view['uploaded']); ?><br/>
			<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($p_view['username']); ?>"><?php echo htmlspecialchars($p_view['username']); ?></a><br/>
			<span class="grayText">Views:</span> <?php echo number_format($p_view['views']); ?><br/>
		</div>
		<?php if (getRatingCount($p_view['vid']) != 0) { ?>
						<nobr>
			<? grabRatings($pick['vid'], "SM", 'class="rating"'); ?>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($p_view['vid'])); ?> ratings</div>
		<? } ?>
	



			</div>
	</div> <!-- end vEntry -->

		</td>
		<?php if ($recwatchnum == 5) { ?>

	</tr>
		<? } ?><? } ?><? } ?>
	</table>

	
		<div style="text-align: right;">
		<a href="/clear_watch_history">Clear Viewing History</a>
		</div>
	</div> <!-- end contentBox -->
	
	
</div> <!-- end mainContent -->

<?php 
require "needed/end.php";
?>