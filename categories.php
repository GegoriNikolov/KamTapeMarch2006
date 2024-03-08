<?php
require "needed/start.php";
$featuredarts = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 1 ORDER BY picks.featured DESC LIMIT 1");
$featuredarts = $featuredarts->fetch(PDO::FETCH_ASSOC);
$featuredautos = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 2 ORDER BY picks.featured DESC LIMIT 1");
$featuredautos = $featuredautos->fetch(PDO::FETCH_ASSOC);
$featuredcomedy = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 23 ORDER BY picks.featured DESC LIMIT 1");
$featuredcomedy = $featuredcomedy->fetch(PDO::FETCH_ASSOC);
$featuredentertainment = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 24 ORDER BY picks.featured DESC LIMIT 1");
$featuredentertainment = $featuredentertainment->fetch(PDO::FETCH_ASSOC);
$featuredmusic = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 10 ORDER BY picks.featured DESC LIMIT 1");
$featuredmusic = $featuredmusic->fetch(PDO::FETCH_ASSOC);
$featurednewsblogs = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 25 ORDER BY picks.featured DESC LIMIT 1");
$featurednewsblogs = $featurednewsblogs->fetch(PDO::FETCH_ASSOC);
$featuredpeople = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 22 ORDER BY picks.featured DESC LIMIT 1");
$featuredpeople = $featuredpeople->fetch(PDO::FETCH_ASSOC);
$featuredpets = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 15 ORDER BY picks.featured DESC LIMIT 1");
$featuredpets = $featuredpets->fetch(PDO::FETCH_ASSOC);
$featuredsciencetech = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 26 ORDER BY picks.featured DESC LIMIT 1");
$featuredsciencetech = $featuredsciencetech->fetch(PDO::FETCH_ASSOC);
$featuredsports = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 17 ORDER BY picks.featured DESC LIMIT 1");
$featuredsports = $featuredsports->fetch(PDO::FETCH_ASSOC);
$featuredtravel = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 19 ORDER BY picks.featured DESC LIMIT 1");
$featuredtravel = $featuredtravel->fetch(PDO::FETCH_ASSOC);
$featuredvideogames = $conn->query("SELECT * FROM picks LEFT JOIN videos ON videos.vid = picks.video LEFT JOIN users ON users.uid = videos.uid WHERE videos.converted = 1 AND videos.privacy = 1 AND videos.category = 20 ORDER BY picks.featured DESC LIMIT 1");
$featuredvideogames = $featuredvideogames->fetch(PDO::FETCH_ASSOC);
?>
<div id="sideAd">
 	
 			
		
			

			
					<!-- begin ad tag -->
	<script type="text/javascript">
		ord=Math.random()*10000000000000000 + 2;
		document.write('<script language="JavaScript" src="http://ad.doubleclick.net/adj/you.categories/main;sz=160x600;kch=4610185682;kbg=FFFFFF;;ord=' + ord + '?" type="text/javascript"><\/script>');
	</script>
	<noscript><a
		href="http://ad.doubleclick.net/jump/you.categories/main;sz=160x600;ord=123456789?" target="_blank"><img
		src="http://ad.doubleclick.net/ad/you.categories/main;sz=160x600;ord=123456789?" width="160" height="600" border="0" alt=""></a>
	</noscript>
	<!-- End ad tag -->
	

		
		
</div>


<div id="mainContent">

<div id="sectionHeader" class="categoriesColor">
	<div class="name">Categories</div>
	<span class="title">All Categories &nbsp; </span>
	<span class="normalText">(Featured videos in each category)</span>
</div>


<div id="sideNav">
	<div class="navBody12" style="padding-top: 8px;">
		<div class="label">&raquo; All</div>
			<div><a href="/categories_portal?c=1&e=1">Arts & Animation</a></div>
			<div><a href="/categories_portal?c=2&e=1">Autos & Vehicles</a></div>
			<div><a href="/categories_portal?c=23&e=1">Comedy</a></div>
			<div><a href="/categories_portal?c=24&e=1">Entertainment</a></div>
			<div><a href="/categories_portal?c=10&e=1">Music</a></div>
			<div><a href="/categories_portal?c=25&e=1">News & Blogs</a></div>
			<div><a href="/categories_portal?c=22&e=1">People</a></div>
			<div><a href="/categories_portal?c=15&e=1">Pets & Animals</a></div>
			<div><a href="/categories_portal?c=26&e=1">Science & Technology</a></div>
			<div><a href="/categories_portal?c=17&e=1">Sports</a></div>
			<div><a href="/categories_portal?c=19&e=1">Travel & Places</a></div>
			<div><a href="/categories_portal?c=20&e=1">Video Games</a></div>
	</div>
