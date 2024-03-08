<?php
require "needed/start.php";
// I took like 6 hours modernizing this and improving pagination. Shocking, eh?
// WARNING: Code below is probably shit. I can't for sure say how it'd look to someone other than me, but I imagine would not be too beautiful.
$start_time = microtime(true);
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$ppv = 20;
$offs = ($page - 1) * $ppv;

if (!empty($_GET['search']) || !empty($_GET['related']) || !empty($_GET['search_query'])) {
    //$res_title = "Search";
    //$res_rlted = "Results";
    
    if(!empty($_GET['search']) && $_GET['search'] != "Search") {
    $search = str_replace(" ", "|", $_GET['search']);
    } elseif (!empty($_GET['related'])) {
    $search = str_replace(" ", "|", $_GET['related']);    
	} else {
    $search = str_replace(" ", "|", $_GET['search_query']);    
    }
    $_GET['search'] = $_GET['related'];
    $vidocount = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY uploaded DESC");
    $vidocount->execute([$search, $search, $search, $search]);
    $vidocount = $vidocount->rowCount();

    if(empty($_GET['search'])) {
    $videos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND videos.converted = 1 ORDER BY (INSTR(LOWER(description), LOWER(?)) > 0) DESC LIMIT $ppv OFFSET $offs");
    $videos->execute([$search, $search, $search, $search, $search]);
    }else{
    //$res_title = "Tag";
    //$res_rlted = "Related results";
    $videos = $conn->prepare("SELECT * FROM videos LEFT JOIN users ON users.uid = videos.uid WHERE (videos.tags REGEXP ? OR videos.description REGEXP ? OR videos.title REGEXP ? OR users.username REGEXP ?) AND videos.privacy = 1 AND             videos.converted = 1 ORDER BY (INSTR(LOWER(tags), LOWER(?)) > 0) DESC LIMIT $ppv OFFSET $offs");
    $videos->execute([$search, $search, $search, $search, $search]);
    }
} else {
    //$res_title = "Tag";
    class videos { // Placeholder class when search is empty
        function rowCount() {
            return 0;
        }
    }
    $videos = new videos;
}

?>
<div id="leaderboardAd">
<!-- google_ad_section_start -->
 
 		
		
			

	
							<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 1;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.results/_default;sz=728x90;kch=1600166264;kbg=FFFFFF;ksearch=blah;kr=F;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.results/_default;sz=728x90;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.results/_default;sz=728x90;ord=123456789?" width="728" height="90" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		

</div>

<div id="sideContent">
	<div>
	<!-- google_ad_section_start -->
	 
	 		
		
			

			
					<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 2;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.results/_default;sz=160x600;kch=1187796739;kbg=FFFFFF;ksearch=blah;kr=F;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.results/_default;sz=160x600;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.results/_default;sz=160x600;ord=123456789?" width="160" height="600" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		

	 </div>

	<div class="spOffersDiv">
		<h4 class="label">New on YouTube</h4>
		<div class="spOffersEntry">
		One week left!
		Vote in the
		<a href="/contest/youtubeunderground">Youtube Underground</a>.
		</div>
		<div class="spOffersEntry">
		Do you believe?
		<a href="/ittakes5ive">It takes 5ive</a>.
		</div>
		<div class="spOffersEntry">
		<a href="/wantlieswiththat">Do you want lies with that?</a>
		</div>
		<div class="spOffersEntry">
		He's back!
		<a href="/supermanreturnsdvd">Superman Returns</a>.
		</div>
	</div>
</div> <!-- end sideContent -->



<div id="mainContent">

<div id="sectionHeader" class="searchColor">
		<? if($vidocount > 0) { ?><div class="my" style="padding-top: 8px; font-size: 12px;">Results <?php if ($offs > 0) { echo htmlspecialchars(trim($offs)); } else { echo "1"; } ?>-<? if($vidocount > $ppv) { $nextynexty = $offs + $ppv; } else {$nextynexty = $vidocount; } echo htmlspecialchars($nextynexty); ?> of <?php echo $vidocount; ?></div><? } ?>
	<div class="name">Search</div>
	<span class="title">	Video <span class="normalText">results for</span>
	'<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>'  
</span>
</div>


