<?php
require "needed/start.php";
if (!isset($_GET['c'])) {
	die(header("Location: /categories"));
}
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ppv = 15;
$offs = ($page - 1) * $ppv;
$featured = $conn->prepare("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = ? ORDER BY picks.featured DESC LIMIT 4");
$featured->execute([$_GET['c']]);
$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.converted = 1 AND videos.privacy = 1 AND users.termination = 0) AND videos.category = ? ORDER BY uploaded DESC LIMIT $ppv OFFSET $offs");
$videos->execute([$_GET['c']]);
switch($_GET['c']) {
					case '1':
						$catname = "Arts &amp; Animation";
						$catdesc = "Fine Arts, Machinima, Anime...";
						break;
					case '2':
						$catname = "Autos &amp; Vehicles";
						$catdesc = "Cars, Racing, Accessorizing...";
						break;
					case '23':
						$catname = "Comedy";
						$catdesc = "Bloopers, Pranks, Improv, Silliness...";
						break;
					case '24':
						$catname = "Entertainment";
						$catdesc = "Short Movies, Random Weirdness...";
						break;
				    case '10':
						$catname = "Music";
						$catdesc = "Singing, Dancing, Indie Bands...";
						break;
				    case '25':
						$catname = "News &amp; Blogs";
						$catdesc = "News, Blogs, Local Issues...";
						break;
				    case '22':
						$catname = "People";
						$catdesc = "Celebrities, Personals, Family Events...";
						break;
				    case '15':
						$catname = "Pets &amp; Animals";
						$catdesc = "Dogs, Cats, Fish, Birds, Bears...";
						break;
				    case '26':
						$catname = "Science &amp; Technology";
						$catdesc = "Gadgets, Robots, Computers...";
						break;
				    case '17':
						$catname = "Sports";
						$catdesc = "Extreme, Competitions, Skateboarding...";
						break;
				    case '19':
						$catname = "Travel &amp; Places";
						$catdesc = "Vacations, Nature, Monuments...";
						break;
				    case '20':
						$catname = "Video Games";
						$catdesc = "Demos, Previews, Game Play...";
						break;
					default:
						die(header("Location: /categories"));
				}
?>
<div id="sideAd">
	 
	 		
		
			

			
					<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 2;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.categories/autosandvehicles;sz=160x600;kch=6465244539;kbg=FFFFFF;;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.categories/autosandvehicles;sz=160x600;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.categories/autosandvehicles;sz=160x600;ord=123456789?" width="160" height="600" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		
		
</div>



<div id="mainContent">


<div id="sectionHeader" class="categoriesColor">
	<div class="name">Categories</div>
	<span class="title"><b><?php echo $catname; ?></b> &nbsp; </span><span class="normalText">(<?php echo $catdesc; ?>)</span>
</div>


<div id="sideNav">
	<div class="navBody12" style="padding-top: 8px;">
		<a href="/categories">All</a><br/>
				<?php echo ($_GET['c'] == 1) ? '<div class="label">&raquo; Arts & Animation</div>' : '<div><a href="/categories_portal?c=1&e=1">Arts & Animation</a></div>'; ?>
				<?php echo ($_GET['c'] == 2) ? '<div class="label">&raquo; Autos & Vehicles</div>' : '<div><a href="/categories_portal?c=2&e=1">Autos & Vehicles</a></div>'; ?>
				<?php echo ($_GET['c'] == 23) ? '<div class="label">&raquo; Comedy</div>' : '<div><a href="/categories_portal?c=23&e=1">Comedy</a></div>'; ?>
				<?php echo ($_GET['c'] == 24) ? '<div class="label">&raquo; Entertainment</div>' : '<div><a href="/categories_portal?c=24&e=1">Entertainment</a></div>'; ?>
				<?php echo ($_GET['c'] == 10) ? '<div class="label">&raquo; Music</div>' : '<div><a href="/categories_portal?c=10&e=1">Music</a></div>'; ?>
				<?php echo ($_GET['c'] == 25) ? '<div class="label">&raquo; News & Blogs</div>' : '<div><a href="/categories_portal?c=25&e=1">News & Blogs</a></div>'; ?>
				<?php echo ($_GET['c'] == 22) ? '<div class="label">&raquo; People</div>' : '<div><a href="/categories_portal?c=22&e=1">People</a></div>'; ?>
				<?php echo ($_GET['c'] == 15) ? '<div class="label">&raquo; Pets & Animals</div>' : '<div><a href="/categories_portal?c=15&e=1">Pets & Animals</a></div>'; ?>
				<?php echo ($_GET['c'] == 26) ? '<div class="label">&raquo; Science & Technology</div>' : '<div><a href="/categories_portal?c=26&e=1">Science & Technology</a></div>'; ?>
				<?php echo ($_GET['c'] == 17) ? '<div class="label">&raquo; Sports</div>' : '<div><a href="/categories_portal?c=17&e=1">Sports</a></div>'; ?>
				<?php echo ($_GET['c'] == 19) ? '<div class="label">&raquo; Travel & Places</div>' : '<div><a href="/categories_portal?c=19&e=1">Travel & Places</a></div>'; ?>
				<?php echo ($_GET['c'] == 20) ? '<div class="label">&raquo; Video Games</div>' : '<div><a href="/categories_portal?c=20&e=1">Video Games</a></div>'; ?>
	</div>
