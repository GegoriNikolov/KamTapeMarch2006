<?php 
require "needed/start.php";
$video = $conn->prepare("SELECT * FROM videos WHERE vid = ? AND converted = 1");
$video->execute([$_GET['v']]);
if($video->rowCount() == 0) {
	http_response_code(500); // that's how youtube handled it back then
	die();
} else {
	$video = $video->fetch(PDO::FETCH_ASSOC);
}
$uploader = $conn->prepare("SELECT * FROM users WHERE uid = ?");
$uploader->execute([$video['uid']]);
$uploader = $uploader->fetch(PDO::FETCH_ASSOC);
    $vresponses = $conn->prepare(
	"SELECT * FROM vidresponses
	LEFT JOIN videos ON vidresponses.responsevid = videos.vid
	LEFT JOIN users ON users.uid = videos.uid
	WHERE vidresponses.responseto = ? AND (videos.converted = 1 AND videos.privacy = 1) AND vidresponses.accepted = 1
	ORDER BY vidresponses.rid DESC" // now this should normally be paginated but the highest number of responses that i've seen in an archive (https://web.archive.org/web/20061206055127/http://www.youtube.com/video_response_view_all?v=o8tvNj_1Fr0) was 19 so
);
$vresponses->execute([$video['vid']]);
$vidresnum = $vresponses->rowCount() + 1;
$trvidresnum = 0;
?>	
	
	
	





<h1><?php echo htmlspecialchars($video['title']); ?></h1>

<table class="vTable" cellpadding="4">
	<tr valign="top">
	<td><a href="/watch?v=<?php echo htmlspecialchars($video['vid']); ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($video['vid']); ?>" class="vimg120" /></a></td>
	<td>
		<div class="vtitle">
			<a href="/watch?v=<?php echo htmlspecialchars($video['vid']); ?>"><?php echo htmlspecialchars($video['title']); ?></a>
		</div>
		<div class="vfacets">
			<span class="grayText">From:</span>
			<a href="/profile?user=<?php echo htmlspecialchars($uploader['username']); ?>"><?php echo htmlspecialchars($uploader['username']); ?></a>	
		</div>
		<div class="vdesc">
				
			<span id="BeginvidDesc<?php echo htmlspecialchars($video['vid']); ?>">
	<?php echo shorten($video['description'], 258, ''); ?>
	</span>
	
			<?php if (shorten($video['description'], 258, '') != htmlspecialchars($video['description'])) { ?>
			<span id="RemainvidDesc<?php echo htmlspecialchars($video['vid']); ?>" style="display: none"><?php echo htmlspecialchars($video['description']); ?></span>
			<span id="MorevidDesc<?php echo htmlspecialchars($video['vid']); ?>" class="smallText">(<a href="#" class="eLink" onclick="showInline('RemainvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); hideInline('MorevidDesc<?php echo htmlspecialchars($video['vid']); ?>'); hideInline('BeginvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); showInline('LessvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); return false;">more</a>)</span>
			<span id="LessvidDesc<?php echo htmlspecialchars($video['vid']); ?>" style="display: none" class="smallText">(<a href="#" class="eLink" onclick="hideInline('RemainvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); hideInline('LessvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); showInline('BeginvidDesc<?php echo htmlspecialchars($video['vid']); ?>'); showInline('MorevidDesc<?php echo htmlspecialchars($video['vid']); ?>'); return false;">less</a>)</span>
			<? } ?>


</div>
		</div>
	</td>
	</tr>
</table>

<br />


	<div class="headerRCBox">
	<b class="rch">
	<b class="rch1"><b></b></b>
	<b class="rch2"><b></b></b>
	<b class="rch3"></b>
	<b class="rch4"></b>
	<b class="rch5"></b>
	</b> <div class="content">	<div class="headerTitleRight"><?php echo $vresponses->rowCount(); ?> Responses</div>
	<div class="headerTitle">Video Responses</div>
</div>
	</div>

		
<div class="vGridBox"> 
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<?php foreach($vresponses as $vresponse) { $vidresnum = $vidresnum - 1; $trvidresnum = $trvidresnum + 1; ?>
				<td><div class="v120vEntry">
					<div class="vstill"><a href="/watch?v=<?php echo htmlspecialchars($vresponse['responsevid']); ?>"><img src="/get_still.php?video_id=<?php echo htmlspecialchars($vresponse['responsevid']); ?>" class="vimg"></a></div>
					<div class="vtitle">
						<a href="/watch?v=<?php echo htmlspecialchars($vresponse['responsevid']); ?>"><?php echo htmlspecialchars($vresponse['title']); ?></a><br/>
						<span class="runtime"><?php echo gmdate("i:s", $vresponse['time']); ?></span>
					</div>
					<div class="vfacets">
						<span class="grayText">From:</span> <a href="/user/<?php echo htmlspecialchars($vresponse['username']); ?>"><?php echo htmlspecialchars($vresponse['username']); ?></a><br/>
						<span class="grayText">Response #:</span> <?php echo htmlspecialchars($vidresnum); ?><br/>
					</div>
				</div></td>
				<?php if ($trvidresnum == 6) { $trvidresnum = 0; ?>
					<tr>
					<? } ?>
					
					<? } ?>
			
	</table>
</div> <!-- end vGridBox -->
<div class="footerBox">





</div>
	

<?php 
require "needed/end.php";
?>