<?php 
if(isset($_GET['v'])) {
	header("Location: watch.php?v=".$_GET['v'], true, 303);
	die();
}
require "needed/start.php";

$tags_strings = $conn->query("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE converted = 1 AND privacy = 1 AND users.termination = 0 ORDER BY uploaded DESC LIMIT 100");
$tag_list = [];
foreach($tags_strings as $result) $tag_list = array_merge($tag_list, explode(" ", $result['tags']));
$tag_list = array_slice(array_count_values($tag_list), 0, 50);
$featured = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 ORDER BY picks.featured DESC LIMIT 10");

$rec_viewed = $conn->query("SELECT * FROM views LEFT JOIN videos ON videos.vid = views.vid LEFT JOIN users ON users.uid = videos.uid AND videos.privacy = 1 AND videos.converted = 1 AND users.termination = 0 ORDER BY views.viewed DESC LIMIT 4");
if ($_SESSION['uid'] != NULL) {
// ahhh!!!! logged in section
$y_views = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE uid = ?");
$y_views->execute([$session['uid']]);
$y_views = $y_views->fetchColumn();
// PHP UP YOURS
$vids = $conn->prepare(
	"SELECT * FROM videos
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1
	ORDER BY videos.uploaded DESC"
);
$vids->execute([$session['uid']]);

$fans = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1
	ORDER BY favorites.fid DESC"
);
$fans->execute([$session['uid']]);

$p_views = $conn->prepare(
	"SELECT * FROM views
	LEFT JOIN videos ON views.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND videos.converted = 1
	ORDER BY views.view_id DESC"
);
$p_views->execute([$session['uid']]);


$favs = $conn->prepare(
	"SELECT * FROM favorites
	LEFT JOIN videos ON favorites.vid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE favorites.uid = ? AND videos.converted = 1
	ORDER BY favorites.fid DESC"
);
$favs->execute([$session['uid']]);

$vresponses = $conn->prepare(
	"SELECT * FROM vidresponses
	LEFT JOIN videos ON vidresponses.responseto = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE videos.uid = ? AND (videos.converted = 1 AND videos.privacy = 1) AND vidresponses.accepted = 0
	ORDER BY vidresponses.rid DESC"
);
$vresponses->execute([$session['uid']]);
// endingggg
}
?>


<script type="text/javascript" src="/js/components_yts1157352107.js"></script>
<script type="text/javascript" src="/js/AJAX_yts1161839869.js"></script>
<script type="text/javascript" src="/js/video_bar_yts1157352107.js"></script>

<script type="text/javascript">
</script>