<div id="sideNav">
		<div class="navHead searchColor">Search In</div>
		<div class="navBody12">
					<div class="label">&raquo; Videos</div>
					<a href="/results?search_type=search_users&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=0">Channels</a><br/>
					<a href="/results?search_type=search_groups&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=0">Groups</a><br/>
					<a href="/results?search_type=search_playlists&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=0">Playlists</a><br/>
		</div>


		<div class="navHead searchColor">Sort By</div>
		<div class="navBody11">
					<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=relevance&search_category=0">Relevance</a><br/>
					<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=video_date_uploaded&search_category=0">Date Added</a><br/>
					<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=video_view_count&search_category=0">View Count</a><br/>
					<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=video_avg_rating&search_category=0">Rating</a><br/>
		</div>
		
		
			<div class="navHead searchColor">Refine by Category</div>
			<div class="navBody11">
						<div class="label">&raquo; All</div>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=1">Arts &amp; Animation</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=2">Autos &amp; Vehicles</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=23">Comedy</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=24">Entertainment</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=10">Music</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=25">News &amp; Blogs</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=22">People</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=15">Pets &amp; Animals</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=26">Science &amp; Technology</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=17">Sports</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=19">Travel &amp; Places</a><br/>
						<a href="/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=20">Video Games</a><br/>
			</div>
	
	
	
	
</div> <!-- end sideNav -->