</div> <!-- end sideNav -->




<div id="mainContentWithNav" style="padding-top: 6px;">

	<div>
		<h3>Featured Videos</h3>
		<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding-top: 4px;">
					<tr valign="top">
					<?php foreach($featured as $pick) { ?>
		<td width="25%">
				<div class="vMicroEntry">
                	<a href="/watch?v=<? echo htmlspecialchars($pick['vid']); ?>"><img src="/get_still.php?video_id=<? echo htmlspecialchars($pick['vid']); ?>" class="vimgSm" alt="<? echo htmlspecialchars($pick['vid']); ?>" /></a>
			<b><a href="/watch?v=<? echo htmlspecialchars($pick['vid']); ?>"><? echo htmlspecialchars($pick['title']); ?></a></b><br/>
			<span class="runtime"><?php echo gmdate("i:s", $pick['time']); ?></span>
	</div>

		</td>


<? } ?>
	</tr>

		</table>
	</div>

	
	
	<div class="catSearchDiv">
		<form method="post" action="/categories_portal?c=<?php echo htmlspecialchars($_GET['c']); ?>">
		<input name="search" value=""></input><input type="submit" name="action_search" value="Search"> in <?php echo $catname; ?>.
		</form>
	</div>
	
	

	<div>
		<h3>More Videos in <?php echo $catname; ?></h3>
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
			<a href="watch.php?v=<?php echo $video['vid']; ?>" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $video['title']); ?>','VidVert');"><?php echo shorten($video['title'], 29); ?></a><br/>
			<span class="runtime"><?php echo gmdate("i:s", $video['time']); ?></span>
		</div>
		
		<div class="vfacets">
			<span class="grayText">Added:</span> <?php echo timeAgo($video['uploaded']); ?><br/>
			<span class="grayText">From:</span> <a href="profile.php?user=<?php echo htmlspecialchars($video['username']); ?>"><?php echo shorten($video['username'], 13); ?></a><br/>
			<span class="grayText">Views:</span> <?php echo number_format($video['views']); ?><br/>
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
	</div> <!-- end more videos -->

	
	<div class="footerBox">
                        
		<div class="pagingDiv">
				Results Page:

<?php if ($page != 1) {?>
						<span class="pagerNotCurrent" onClick="location.href='/categories_portal?c=<?php echo htmlspecialchars($_GET['c']); ?>&page=<?php echo $page - 1; ?>'" accesskey="p" >Previous</span><? } ?>
				

				<?php
	if ($page > 4) {
    $totalPages = ceil($page + 3);
	} else {
    $totalPages = ceil(105 / $ppv);
	}
	if ($page > 4) {
		$pagething = $page - 3;
	} else {
		$pagething = 1;
	}
    for ($i = $pagething; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo '<span class="pagerCurrent">' . $i . '</span>';
        } else {
            echo '<span class="pagerNotCurrent" onClick="location.href=\'/categories_portal?c=' . htmlspecialchars($_GET['c']) . '&page=' . $i . '\'" >' . $i . '</span>';
        }
    }
    ?>


		
			...<? if ($page != $totalPages) { ?>
                			<span class="pagerNotCurrent" onClick="location.href='/categories_portal?c=<?php echo htmlspecialchars($_GET['c']); ?>&page=<?php echo $page + 1; ?>'" >Next</span><? } ?>

		
			</div> <!-- end pagingDiv -->




	</div>
	
</div> <!-- end mainContentWithNav -->
</div> <!-- end mainContent -->

<?php
require "needed/end.php";
?>