<div id="hpMainContent"> 
			<div class="hpContentBlock">
		<!-- <div id="hpSVidAboutLink"><a href="#">Advertise on YouTube</a></div> -->
		<div id="hpSVidHeader">Director Videos</div>
		<div>
			<div class="hpSVidEntry " style="margin-bottom: 0px;">
				<div class="vstill"><a href="/cthru?5IW6cZwKG2jK4jcmCdtDedt_12bb1YTpDh-UOHQ4K7L9Ub3VuH4p1oBbhjerR1EdRJ7KfTNH8vRnIsIo5Gu5m_wMtB1czOIx1LZe9Zc6dUBdlcmblVGQKSpnYEOzmOqSGJ1o3xV7MULY5Ko_XRLjS5LJ0LZaAkuqgJHsolPWFv8TefeY7-q242l3b05SfYOK3X6MI9TKSBk=" name="&lid=DV+-+AlanKaltersCelebrityInterviewwithGeorgeClooney+-+CBS&lpos=Homepage-s0"><img src="http://sjl-static5.sjl.youtube.com/vi/y4znAqNYB1s/2.jpg" class="vimg80"></a></div>
				<div class="vtitle xsmallText">
				
				<a href="/cthru?5IW6cZwKG2jK4jcmCdtDedt_12bb1YTpDh-UOHQ4K7L9Ub3VuH4p1oBbhjerR1EdRJ7KfTNH8vRnIsIo5Gu5m_wMtB1czOIx1LZe9Zc6dUBdlcmblVGQKSpnYEOzmOqSGJ1o3xV7MULY5Ko_XRLjS5LJ0LZaAkuqgJHsolPWFv8TefeY7-q242l3b05SfYOK3X6MI9TKSBk=" name="&lid=DV+-+AlanKaltersCelebrityInterviewwithGeorgeClooney+-+CBS&lpos=Homepage-s0">Alan Kalter's Celebrity Interview with George Clooney</a>
				</div>
				
			</div>
			<div class="hpSVidEntry " style="margin-bottom: 0px;">
				<div class="vstill"><a href="/cthru?orEsGRtQ18reLeOHlAeAIEUf0KfLDUt7zJ2arPnrD387Ow94gPAwENObNKZBeBrGj50Nl8nHbvWDLoNR_7nj6jx7HfaCWOsf8y-77rVxch-H4XiNqPTewnU993QjDDVkYDgf9QgvmCOY2pjJ8vRcJASZQ0sazeZSSzqZ4M19hW-VPJ2OHh8TgviC56nOMXcfVAu1ivvWCWU=" name="&lid=DV+-+411VM141Commercial+-+411VideoMagazine&lpos=Homepage-s1"><img src="http://sjc-static12.sjc.youtube.com/vi/TC7gXmMPKUc/2.jpg" class="vimg80"></a></div>
				<div class="vtitle xsmallText">
				
				<a href="/cthru?orEsGRtQ18reLeOHlAeAIEUf0KfLDUt7zJ2arPnrD387Ow94gPAwENObNKZBeBrGj50Nl8nHbvWDLoNR_7nj6jx7HfaCWOsf8y-77rVxch-H4XiNqPTewnU993QjDDVkYDgf9QgvmCOY2pjJ8vRcJASZQ0sazeZSSzqZ4M19hW-VPJ2OHh8TgviC56nOMXcfVAu1ivvWCWU=" name="&lid=DV+-+411VM141Commercial+-+411VideoMagazine&lpos=Homepage-s1">411VM 14.1 Commercial</a>
				</div>
				
			</div>
			<div class="hpSVidEntry " style="margin-bottom: 0px;">
				<div class="vstill"><a href="/cthru?JvOwGrOlwfezB9tstUgAF6ASw20tCSwQhALugdtNTszRd0exPt8ZW46DLzD4FFntUkTHIXrVvEKKKmI7_GJyyyiAsfF81SeIqEPE0vU4OJIFjGC8LIeYB6pvaZuv7poIUjEUxMJOWXtSEKjPP8x3OQ5B5xatn3iuUiHEjjgIW2cD_R1AL7sCsy7jCPPAkBUuojI2wQ8AjHc=" name="&lid=DV+-+TenQuestions+-+CBS&lpos=Homepage-s2"><img src="http://sjc-static2.sjc.youtube.com/vi/DiVvkv4sBFI/2.jpg" class="vimg80"></a></div>
				<div class="vtitle xsmallText">
				
				<a href="/cthru?JvOwGrOlwfezB9tstUgAF6ASw20tCSwQhALugdtNTszRd0exPt8ZW46DLzD4FFntUkTHIXrVvEKKKmI7_GJyyyiAsfF81SeIqEPE0vU4OJIFjGC8LIeYB6pvaZuv7poIUjEUxMJOWXtSEKjPP8x3OQ5B5xatn3iuUiHEjjgIW2cD_R1AL7sCsy7jCPPAkBUuojI2wQ8AjHc=" name="&lid=DV+-+TenQuestions+-+CBS&lpos=Homepage-s2">Ten Questions</a>
				</div>
				
			</div>
			<div class="hpSVidEntry " style="margin-bottom: 0px;">
				<div class="vstill"><a href="/cthru?ysQh4tSIoSCE44ZQ42L3Iso19LzdBYnookDKDVAOGKvcsxhBC1oWgAbVjY_EwAuiEao-PbJ-JjEBBVSfBt9ROTUi1_AfCYfOaS2mrGmtYzFWg_rZslMACE0JPPr2pKFjguB-cjQtvjVQP7XbuOXiTe8-N8fJbMMOevy5aSBRVXCEWRa7kI8C3b7f8CfRw1_7zHvwLW_2hEk=" name="&lid=DV+-+WhoWantsToMarryAMidget+-+CBS&lpos=Homepage-s3"><img src="http://sjl-static7.sjl.youtube.com/vi/w_2rQU96yfE/2.jpg" class="vimg80"></a></div>
				<div class="vtitle xsmallText">
				
				<a href="/cthru?ysQh4tSIoSCE44ZQ42L3Iso19LzdBYnookDKDVAOGKvcsxhBC1oWgAbVjY_EwAuiEao-PbJ-JjEBBVSfBt9ROTUi1_AfCYfOaS2mrGmtYzFWg_rZslMACE0JPPr2pKFjguB-cjQtvjVQP7XbuOXiTe8-N8fJbMMOevy5aSBRVXCEWRa7kI8C3b7f8CfRw1_7zHvwLW_2hEk=" name="&lid=DV+-+WhoWantsToMarryAMidget+-+CBS&lpos=Homepage-s3">Who Wants To Marry A Midget?</a>
				</div>
				
			</div>
		<div class="clearL" style="height: 1px;"></div>
		</div>
	</div> <!-- end hpContentBlock -->


		<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content">		<div class="headerTitle">
			<div class="headerTitleRight">
				<a href="/browse?s=rf">See More Videos</a>
			</div>
			<span>Featured Videos</span>
		</div>