<div id="mainContentWithNav">
				
			<? if($vidocount > 0) { ?>
			<div> <!-- start search results -->
		
				<?php foreach($videos as $video) { ?>
				<?php
				
				$video['views'] = $conn->prepare("SELECT COUNT(view_id) FROM views WHERE vid = ?");
				$video['views']->execute([$video['vid']]);
				$video['views'] = $video['views']->fetchColumn();
						
				//$video['comments'] = $conn->prepare("SELECT COUNT(cid) FROM comments WHERE vidon = ?");
				//$video['comments']->execute([$video['vid']]);
				//$video['comments'] = $video['comments']->fetchColumn();
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
			<div class="vEntry">
		<table class="vTable"><tr>
			<td>
				<a href="/watch?v=<?php echo $video['vid']; ?>" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $video['title']); ?>','VidHorz');"><img src="get_still.php?video_id=<?php echo $video['vid']; ?>" border="0" class="vimg120" /></a>
				        <script language="javascript" type="text/javascript">
                if (navigator.appName.indexOf('Microsoft') != -1) {
                        document.write('<div id="add_img_<?php echo $video['vid']; ?>" style="width:20px;margin-top:-29px;margin-left:1px;">');
                }
                else {
                        document.write('<div id="add_img_<?php echo $video['vid']; ?>" style="width:20px;margin-top:-26px;margin-left:1px;">');
                }
        </script>
        <a href="#" onClick="clicked_add_icon('<?php echo $video['vid']; ?>', 0);return false;" title="Add Video to QuickList"><img id="add_button_<?php echo $video['vid']; ?>" border="0" onMouseover="mouse_over_add_icon('<?php echo $video['vid']; ?>');return false;" onMouseout="mouse_out_add_icon('<?php echo $video['vid']; ?>');return false;"  src="/img/icn_add_20x20.gif" alt="Add Video to QuickList"></a>
        </div>

			</td>
			<td class="vinfo">
				<div class="vtitle">
					<a href="/watch?v=<?php echo $video['vid']; ?>" onclick="_hbLink('<?php echo preg_replace('/[^A-Za-z0-9]/', '', $video['title']); ?>','VidHorz');"><?php echo htmlspecialchars($video['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $video['time']); ?></span>
				</div>
				<div class="vdesc">		
			<span id="BeginvidDesc<?php echo $video['vid']; ?>">
	<?php echo shorten($video['description'], 240, ''); ?>
	</span>
	
			<?php if (shorten($video['description'], 240, '') != htmlspecialchars($video['description'])) { ?>
			<span id="RemainvidDesc<?php echo $video['vid']; ?>" style="display: none"><?php echo htmlspecialchars($video['description']); ?></span>
			<span id="MorevidDesc<?php echo $video['vid']; ?>" class="smallText">(<a href="#" class="eLink" onclick="showInline('RemainvidDesc<?php echo $video['vid']; ?>'); hideInline('MorevidDesc<?php echo $video['vid']; ?>'); hideInline('BeginvidDesc<?php echo $video['vid']; ?>'); showInline('LessvidDesc<?php echo $video['vid']; ?>'); return false;">more</a>)</span>
			<span id="LessvidDesc<?php echo $video['vid']; ?>" style="display: none" class="smallText">(<a href="#" class="eLink" onclick="hideInline('RemainvidDesc<?php echo $video['vid']; ?>'); hideInline('LessvidDesc<?php echo $video['vid']; ?>'); showInline('BeginvidDesc<?php echo $video['vid']; ?>'); showInline('MorevidDesc<?php echo $video['vid']; ?>'); return false;">less</a>)</span>
			<? } ?>


</div>
				<div class="vfacets">
					<div class="vtagLabel">Tags:</div>
					<div class="vtagValue"><?php
						foreach(explode(" ", $video['tags']) as $tag) {
							echo '<a href="/results?search_query='.htmlspecialchars($tag).'" class="dg">'.htmlspecialchars($tag).'</a> &nbsp; ';
						}
						?></div>
						<span class="grayText">Added:</span> <?php echo timeAgo($video['uploaded']); ?>
						<span class="grayText"> &nbsp; in Category:</span> <a href="/browse?s=mp&t=t&c=<?php echo htmlspecialchars($video['category']); ?>" class="dg"><?php echo $catname; ?></a><br/>
							<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($video['username']); ?>"><?php echo htmlspecialchars($video['username']); ?></a><br/>
						<span class="grayText">Views:</span> <?php echo number_format($video['views']); ?>
				</div>
				<?php if (getRatingCount($video['vid']) != 0) { ?>
							<nobr>
			<? grabRatings($video['vid'], "SM", 'class="rating"'); ?>
	</nobr>
		<div class="rating"><? echo htmlspecialchars(getRatingCount($video['vid'])); ?> ratings</div>
		<? } ?>
	



			</td>
		</tr>
		</table>
	</div> <!-- end vEntry -->

		<?php } ?>

	</div> <!-- end search results -->
	<?php } else { ?>
				<div class="contentBox">
			No Videos found for '<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>'
		</div>
	<?php } ?>

		
	
	<? if($vidocount > 0) { ?>
	<div class="footerBox">
		
		<?php if($vidocount > $ppv) { ?>
		<div class="pagingDiv">
				Pages:

    <?php
    $totalPages = ceil($vidocount / $ppv);
    if (empty($_GET['page'])) { $_GET['page'] = 1; }
    $pagesPerSet = 7; // Set the number of pages per group
    $startPage = floor(($page - 1) / $pagesPerSet) * $pagesPerSet + 1;
    $endPage = min($startPage + $pagesPerSet - 1, $totalPages); ?>
	<?php if ($startPage < $totalPages && $page !== 1) { ?>
						<span class="pagerNotCurrent" onClick="location.href='/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=0&page=<?php echo $_GET['page'] - 1; ?>'" accesskey="p" >Previous</span>
	<?php } ?>
		
    <?php 
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $page) {
            echo '<span class="pagerCurrent">' . $i . '</span>';
        } else {
            echo '<span class="pagerNotCurrent" onClick="location.href=\'/results?search_type=search_videos&search_query=' . htmlspecialchars(str_replace("|", " ", $search)) . '&search_sort=&search_category=0&page=' . $i . '\'" >' . $i . '</span>';
        }
    }
    ?>
<?php if ($totalPages > 7) { ?>


		
			...<?php } ?>
						<?php if ($endPage < $totalPages) { ?>
						<span class="pagerNotCurrent" onClick="location.href='/results?search_type=search_videos&search_query=<?php echo htmlspecialchars(str_replace("|", " ", $search)); ?>&search_sort=&search_category=0&page=<?php echo $_GET['page'] + 1; ?>'" >Next</span>
						<?php } ?>

		
			</div> <!-- end pagingDiv --><?php } ?>




	</div>
	<?php } ?>

</div> <!-- end mainContentWithNav -->
</div> <!-- end mainContent -->


















<?php 
require "needed/end.php";
?>