<?php

// Built by idsniper on Aug 24, 2022 for my_clips.swf on YRT, modified to work with KamTape and expanded to be compatible with the entire API from way back

header("Content-Type: text/xml");

require($_SERVER["DOCUMENT_ROOT"] . "/includes/functions.php");

if(!isset($_GET['user'])) die();

$profile = $conn->prepare("SELECT * FROM members WHERE members.username = ?");
$profile->execute([$_GET['user']]);

if($profile->rowCount() == 0) {
	die();
} else {
	$profile = $profile->fetch(PDO::FETCH_ASSOC);
}

$profile['videos'] = $conn->prepare("SELECT video_id FROM videos WHERE member_id = ? AND converted = 1");
$profile['videos']->execute([$profile["member_id"]]);
$profile['videos'] = $profile['videos']->rowCount();
$profile['favorites'] = $conn->prepare("SELECT favorite_id FROM favorites WHERE member_id = ?");
$profile['favorites']->execute([$profile["member_id"]]);
$profile['favorites'] = $profile['favorites']->rowCount();

$videos = $conn->prepare("SELECT * FROM videos LEFT JOIN members ON members.member_id = videos.member_id WHERE videos.member_id = ? AND videos.converted = 1 ORDER BY videos.uploaded_at DESC");
$videos->execute([$profile['member_id']]);
?>

<ut_response>
	<response_type>sequence_response</response_type>
	<response>
		<sequence_items>
<?php if($videos !== false) { ?>
<?php foreach($videos as $video) { ?>
<?php
$video['views'] = $conn->prepare("SELECT COUNT(view_id) AS views FROM views WHERE video_id = ?");
$video['views']->execute([$video['video_id']]);
$video['views'] = $video['views']->fetchColumn();
$video['comments'] = $conn->prepare("SELECT COUNT(comment_id) AS comments FROM comments WHERE video_id = ?");
$video['comments']->execute([$video['video_id']]);
$video['comments'] = $video['comments']->fetchColumn();
$tags = explode(" ", $video['tags']);
?>
			<sequence_item>
				<id><?php echo htmlspecialchars($video['video_id']); ?></id>
				<author><?php echo htmlspecialchars($video['username']); ?></author>
				<title><?php echo htmlspecialchars($video['title']); ?></title>
				<keywords></keywords>
				<description><?php echo htmlspecialchars($video['description']); ?></description>
				<date_uploaded><?php echo date("F j, Y", strtotime($video['uploaded_at']. ' - 17 years')); ?></date_uploaded>
				<view_count><?php echo $video['views']; ?></view_count>
				<comment_count><?php echo $video['comments']; ?></comment_count>
			</sequence_item>
<?php } ?>
<?php } ?>
		</sequence_items>
	</response>
</ut_response>