</div>
	</div>

	<div class="vListBox">
                     <?php foreach($featured as $pick) { 
                       $pick['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
				$pick['views']->execute([$pick['vid']]);
				$pick['views'] = $pick['views']->fetchColumn();
						
				$pick['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
				$pick['comments']->execute([$pick['vid']]);
				$pick['comments'] = $pick['comments']->fetchColumn();
				switch($pick['category']) {
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
            <? if($pick['special'] == 1) {?>
			<div class="moduleEntrySelected">
			<? } else { ?>
			<div class="vEntry"><? } ?>
		<table class="vTable"><tr>
			<td>
				<a href="index.php?v=<?php echo htmlspecialchars($pick['vid']); ?>"><img src="get_still.php?video_id=<?php echo htmlspecialchars($pick['vid']); ?>" border="0" class="vimg120" /></a>
				        <script language="javascript" type="text/javascript">
                if (navigator.appName.indexOf('Microsoft') != -1) {
                        document.write('<div id="add_img_<?php echo htmlspecialchars($pick['vid']); ?>" style="width:20px;margin-top:-29px;margin-left:1px;">');
                }
                else {
                        document.write('<div id="add_img_<?php echo htmlspecialchars($pick['vid']); ?>" style="width:20px;margin-top:-26px;margin-left:1px;">');
                }
        </script>
        <a href="#" onClick="clicked_add_icon('<?php echo htmlspecialchars($pick['vid']); ?>', 0);return false;" title="Add Video to QuickList"><img id="add_button_<?php echo htmlspecialchars($pick['vid']); ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo htmlspecialchars($pick['vid']); ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo htmlspecialchars($pick['vid']); ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
        </div>

			</td>
			<td class="vinfo">
				<div class="vtitle">
					<a href="index.php?v=<?php echo htmlspecialchars($pick['vid']); ?>"><?php echo htmlspecialchars($pick['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $pick['time']); ?></span>
				</div>
				<div class="vdesc">		
			<span id="BeginvidDesc<?php echo htmlspecialchars($pick['vid']); ?>">
	<?php echo shorten($pick['description'], 240, ''); ?>
	</span>
	
			<?php if (shorten($pick['description'], 240, '') != htmlspecialchars($pick['description'])) { ?>
			<span id="RemainvidDesc<?php echo htmlspecialchars($pick['vid']); ?>" style="display: none"><?php echo htmlspecialchars($pick['description']); ?></span>
			<span id="MorevidDesc<?php echo htmlspecialchars($pick['vid']); ?>" class="smallText">(<a href="#" class="eLink" onclick="showInline('RemainvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); hideInline('MorevidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); hideInline('BeginvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); showInline('LessvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); return false;">more</a>)</span>
			<span id="LessvidDesc<?php echo htmlspecialchars($pick['vid']); ?>" style="display: none" class="smallText">(<a href="#" class="eLink" onclick="hideInline('RemainvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); hideInline('LessvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); showInline('BeginvidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); showInline('MorevidDesc<?php echo htmlspecialchars($pick['vid']); ?>'); return false;">less</a>)</span>
			<? } ?>


</div>
				<div class="vfacets">
					<div class="vtagLabel">Tags:</div>
					<div class="vtagValue"><?php
						foreach(explode(" ", $pick['tags']) as $tag) {
							echo '<a href="results.php?search='.htmlspecialchars($tag).'" class="dg">'.htmlspecialchars($tag).'</a> &nbsp; ';
						}
						?></div>
						<span class="grayText">Added:</span> <?php echo timeAgo($pick['uploaded']); ?>
						<span class="grayText"> &nbsp; in Category:</span> <a href="/browse?s=mp&t=t&c=<?php echo htmlspecialchars($pick['category']); ?>" class="dg"><?php echo $catname; ?></a><br/>
							<span class="grayText">From:</span> <a href="profile.php?user=<?php echo htmlspecialchars($pick['username']); ?>"><?php echo htmlspecialchars($pick['username']); ?></a><br/>
						<span class="grayText">Views:</span> <?php echo number_format($pick['views']); ?>
				</div>
				<?php if (getRatingCount($pick['vid']) != 0) { ?>
							<nobr>
			<? grabRatings($pick['vid'], "SM", 'class="rating"'); ?>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($pick['vid'])); ?> ratings</div>
		<? } ?>
	



			</td>
		</tr>
		</table>
	</div> <!-- end vEntry -->
<?php } ?>

	</div> <!-- end vListBox -->
	<div class="footerBox">
		<div style="padding: 3px 0px; text-align: right;"><a href="/browse?s=rf">See More Videos</a></div>
	</div>
</div> <!-- end hpMainCol -->


<div id="hpSideContent">

	<div class="hpContentBlock">
		<div id="hpEmbedTopCap">
		<!-- 300x35 ad block above embed player -->
	 	
				
		
			

							
	<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 4;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.home/_default;sz=300x35;kch=3000513466;kbg=FFFFFF;kvideoid=oJeEw6jtJEg;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.home/_default;sz=300x35;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.home/_default;sz=300x35;ord=123456789?" width="300" height="35" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		

		</div> <!-- end ad block -->
					<div id="hpEmbedVideo">
		<object width="300" height="250"><param name="movie" value="/admp.swf?vids=oJeEw6jtJEg&eurl=/index&iurl=http%3A//sjc-static5.sjc.youtube.com/vi/oJeEw6jtJEg/2.jpg&t=OEgsToPDskI1qLfCYQREimkwlfCg14Sa"></param><embed src="/admp.swf?vids=oJeEw6jtJEg&eurl=/index&iurl=http%3A//sjc-static5.sjc.youtube.com/vi/oJeEw6jtJEg/2.jpg&t=OEgsToPDskI1qLfCYQREimkwlfCg14Sa" type="application/x-shockwave-flash" width="300" height="250"></embed></object>
		</div>
		
		<div id="hpEmbedUnderBlock">
		
			<table cellpadding="0" cellspacing="0" width="100%"><tr valign="top">
			<td width="80%"> <!-- begin embedInfo -->
				<div class="vtitle"><a href="/watch?v=oJeEw6jtJEg">National Lampoon's Van Wilder: The Rise of Taj Trailer</a></div>
				<div class="vfacets">
					<span class="grayText">From:</span> <i><a href="/user/MGMStudiosInc" class="dg">MGMStudiosInc</a></i><br/>
					<span class="grayText">Comments:</span> <a href="/comment_servlet?all_comments&v=oJeEw6jtJEg&fromurl=/watch?v=oJeEw6jtJEg">99</a>
				</div>
			</td> <!-- end embedInfo -->
			
			<td width="20%" align="center" nowrap>
				<div id="ratingDiv">
				<?php if ($_SESSION['uid'] == NULL) { ?>
						<div id="ratingMessage" class="label">Login to rate video</div>
									<nobr>
			<img class="rating" src="/img/star.gif">
			<img class="rating" src="/img/star.gif">
			<img class="rating" src="/img/star.gif">
			<img class="rating" src="/img/star_bg.gif">
			<img class="rating" src="/img/star_bg.gif">
	</nobr>
		<div class="rating">924 ratings</div>
		<? } else { ?>
				<div id="ratingMessage" class="label">Rate this video</div>
					
<form style="display:none;" name="ratingForm" action="/rating" method="POST">
	<input type="hidden" name="action_add_rating" value="1" />
	<input type="hidden" name="rating_count" value="924">
	<input type="hidden" name="video_id" value="oJeEw6jtJEg">
	<input type="hidden" name="user_id" value="<?php echo htmlspecialchars($session['uid']); ?>">
	<input type="hidden" name="rating" id="rating" value="">
	<input type="hidden" name="size" value="L">
</form>

<script language="javascript">
	ratingComponent = new UTRating('ratingDiv', 5, 'ratingComponent', 'ratingForm', 'ratingMessage', '', 'L');
	ratingComponent.starCount=3;
			onLoadFunctionList.push(function() { ratingComponent.drawStars(3, true); });
</script>

	
<div>
		<nobr>
			<a href="#" onclick="ratingComponent.setStars(1); return false;" onmouseover="ratingComponent.showStars(1);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__1" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(2); return false;" onmouseover="ratingComponent.showStars(2);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__2" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(3); return false;" onmouseover="ratingComponent.showStars(3);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__3" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(4); return false;" onmouseover="ratingComponent.showStars(4);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__4" class="rating" style="border: 0px" ></a>
			<a href="#" onclick="ratingComponent.setStars(5); return false;" onmouseover="ratingComponent.showStars(5);" onmouseout="ratingComponent.clearStars();"><img src="/img/star_bg.gif" id="star__5" class="rating" style="border: 0px" ></a>
	</nobr>
		<div class="rating">924 ratings</div>
	


</div>
<? } ?>
	



				</div> <!-- end ratings Div -->
			</td>
			
			</tr></table>
			
			


		</div> <!-- end embedUnderBlock -->	


	</div> <!-- end hpContentBlock -->

	
	


<?php if(isset($session)) { ?>
		<div class="hpContentBlock">
				<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">Welcome, <?php echo htmlspecialchars($session['username']) ?></span></div>
	</div>

			<div class="contentBox">
				<table class="hpAboutTable">
				    <tr>
					<td>
					<span class="hpStatsHeading">Statistics</span>
					<div><span class="smallLabel">Video Views: </span><?php echo $p_views->rowCount(); ?></div>
					<div><span class="smallLabel">Channel Views: </span>2834</div>
					<div><span class="smallLabel">Subscribers: </span>94</div>
					<div><span class="smallLabel"><a href="profile.php">View Channel</a></span></div>
					</td>
					<td>
					<span class="hpStatsHeading">Inbox</span>
					<div><span class="smallLabel">General Messages: </span><a href="my_messages.php">&nbsp;<?php echo htmlspecialchars($inbox) ?>&nbsp;</a></div>
					<div><span class="smallLabel">Friend Invites: </span><a href="my_messages.php">&nbsp;9&nbsp;</a></div>
					<div><span class="smallLabel">Received Videos: </span><a href="my_messages.php">&nbsp;0&nbsp;</a></div>
					<div><span class="smallLabel"><a href="my_messages.php">Read Inbox</a></span></div>
					</td>
					</tr>
					</table>
					<table class="hpAboutTable">
					<tr>
					<?php if ($vresponses->rowCount != 0) { ?>
					<td class="userStats">
					<span class="hpStatsHeading">Video Responses</span><br/>
					You have pending video responses<br/>
					<a href="index.php">View Pending</a>
					</td>
					<? } ?>
					</tr>
					<tr>
					<td class="userStats">
					<span class="hpStatsHeading">Quick Links</span>
					<div class="smallText">
					<a href="my_videos">My Videos</a>&nbsp; | &nbsp;<a href="/my_favorites">My Favorites</a>&nbsp; | &nbsp;<a href="/my_playlists">My Playlists</a>
					</div>
					</td>
					</tr>
                </table>
				
			</div>
		</div> <!-- end hpContentBlock -->
<? } else if(!isset($session)){ ?>
		<div class="hpContentBlock">
				<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">Member Login</span></div>
	</div>

			<div class="contentBox">
				<table>
					<form method="post" name="loginForm" id="loginForm" action="signup">
					<input type="hidden" name="action_login" value="1">
					<tr>
					<td><b>User Name:</b></td>
					<td><input tabindex="1" type="text" name="username" value="" class="hpLoginField"></td>
					</tr>
					<tr>
					<td><b>Password:</b></td>
					<td><input tabindex="2" type="password" name="password" class="hpLoginField"></td>
					</tr>
					<tr>
					<td>&nbsp;</td>
					<td>
						<div style="float: right;"><b><a href="/signup">Sign Up</a></div>
						<div><input type="submit" value="Login"></div>
						<div class="hpLoginForgot smallText">
						<b>Forgot:</b> <a href="forgot_username">Username</a> | <a href="/forgot">Password</a>
						</div>
					</td>
					</tr>
					</form>
				</table>
				
			</div>
</div> <!-- end hpContentBlock --><?php } ?>
	
	
	
	<div class="hpContentBlock">
		<a href="/underground"><img src="/img/ad_underground_winners_300x35.gif" width="300" height="35" border="0" alt="youtube underground" name="&lid=underground+banner&lpos=Promo+Block" /></a>
	</div>
	
	
	
	<div class="hpContentBlock">
			<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">What's New at YouTube</span></div>
	</div>

		<div class="contentBox">

			<p style="margin-top:0px;">
				<b><a href="/underground"><img src="/img/pic_home_underground_30x37.gif" style="float: left; margin: 2px 6px 10px 0px;" border="0">YouTube Underground</a></b><br/>
				<b>Winners Have Been Chosen!</b>
			</p>
			
		</div>
	</div> <!-- end hpContentBlock -->

	
	
	<div class="hpContentBlock">
			<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">Recently Released</span></div>
	</div>

		<div class="contentBox">
			<p style="margin-top:0px;">
			<b><a href="/watch_queue?all"><img src="/img/pic_home_quicklist_30x37.gif" style="float: left; margin: 2px 6px 10px 0px;" border="0">QuickList</a></b><br>
			Too much good stuff to watch now? Click the "+" on any video to save it for later.
			</p>
			
			<p>
			<b><a href="/signup?signup_type=c"><img src="/img/pic_home_comedians_30x37.gif" style="float: left; margin: 2px 6px 10px 0px;" border="0"> Comedian Accounts</a></b><br/>
			Get your standup, improv, or sketches out to a huge audience!
			</p>
			
			<p>
			<b><a href="/school_main"><img src="/img/colleges.gif" style="float: left; margin: 2px 6px 10px 0px;" border="0">YouTube Colleges</a></b><br/>
			Join or request your college now and start sharing your college experience!
			</p>
		</div>
	</div> <!-- end hpContentBlock -->
	
	
	
	
	
	<div class="hpContentBlock">
			<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">Active Channels</span></div>
	</div>

		<div class="contentBox">
			<div class="hpChannelEntry v80hEntry">
				<div class="vstill"><a href="/profile?user=ankhv2"><img src="http://sjl-static10.sjl.youtube.com/vi/LJnkFvaeZMQ/2.jpg" class="vimg" style="background: #333;"></a></div>
				<div class="vinfo">
					<b><a href="/profile?user=ankhv2">ankhv2</a></b>
					<div class="vfacets">25 Videos | 109 Subscribers</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpChannelEntry -->
						<div class="hpChannelEntry v80hEntry">
				<div class="vstill"><a href="/profile?user=cualcerdo"><img src="http://sjc-static11.sjc.youtube.com/vi/ezDxVvW8BBQ/2.jpg" class="vimg" style="background: #333;"></a></div>
				<div class="vinfo">
					<b><a href="/profile?user=cualcerdo">cualcerdo</a></b>
					<div class="vfacets">13 Videos | 479 Subscribers</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpChannelEntry -->
						<div class="hpChannelEntry v80hEntry">
				<div class="vstill"><a href="/profile?user=Interscope"><img src="http://sjl-static16.sjl.youtube.com/vi/Ga7hU-3-PbQ/2.jpg" class="vimg" style="background: #333;"></a></div>
				<div class="vinfo">
					<b><a href="/profile?user=Interscope">Interscope</a></b>
					<div class="vfacets">2 Videos | 183 Subscribers</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpChannelEntry -->
						<div class="hpChannelEntry v80hEntry">
				<div class="vstill"><a href="/profile?user=wiccawitch1"><img src="http://sjc-static10.sjc.youtube.com/vi/Zh2lU6F74SA/2.jpg" class="vimg" style="background: #333;"></a></div>
				<div class="vinfo">
					<b><a href="/profile?user=wiccawitch1">wiccawitch1</a></b>
					<div class="vfacets">71 Videos | 150 Subscribers</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpChannelEntry -->
			
			<div style="text-align: right;"><a href="/members">See More Channels</a></div>
		</div>
	</div> <!-- end hpContentBlock -->


	<div class="hpContentBlock">
			<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content"><span class="headerTitle">Active Groups</span></div>
	</div>

		<div class="contentBox">
			<div class="hpGroupEntry v80hEntry">
				<div class="vstill"><a href="/group/cheyennekimball"><img class="vimg" src="http://sjl-static14.sjl.youtube.com/vi/P0IIqaz6cec/2.jpg" /></a></div>
				<div class="vinfo">
					<b><a href="/group/cheyennekimball">cheyennekimball</a></b>
					
					<div class="vfacets"> 0 Videos | 0 Discussions</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpGroupEntry -->
						<div class="hpGroupEntry v80hEntry">
				<div class="vstill"><a href="/group/createkungfu"><img class="vimg" src="http://sjc-static7.sjc.youtube.com/vi/9qDB40lkZrk/2.jpg" /></a></div>
				<div class="vinfo">
					<b><a href="/group/createkungfu">createkungfu</a></b>
					
					<div class="vfacets"> 176 Videos | 25 Discussions</div>
				</div>
				<div class="clear"></div>
			</div> <!-- end hpGroupEntry -->
			
			<div style="text-align: right;"><a href="/groups_main">See More Groups</a></div>
		</div>
	</div> <!-- end hpContentBlock -->
	
	
	
</div> <!-- end hpSideCol -->
<?php 
require "needed/end.php";
?>