</div> <!-- end sideNav -->



<div id="mainContentWithNav" style="padding-top: 8px;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr valign="top">
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=1&e=1" class="dg">Arts & Animation</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredarts['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredarts['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredarts['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredarts['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredarts['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredarts['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredarts['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=1&e=1">more in <b>Arts & Animation</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=2&e=1" class="dg">Autos & Vehicles</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredautos['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredautos['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredautos['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredautos['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredautos['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredautos['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredautos['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=2&e=1">more in <b>Autos & Vehicles</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=23&e=1" class="dg">Comedy</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredcomedy['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredcomedy['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredcomedy['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredcomedy['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredcomedy['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredcomedy['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredcomedy['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=23&e=1">more in <b>Comedy</b></a>
			</div>
	</div>

			</td>	
		</tr>
		<tr valign="top">
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=24&e=1" class="dg">Entertainment</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredentertainment['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredentertainment['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredentertainment['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredentertainment['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredentertainment['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredentertainment['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredentertainment['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=24&e=1">more in <b>Entertainment</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=10&e=1" class="dg">Music</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredmusic['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredmusic['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredmusic['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredmusic['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredmusic['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredmusic['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredmusic['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=10&e=1">more in <b>Music</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=25&e=1" class="dg">News & Blogs</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featurednewsblogs['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featurednewsblogs['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featurednewsblogs['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featurednewsblogs['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featurednewsblogs['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featurednewsblogs['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featurednewsblogs['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=25&e=1">more in <b>News & Blogs</b></a>
			</div>
	</div>

			</td>	
		</tr>
		<tr valign="top">
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=22&e=1" class="dg">People</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredpeople['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredpeople['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredpeople['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredpeople['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredpeople['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredpeople['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredpeople['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=22&e=1">more in <b>People</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=15&e=1" class="dg">Pets & Animals</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredpets['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredpets['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredpets['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredpets['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredpets['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredpets['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredpets['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=15&e=1">more in <b>Pets & Animals</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=26&e=1" class="dg">Science & Technology</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredsciencetech['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredsciencetech['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredsciencetech['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredsciencetech['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredsciencetech['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredsciencetech['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredsciencetech['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=26&e=1">more in <b>Science & Technology</b></a>
			</div>
	</div>

			</td>	
		</tr>
		<tr valign="top">
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=17&e=1" class="dg">Sports</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredsports['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredsports['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredsports['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredsports['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredsports['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredsports['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredsports['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=17&e=1">more in <b>Sports</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=19&e=1" class="dg">Travel & Places</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredtravel['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredtravel['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredtravel['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredtravel['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredtravel['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredtravel['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredtravel['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=19&e=1">more in <b>Travel & Places</b></a>
			</div>
	</div>

			</td>	
			<td width="33%">
				<div class="catEntry">
		<div class="catNameHeader"><a href="/categories_portal?c=20&e=1" class="dg">Video Games</a></div>
		
			<div class="catFeatureVidDiv">
				<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($featuredvideogames['vid']); ?>" name="&lid="><img src="/get_still.php?video_id=<?php echo htmlspecialchars($featuredvideogames['vid']); ?>" class="vimg90" alt="videoStill" /></a></div>
				<div class="vtitle">
					<a href="/watch?v=<?php echo htmlspecialchars($featuredvideogames['vid']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredvideogames['title']); ?></a><br/>
					<span class="runtime"><?php echo gmdate("i:s", $featuredvideogames['time']); ?></span>
				</div>
				
				<div class="vfacets">
					<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($featuredvideogames['username']); ?>" class="dg noul"><?php echo htmlspecialchars($featuredvideogames['username']); ?></a><br/>
				</div>
				
			</div>

			<div class="catMoreDiv">
			<a href="/categories_portal?c=20&e=1">more in <b>Video Games</b></a>
			</div>
	</div>

			</td>	
		</tr>
	</table>
</div> <!-- end mainContentWithNav -->

</div> <!-- end mainContent -->

<?php
require "needed/end.php";
?>